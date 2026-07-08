<?php
require_once 'includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'Forbidden']);
    exit;
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$phone   = trim($_POST['phone']   ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if (strlen($name) < 2)               $errors[] = 'Please enter your name.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
if (strlen($message) < 10)           $errors[] = 'Message is too short.';

if ($errors) {
    echo json_encode(['ok' => false, 'message' => implode(' ', $errors)]);
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare(
        "INSERT INTO enquiries (name, email, phone, service, message, ip_address)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$name, $email, $phone, $service, $message, $_SERVER['REMOTE_ADDR'] ?? '']);

    // Email notification (optional – configure in CPanel)
    $adminEmail = ADMIN_EMAIL;
    $subject = "New Enquiry from $name — Creative Elements";
    $body  = "Name: $name\nEmail: $email\nPhone: $phone\nService: $service\n\nMessage:\n$message";
    $headers = "From: noreply@creativelements.co\r\nReply-To: $email";
    @mail($adminEmail, $subject, $body, $headers);

    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Server error. Please try again.']);
}
