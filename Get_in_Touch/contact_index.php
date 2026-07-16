<?php
include('../config/connection.php');

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
            $mail->SMTPSecure = (MAIL_SECURE === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;            
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
            error_log("PHPMailer error in contact page: " . $mail->ErrorInfo);
            echo "<script>alert('Message could not be sent. Mailer Error occurred.')</script>";
        }
    } else {
        echo "Invalid Query..!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../css/otpNew.css">
</head> 
<body>
    <div class="signUpPage">
        <div class="nav">
          <div class="nav-part2">
            <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
              </svg>
              <a href="../index.php">Home</a></h3>
            </div>
              <div class="nav-part1">
             <h3>EST-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Connect with us</h3>
            <div class="signUpPage-bottom">
              <h1>Connect <br> With Us</h1>
            </div>
          </div>
          <div class="container"> 
            <form action="" method="post">  
              <?php echo csrf_field(); ?>
              <label for="activity" class="required">Name</label>
              <input type="text" id="name" name="name" placeholder="Enter your name" required>
    
              <label for="activity" class="required">email</label>
              <input type="email" id="email" name="email" placeholder="Enter your email" required>
                 
              <label for="password" class="required">Message</label>
              <textarea id="message" name="massage" placeholder="Write your message here..." required></textarea>
      
              <button type="submit" name="send">Send Message</button>
            </form>
          </div> 
        </div>
      </div>
</body>
</html>