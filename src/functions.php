<?php

function generateVerificationCode() {
    return rand(100000, 999999);
}

function registerEmail($email) {
    $file = __DIR__ . '/../registered_emails.txt';
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (trim($line) === $email) return false;
    }
    file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    return true;
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/../registered_emails.txt';
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $newLines = array_filter($lines, function($line) use ($email) {
        return trim($line) !== $email;
    });
    file_put_contents($file, implode(PHP_EOL, $newLines) . PHP_EOL);
    return true;
}

function sendVerificationEmail($email, $code) {
    $subject = 'Your Verification Code';
    $message = '<p>Your verification code is: <strong>' . htmlspecialchars($code) . '</strong></p>';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: no-reply@example.com' . "\r\n";
    // Log to file for testing instead of sending
    file_put_contents(__DIR__ . '/../mail_log.txt', "To: $email | Subject: $subject | Message: $message\n", FILE_APPEND);
    // return mail($email, $subject, $message, $headers); // Disabled for assignment compliance
    return true;
}

function fetchGitHubTimeline() {
    $url = 'https://www.github.com/timeline';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'GH-Timeline-App');
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function formatGitHubData($data) {
    $events = json_decode($data, true);
    if (!is_array($events)) {
        return '<h2>GitHub Timeline Updates</h2><p>Could not fetch timeline data.</p>';
    }
    $html = '<h2>GitHub Timeline Updates</h2><table border="1"><tr><th>Event</th><th>User</th></tr>';
    $count = 0;
    foreach ($events as $event) {
        if (isset($event['type']) && isset($event['actor']['login'])) {
            $html .= '<tr><td>' . htmlspecialchars($event['type']) . '</td><td>' . htmlspecialchars($event['actor']['login']) . '</td></tr>';
            $count++;
        }
        if ($count >= 10) break;
    }
    $html .= '</table>';
    return $html;
}

function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/../registered_emails.txt';
    if (!file_exists($file)) return;
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $data = fetchGitHubTimeline();
    $html = formatGitHubData($data);
    $subject = 'Latest GitHub Updates';
    foreach ($emails as $email) {
        $unsubscribe_url = 'http://' . $_SERVER['HTTP_HOST'] . '/src/unsubscribe.php?email=' . urlencode($email);
        $body = $html . '<p><a href="' . $unsubscribe_url . '" id="unsubscribe-button">Unsubscribe</a></p>';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: no-reply@example.com' . "\r\n";
        // Log to file for testing instead of sending
        file_put_contents(__DIR__ . '/../mail_log.txt', "To: $email | Subject: $subject | Message: $body\n", FILE_APPEND);
        // mail($email, $subject, $body, $headers); // Disabled for assignment compliance
    }
}
