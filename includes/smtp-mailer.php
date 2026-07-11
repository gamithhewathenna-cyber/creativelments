<?php
// Minimal dependency-free SMTP client — no Composer/PHPMailer required.
// Speaks raw SMTP over a socket so outgoing mail goes through a real mailbox
// (Gmail, Zoho, cPanel mail, etc.) instead of relying on PHP's mail(), which
// is frequently unreliable/spam-flagged on shared hosting.

class SmtpMailer {
    private $host, $port, $username, $password, $encryption, $timeout = 15;
    private $socket;
    private $lastError = '';

    public function __construct($host, $port, $username, $password, $encryption) {
        $this->host       = $host;
        $this->port       = $port;
        $this->username   = $username;
        $this->password   = $password;
        $this->encryption = $encryption; // '', 'ssl', or 'tls'
    }

    public function getError() {
        return $this->lastError;
    }

    private function connect() {
        $host = $this->encryption === 'ssl' ? 'ssl://' . $this->host : $this->host;
        $this->socket = @fsockopen($host, $this->port, $errno, $errstr, $this->timeout);
        if (!$this->socket) {
            $this->lastError = "Could not connect to {$this->host}:{$this->port} — $errstr ($errno)";
            return false;
        }
        stream_set_timeout($this->socket, $this->timeout);
        $this->readResponse();
        return true;
    }

    private function send($cmd) {
        fwrite($this->socket, $cmd . "\r\n");
    }

    private function readResponse() {
        $data = '';
        while (($line = fgets($this->socket, 515)) !== false) {
            $data .= $line;
            if (isset($line[3]) && $line[3] === ' ') break;
            if ($line === false) break;
        }
        return $data;
    }

    private function expect($code, $response) {
        return substr(trim($response), 0, 3) === (string) $code;
    }

    public function sendMail($fromEmail, $fromName, $to, $subject, $body, $replyTo = '', $isHtml = false) {
        if (!$this->connect()) return false;

        $helloName = $_SERVER['SERVER_NAME'] ?? 'localhost';

        $this->send('EHLO ' . $helloName);
        $this->readResponse();

        if ($this->encryption === 'tls') {
            $this->send('STARTTLS');
            $resp = $this->readResponse();
            if (!$this->expect(220, $resp)) { $this->lastError = 'STARTTLS was refused: ' . trim($resp); fclose($this->socket); return false; }
            if (!stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                $this->lastError = 'TLS handshake failed.';
                fclose($this->socket);
                return false;
            }
            $this->send('EHLO ' . $helloName);
            $this->readResponse();
        }

        if ($this->username !== '') {
            $this->send('AUTH LOGIN');
            $resp = $this->readResponse();
            if (!$this->expect(334, $resp)) { $this->lastError = 'AUTH LOGIN not supported: ' . trim($resp); fclose($this->socket); return false; }

            $this->send(base64_encode($this->username));
            $resp = $this->readResponse();
            if (!$this->expect(334, $resp)) { $this->lastError = 'Username rejected: ' . trim($resp); fclose($this->socket); return false; }

            $this->send(base64_encode($this->password));
            $resp = $this->readResponse();
            if (!$this->expect(235, $resp)) { $this->lastError = 'Login failed — check SMTP username/password: ' . trim($resp); fclose($this->socket); return false; }
        }

        $this->send('MAIL FROM:<' . $fromEmail . '>');
        $resp = $this->readResponse();
        if (!$this->expect(250, $resp)) { $this->lastError = 'MAIL FROM rejected: ' . trim($resp); fclose($this->socket); return false; }

        $this->send('RCPT TO:<' . $to . '>');
        $resp = $this->readResponse();
        if (!$this->expect(250, $resp) && !$this->expect(251, $resp)) { $this->lastError = 'RCPT TO rejected: ' . trim($resp); fclose($this->socket); return false; }

        $this->send('DATA');
        $resp = $this->readResponse();
        if (!$this->expect(354, $resp)) { $this->lastError = 'DATA rejected: ' . trim($resp); fclose($this->socket); return false; }

        $headers   = [];
        $headers[] = 'From: ' . ($fromName !== '' ? mb_encode_mimeheader($fromName, 'UTF-8') . " <$fromEmail>" : $fromEmail);
        $headers[] = 'To: ' . $to;
        if ($replyTo !== '') $headers[] = 'Reply-To: ' . $replyTo;
        $headers[] = 'Subject: ' . mb_encode_mimeheader($subject, 'UTF-8');
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: ' . ($isHtml ? 'text/html' : 'text/plain') . '; charset=UTF-8';
        $headers[] = 'Date: ' . date('r');

        // Per RFC 5321, a lone "." on its own line ends the DATA block — escape any such lines in the body.
        $escapedBody = preg_replace('/^\./m', '..', $body);

        $this->send(implode("\r\n", $headers) . "\r\n\r\n" . $escapedBody . "\r\n.");
        $resp = $this->readResponse();
        if (!$this->expect(250, $resp)) { $this->lastError = 'Message rejected by server: ' . trim($resp); fclose($this->socket); return false; }

        $this->send('QUIT');
        fclose($this->socket);
        return true;
    }
}

