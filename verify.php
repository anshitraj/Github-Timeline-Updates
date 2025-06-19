<?php
require_once 'src/functions.php';

function showVerifyForm($email, $feedback = '') {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Email Verification</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body { background: linear-gradient(135deg, #f4f6fb 60%, #e0e7ff 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: Roboto, Arial, sans-serif; }
            .container { background: #fff; padding: 2.5rem 2rem 2rem 2rem; border-radius: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.12); max-width: 400px; width: 100%; text-align: center; }
            h2 { color: #22223b; margin-bottom: 1.5rem; font-size: 2rem; font-weight: 700; }
            .feedback { margin-bottom: 1.2rem; font-size: 1.1rem; }
            .success { color: #22c55e; font-weight: bold; }
            .error { color: #ef4444; font-weight: bold; }
            .info { color: #2563eb; font-weight: bold; }
            input[type="text"] { width: 100%; padding: 0.7rem; border: 1px solid #c9c9c9; border-radius: 8px; margin-bottom: 1.2rem; font-size: 1rem; transition: border 0.2s; background: #f8fafc; }
            input:focus { border: 1.5px solid #4f8cff; outline: none; background: #fff; }
            button { background: #4f8cff; color: #fff; border: none; padding: 0.7rem 1.5rem; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s, box-shadow 0.2s; box-shadow: 0 2px 8px rgba(79,140,255,0.08); }
            button:hover { background: #2563eb; box-shadow: 0 4px 16px rgba(79,140,255,0.12); }
            .back-link { display: block; margin-top: 1.5rem; color: #4f8cff; text-decoration: none; font-weight: 500; }
            .back-link:hover { text-decoration: underline; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Email Verification</h2>';
    if ($feedback) echo $feedback;
    echo '<form action="verify.php" method="POST">
                <input type="hidden" name="email" value="'.htmlspecialchars($email, ENT_QUOTES).'">
                <input type="text" name="verification_code" maxlength="6" required placeholder="Enter your verification code">
                <button id="submit-verification">Verify</button>
            </form>
            <a href="index.php" class="back-link">&larr; Back to Home</a>
        </div>
    </body>
    </html>';
}

if (isset($_POST['verification_code']) && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $code = trim($_POST['verification_code']);
    $lines = file_exists(__DIR__ . '/verify_codes.txt') ? file(__DIR__ . '/verify_codes.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
    $found = false;
    foreach ($lines as $line) {
        list($savedEmail, $savedCode) = explode('|', $line);
        if ($savedEmail === $email && $savedCode === $code) {
            registerEmail($email);
            // Redirect to src/unsubscribe.php with subscription confirmation
            header('Location: src/unsubscribe.php?email=' . urlencode($email) . '&verified=1');
            exit;
        }
    }
    if (!$found) {
        $feedback = '<div class="feedback error">❌ Invalid code or email. Please try again.</div>';
        showVerifyForm($email, $feedback);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['verification_code'])) {
    $email = trim($_POST['email']);
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
    if (in_array($email, $emails)) {
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Already Verified</title><style>body{background:#f4f6fb;min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:Roboto,Arial,sans-serif}.container{background:#fff;padding:2.5rem 2rem 2rem 2rem;border-radius:20px;box-shadow:0 8px 32px rgba(0,0,0,0.12);max-width:400px;width:100%;text-align:center}h2{color:#22223b;margin-bottom:1.5rem;font-size:2rem;font-weight:700}.success{color:#22c55e;font-weight:bold;}</style></head><body><div class="container"><h2>Email Verification</h2><div class="success">✅ You are already subscribed and verified!</div><a href="index.php" class="back-link">&larr; Back to Home</a></div></body></html>';
        exit;
    }
    // Generate and send code
    $code = generateVerificationCode();
    file_put_contents(__DIR__ . '/verify_codes.txt', "$email|$code\n", FILE_APPEND);
    if (sendVerificationEmail($email, $code)) {
        $feedback = '<div class="feedback info">📧 Verification email sent to <b>' . htmlspecialchars($email) . '</b></div>';
    } else {
        $feedback = '<div class="feedback error">⚠️ Failed to send email. Showing code directly for testing.<br>Your code is: <b>' . htmlspecialchars($code) . '</b></div>';
    }
    showVerifyForm($email, $feedback);
    exit;
}
?>
