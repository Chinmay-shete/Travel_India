<?php
// Set execution timeout to prevent overlap
set_time_limit(55);

$connectionFile = dirname(__DIR__) . '/config/connection.php';
if (!file_exists($connectionFile)) {
    die("Connection file not found.");
}
require_once $connectionFile;
require_once __DIR__ . '/../config/email_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "[" . date('Y-m-d H:i:s') . "] Starting email worker...\n";

// Fetch pending emails
$stmt = $conn->prepare("SELECT id, to_email, subject, body, attempts FROM email_queue WHERE status = 'pending' AND attempts < 3 LIMIT 10");
$stmt->execute();
$result = $stmt->get_result();
$emails = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($emails)) {
    echo "No pending emails found. Exiting.\n";
    exit(0);
}

foreach ($emails as $emailData) {
    $id = $emailData['id'];
    $to = $emailData['to_email'];
    $subject = $emailData['subject'];
    $body = $emailData['body'];
    $attempts = $emailData['attempts'] + 1;

    echo "Processing email ID $id (Attempt $attempts) to $to...\n";

    // Mark as sending to prevent duplicate worker pickup
    $update_status = $conn->prepare("UPDATE email_queue SET status = 'sending', attempts = ? WHERE id = ?");
    $update_status->bind_param("ii", $attempts, $id);
    $update_status->execute();
    $update_status->close();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = (MAIL_SECURE === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        $mail->send();

        // Mark as sent on success
        $update_sent = $conn->prepare("UPDATE email_queue SET status = 'sent' WHERE id = ?");
        $update_sent->bind_param("i", $id);
        $update_sent->execute();
        $update_sent->close();
        echo "Email ID $id sent successfully!\n";
    } catch (Exception $e) {
        $error_msg = $mail->ErrorInfo ?: $e->getMessage();
        echo "Failed to send email ID $id: $error_msg\n";

        // Determine final status
        $final_status = ($attempts >= 3) ? 'failed' : 'pending';

        $update_fail = $conn->prepare("UPDATE email_queue SET status = ?, last_error = ? WHERE id = ?");
        $update_fail->bind_param("ssi", $final_status, $error_msg, $id);
        $update_fail->execute();
        $update_fail->close();
    }
}

echo "[" . date('Y-m-d H:i:s') . "] Email worker execution completed.\n";
?>
