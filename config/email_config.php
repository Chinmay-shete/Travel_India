<?php
require_once __DIR__ . '/env.php';

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

function configure_smtp_mailer($mail) {
  $mail->isSMTP();
  $mail->Host       = env('SMTP_HOST');
  $mail->SMTPAuth   = true;
  $mail->Username   = env('SMTP_USER');
  $mail->Password   = env('SMTP_PASS');
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port       = (int) env('SMTP_PORT');
  $mail->setFrom(env('SMTP_FROM'), 'The Real Travel');
}
?>
