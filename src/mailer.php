<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendVerificationEmail($email, $codeOrMessage, $isCode = true) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'anshitraj0@gmail.com';      
        $mail->Password   = 'bleu lbvu cvex aspp';         
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email content
        $mail->setFrom('anshitraj0@gmail.com', 'GH Timeline'); 
        $mail->addAddress($email);

        if ($isCode) {
            $mail->Subject = 'Your Verification Code';
            $mail->Body    = "Hi,\n\nYour verification code is: $codeOrMessage\n\nThanks!";
        } else {
            $mail->Subject = 'GitHub Timeline Update';
            $mail->Body    = $codeOrMessage;
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
    
        return false;
    }
}

function sendWelcomeEmail($email) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'anshitraj0@gmail.com';
        $mail->Password   = 'bleu lbvu cvex aspp';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email content
        $mail->setFrom('anshitraj0@gmail.com', 'GH Timeline');
        $mail->addAddress($email);

        $mail->Subject = "🎉 You're subscribed to GitHub Timeline updates!";
        $mail->Body    = "Hi,\n\nThank you for verifying your email. You'll now receive GitHub activity updates automatically from @torvalds!\n\nCheers,\nGH Timeline";

        $mail->send();
        return true;
    } catch (Exception $e) {
        
        return false;
    }
}

