<?php
include('connection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['send'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $massage = trim($_POST['massage'] ?? '');

    // Server-side validation
    if (empty($name) || empty($email) || empty($massage)) {
        die("All fields are required.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }
    if (strlen($name) > 100 || strlen($email) > 100 || strlen($massage) > 5000) {
        die("Input exceeds allowed length.");
    }

    // Prepared Statement
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, massage) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $massage);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->SMTPDebug = 0;                      
            $mail->isSMTP();                                           
            $mail->Host       = MAIL_HOST;                    
            $mail->SMTPAuth   = true;                                    
            $mail->Username   = MAIL_USERNAME;                     
            $mail->Password   = MAIL_PASSWORD;                               
            $mail->SMTPSecure = (MAIL_SECURE === 'ssl' || MAIL_SECURE === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;            
            $mail->Port       = MAIL_PORT;                                    

            // Recipients
            $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
            $mail->addAddress('tourism@mailinator.com');
            $mail->addAddress('travelindia9500@gmail.com');
            $mail->addReplyTo($email, $name);
            
            $mail->isHTML(true);
            $mail->Subject = 'Feedback From ' . h($name) . '..!';
            $mail->Body    = '<h3>Hello Travel_India Team,</h3>
                              <p><b>You got a new message from ' . h($name) . ',</b></p>
                              <p><b>Their Email ID:</b> ' . h($email) . '</p> 
                              <p><b>Message:</b><br>' . nl2br(h($massage)) . '</p>
                              <p><h3>Best wishes,</h3><b>Travel_India Team</b></p>';
            
            if ($mail->send()) {
                echo "<script>alert('Your Message was successfully sent..!')</script>";
            } else {
                echo "<script>alert('Your Message was not sent..!')</script>";
            }
        } catch (Exception $e) {
            error_log("PHPMailer error in feedback: " . $mail->ErrorInfo);
            echo "<script>alert('Message could not be sent. Mailer Error occurred.')</script>";
        }
    } else {
        echo "Invalid Query..!";
    }
}
?>