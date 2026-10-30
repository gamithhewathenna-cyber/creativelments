<?php
// Word verification for the contact form. The word only ever lives in the session —
// the browser just gets a picture of it — and each word can be checked once.

// Pronounceable made-up word (e.g. "toravi"). About 274,000 combinations, so a bot
// can't get through by guessing from a short fixed word list.
function generateCaptchaWord() {
    $consonants = 'bdfgkmnprstvz';
    $vowels     = 'aeiou';
    $word = '';
    for ($i = 0; $i < 3; $i++) {
        $word .= $consonants[random_int(0, strlen($consonants) - 1)];
        $word .= $vowels[random_int(0, strlen($vowels) - 1)];
    }
    return $word;
}

// Starts a new challenge and outputs it as an image, replacing any previous word.
function outputCaptchaImage() {
    $word = generateCaptchaWord();
    $_SESSION['captcha_word'] = $word;

    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    // An explicit Expires also stops the .htaccess 1-year image rule applying here.
    header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

    if (extension_loaded('gd')) {
        outputCaptchaPng($word);
    } else {
        outputCaptchaSvg($word);
    }
}

function outputCaptchaPng($word) {
    // Draw small with GD's built-in font, then enlarge — no font file needed on the server.
    $small = imagecreatetruecolor(92, 24);
    imagefill($small, 0, 0, imagecolorallocate($small, 241, 245, 249));
    $ink = imagecolorallocate($small, 128, 72, 153);
    foreach (str_split($word) as $i => $letter) {
        imagestring($small, 5, 6 + $i * 14, random_int(1, 8), $letter, $ink);
    }

    $img = imagecreatetruecolor(230, 60);
    imagecopyresampled($img, $small, 0, 0, 0, 0, 230, 60, 92, 24);

    // Light noise lines: confuses simple text recognition but stays easy for people to read.
    imagesetthickness($img, 2);
    $noise = imagecolorallocate($img, 196, 170, 208);
    for ($i = 0; $i < 5; $i++) {
        imageline($img, 0, random_int(0, 59), 229, random_int(0, 59), $noise);
    }

    header('Content-Type: image/png');
    imagepng($img);
}

// Fallback so the form keeps working if a host has no GD extension.
function outputCaptchaSvg($word) {
    $letters = '';
    foreach (str_split($word) as $i => $letter) {
        $x = 22 + $i * 34;
        $y = random_int(34, 46);
        $letters .= '<text x="' . $x . '" y="' . $y . '" transform="rotate(' . random_int(-14, 14) . ' ' . $x . ' ' . $y . ')">' . $letter . '</text>';
    }
    header('Content-Type: image/svg+xml');
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="230" height="60" viewBox="0 0 230 60">'
       . '<rect width="230" height="60" fill="#F1F5F9"/>'
       . '<g fill="#804899" font-family="monospace" font-size="30" font-weight="bold" text-anchor="middle">' . $letters . '</g>'
       . '</svg>';
}

// Case-insensitive check. The word is always cleared first, so a right or wrong
// answer uses it up and a bot can't retry or replay the same challenge.
function verifyCaptcha($answer) {
    $word = $_SESSION['captcha_word'] ?? '';
    unset($_SESSION['captcha_word']);

    if ($word === '' || !is_string($answer)) return false;
    return hash_equals($word, strtolower(trim($answer)));
}
