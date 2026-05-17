<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_smtp_mail(
    string $toEmail,
    string $toName,
    string $subject,
    string $htmlBody,
    string $plainBody = ''
): bool {   

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();

        $mail->Host = env('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Port = env('MAIL_PORT');

        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom(
            env('MAIL_FROM'),
            env('MAIL_FROM_NAME')
        );

        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $htmlBody;

        $mail->AltBody = $plainBody !== ''
            ? $plainBody
            : strip_tags($htmlBody);

        return $mail->send();

    } catch (Exception $e) {

        error_log('Mailer Error: ' . $mail->ErrorInfo);

        return false;
    }
}

function send_verification_email(
    string $email,
    string $name,
    string $token
): bool {

    $verifyUrl = env('APP_URL') .
        '/verify.php?token=' .
        urlencode($token);

    $subject = 'Potvrdi svoj Roo račun';

    $safeName = htmlspecialchars(
        $name,
        ENT_QUOTES,
        'UTF-8'
    );

    $html = "
        <h2>Pozdrav, {$safeName}!</h2>

        <p>Klikni na link ispod da potvrdiš svoj račun:</p>

        <p>
            <a href=\"{$verifyUrl}\">
                Potvrdi račun
            </a>
        </p>

        <p>
            Ako gumb ne radi, kopiraj ovaj link:
        </p>

        <p>{$verifyUrl}</p>

        <p>Link vrijedi 24 sata.</p>
    ";

    $plain = "
Pozdrav, {$name}!

Potvrdi račun ovdje:
{$verifyUrl}

Link vrijedi 24 sata.
";

    return send_smtp_mail(
        $email,
        $name,
        $subject,
        $html,
        $plain
    );
}

function send_reset_email(
    string $email,
    string $name,
    string $token
): bool {

    $resetUrl = env('APP_URL') .
        '/reset-password.php?token=' .
        urlencode($token);

    $subject = 'Roo - Reset lozinke';

    $safeName = htmlspecialchars(
        $name,
        ENT_QUOTES,
        'UTF-8'
    );

    $html = "
        <h2>Pozdrav, {$safeName}!</h2>

        <p>
            Klikni na link ispod za promjenu lozinke:
        </p>

        <p>
            <a href=\"{$resetUrl}\">
                Resetiraj lozinku
            </a>
        </p>

        <p>
            Ako gumb ne radi, kopiraj ovaj link:
        </p>

        <p>{$resetUrl}</p>

        <p>Link vrijedi 1 sat.</p>
    ";

    $plain = "
Pozdrav, {$name}!

Reset lozinke ovdje:
{$resetUrl}

Link vrijedi 1 sat.
";

    return send_smtp_mail(
        $email,
        $name,
        $subject,
        $html,
        $plain
    );
}