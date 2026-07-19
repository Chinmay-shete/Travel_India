<?php
// Centralized Brevo (Sendinblue) SMTP Email Configuration
if (file_exists(__DIR__ . '/credentials.php')) {
    require_once __DIR__ . '/credentials.php';
}

if (!defined('SMTP_HOST')) define('SMTP_HOST', 'smtp-relay.brevo.com');
if (!defined('SMTP_PORT')) define('SMTP_PORT', 587);
if (!defined('SMTP_USER')) define('SMTP_USER', getenv('SMTP_USER') ?: 'ad84f9001@smtp-brevo.com');
if (!defined('SMTP_PASS')) define('SMTP_PASS', getenv('SMTP_PASS') ?: 'YOUR_BREVO_SMTP_KEY');
if (!defined('SMTP_FROM_EMAIL')) define('SMTP_FROM_EMAIL', 'chinmayshete4@gmail.com');
if (!defined('SMTP_FROM_NAME')) define('SMTP_FROM_NAME', 'The Real Travel');

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
        require_once BASE_PATH . '/vendor/autoload.php';
    } else {
        require_once BASE_PATH . '/PHPMailer/src/Exception.php';
        require_once BASE_PATH . '/PHPMailer/src/PHPMailer.php';
        require_once BASE_PATH . '/PHPMailer/src/SMTP.php';
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Configure PHPMailer instance with Brevo SMTP credentials
 * 
 * @param PHPMailer $mail
 */
function configure_smtp_mailer($mail) {
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
}
