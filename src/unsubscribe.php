<?php
require_once 'functions.php';

$step = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['unsubscribe_email'])) {
        $email = trim($_POST['unsubscribe_email']);
        if (!empty($email)) {
            $code = generateVerificationCode();
            file_put_contents(__DIR__ . '/../unsubscribe_codes.txt', "$email|$code\n", FILE_APPEND);
            // Send code email
            $subject = 'Confirm Unsubscription';
            $body = '<p>To confirm unsubscription, use this code: <strong>' . htmlspecialchars($code) . '</strong></p>';
            $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: no-reply@example.com\r\n";
            // Log to file for testing instead of sending
            file_put_contents(__DIR__ . '/../mail_log.txt', "To: $email | Subject: $subject | Message: $body\n", FILE_APPEND);
            // mail($email, $subject, $body, $headers); // Disabled for assignment compliance
            $message = 'A confirmation code has been sent to your email.';
            $step = 'code';
        }
    } elseif (isset($_POST['unsubscribe_verification_code']) && isset($_POST['unsubscribe_email_code'])) {
        $email = trim($_POST['unsubscribe_email_code']);
        $code = trim($_POST['unsubscribe_verification_code']);
        $lines = file(__DIR__ . '/../unsubscribe_codes.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $found = false;
        foreach ($lines as $line) {
            list($savedEmail, $savedCode) = explode('|', $line);
            if ($savedEmail === $email && $savedCode === $code) {
                unsubscribeEmail($email);
                $found = true;
                $message = 'You have been unsubscribed.';
                break;
            }
        }
        if (!$found) {
            $message = 'Invalid code or email.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unsubscribe from GitHub Timeline</title>
    <style>
        body { background: #f4f6fb; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', Arial, sans-serif; }
        .container { background: #fff; padding: 2.5rem 2rem 2rem 2rem; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); max-width: 350px; width: 100%; text-align: center; }
        h2 { color: #22223b; margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; color: #4a4e69; font-weight: 500; }
        input[type="email"], input[type="text"] { width: 100%; padding: 0.7rem; border: 1px solid #c9c9c9; border-radius: 8px; margin-bottom: 1.2rem; font-size: 1rem; transition: border 0.2s; }
        input:focus { border: 1.5px solid #4f8cff; outline: none; }
        button { background: #4f8cff; color: #fff; border: none; padding: 0.7rem 1.5rem; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        button:hover { background: #2563eb; }
        .msg { margin-bottom: 1rem; color: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Unsubscribe</h2>
        <?php if ($message) { echo '<div class="msg">' . $message . '</div>'; } ?>
        <form method="POST">
            <label for="unsubscribe_email">Email:</label>
            <input type="email" name="unsubscribe_email" required>
            <button id="submit-unsubscribe">Unsubscribe</button>
        </form>
        <form method="POST">
            <label for="unsubscribe_verification_code">Verification Code:</label>
            <input type="text" name="unsubscribe_verification_code" maxlength="6">
            <input type="email" name="unsubscribe_email_code" placeholder="Enter your email again" required>
            <button id="verify-unsubscribe">Verify</button>
        </form>
    </div>
</body>
</html> 