// Sends an email using the SMTP settings saved in Admin → Settings.
// Falls back to PHP's mail() if no SMTP host has been configured.
// Returns true on success; on failure, $errorOut (if passed) is filled with the reason.
function sendAppEmail($db, $to, $subject, $body, $replyTo = '', &$errorOut = null, $isHtml = false) {
    $rows = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp_%'")->fetchAll();
    $s = [];
    foreach ($rows as $r) { $s[$r['setting_key']] = $r['setting_value']; }

    if (empty($s['smtp_host'])) {
        $headers = 'From: ' . ADMIN_EMAIL . "\r\n";
        if ($replyTo !== '') $headers .= "Reply-To: $replyTo\r\n";
        if ($isHtml) $headers .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
        $ok = @mail($to, $subject, $body, $headers);
        if (!$ok) $errorOut = 'PHP mail() failed and no SMTP is configured in Admin → Settings.';
        return $ok;
    }

    $mailer = new SmtpMailer(
        $s['smtp_host'],
        intval($s['smtp_port'] ?? 587),
        $s['smtp_username'] ?? '',
        $s['smtp_password'] ?? '',
        $s['smtp_encryption'] ?? 'tls'
    );
    $fromEmail = $s['smtp_from_email'] ?: ($s['smtp_username'] ?: ADMIN_EMAIL);
    $fromName  = $s['smtp_from_name'] ?: SITE_NAME;

    $ok = $mailer->sendMail($fromEmail, $fromName, $to, $subject, $body, $replyTo, $isHtml);
    if (!$ok) $errorOut = $mailer->getError();
    return $ok;
}

// Wraps enquiry details in a clean branded HTML email template.
function buildEnquiryEmailHtml($fields) {
    $rowsHtml = '';
    foreach ($fields as $label => $value) {
        if ($value === '') continue;
        $rowsHtml .= '<tr>'
            . '<td style="padding:10px 16px;border-bottom:1px solid #E2E8F0;color:#8892A4;font-size:13px;font-weight:600;white-space:nowrap;vertical-align:top">' . htmlspecialchars($label) . '</td>'
            . '<td style="padding:10px 16px;border-bottom:1px solid #E2E8F0;color:#0A0F1E;font-size:14px">' . nl2br(htmlspecialchars($value)) . '</td>'
            . '</tr>';
    }
    return '<!DOCTYPE html><html><body style="margin:0;padding:0;background:#F1F5F9;font-family:Arial,Helvetica,sans-serif">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9;padding:32px 16px">'
        . '<tr><td align="center">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:12px;overflow:hidden">'
        . '<tr><td style="background:#804899;padding:20px 24px">'
        . '<span style="color:#ffffff;font-size:18px;font-weight:700">' . htmlspecialchars(SITE_NAME) . '</span>'
        . '</td></tr>'
        . '<tr><td style="padding:24px">'
        . '<h2 style="margin:0 0 16px;color:#0A0F1E;font-size:18px">New Website Enquiry</h2>'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $rowsHtml . '</table>'
        . '</td></tr>'
        . '<tr><td style="padding:16px 24px;background:#F8FAFC;color:#8892A4;font-size:12px">Sent automatically from the contact form on ' . htmlspecialchars(SITE_URL) . '</td></tr>'
        . '</table>'
        . '</td></tr>'
        . '</table>'
        . '</body></html>';
}
