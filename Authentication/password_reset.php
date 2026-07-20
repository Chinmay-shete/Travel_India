<?php
include("../config/connection.php");
require_once("../config/email_config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_password_reset($get_email, $token){
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        configure_smtp_mailer($mail);

        $email = !empty($get_email) ? $get_email : ($_POST['email'] ?? '');
        $mail->addAddress($email);
        $mail->addReplyTo(SMTP_FROM_EMAIL, 'Support');
        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password — The Real Travel';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                <h2>Password Reset Request</h2>
                <p>You are receiving this email because we received a password reset request for your account.</p>
                <p style='margin: 20px 0;'>
                    <a href='http://localhost:8080/Authentication/password_change.php?email=" . urlencode($get_email) . "&verify_token=" . urlencode($token) . "' style='background-color: #10B981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;'>Reset Password</a>
                </p>
                <p>If you did not request a password reset, no further action is required.</p>
            </div>";
           
        $res = $mail->send();
        if(!$res){
            echo "<script>alert('Password reset email could not be sent.');</script>";
        }
    } catch (Exception $e) {
        error_log("Brevo Mailer Error: " . $mail->ErrorInfo);
    }
}

if(isset($_POST['send_link'])){
    $email = $_POST['email'];
    $token = md5(rand());

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        $get_email = $row['email'];

        $stmt2 = $conn->prepare("UPDATE users SET activation_code = ? WHERE email = ? LIMIT 1");
        $stmt2->bind_param("ss", $token, $get_email);
        $update_token_run = $stmt2->execute();
        $stmt2->close();

        if($update_token_run){
            send_password_reset($get_email, $token);
            echo "<script>alert('Email Send successfully, Please check your Email_Id..!')</script>";
            header("Refresh:1; url=../index.php");
        }
        else {
            echo "<script>alert('Something went wrong..!')</script>";
        }
    }
    else {
        echo "<script>alert('No Email Found..!')</script>";
        header("Refresh:1; url=password_reset.php");
    }
}

if(isset($_POST['update_password'])){
    $email = $_REQUEST['email'];
    $pwd = $_REQUEST['new_password'];
    $cpwd = $_REQUEST['cpassword'];

    if($pwd == $cpwd){
        $stmt3 = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt3->bind_param("ss", $pwd, $email);
        $reset_pwd = $stmt3->execute();
        $stmt3->close();

        if($reset_pwd > 0){
            echo "<script>alert('New Password Successfully Updated..!')</script>";  
            header("Refresh:1; url=../index.php");
        }
        else {
            echo "<script>alert('Password Not Updated..!')</script>"; 
        }
    }
    else {
        echo "<script>alert('Password and Confirm Password does not match..!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="../css/otpNew.css">
</head>
 
<body>
    <div class="signUpPage">
        <div class="nav">
          <div class="nav-part2">

          <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
          <svg id="arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                  <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
              </svg>
              <a href="../index.php">Back</a></h3>
            
          </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>password reset</h3>
            <div class="signUpPage-bottom">
              <h1>Password <br> Reset</h1>
            </div>
    
          </div>
          <div class="container"> 
            <form action="" method="POST">
                <?php include("../config/alert.php");  ?> 
              
              <label for="activity" class="required">Email</label>
              <input type="email" name="email"  placeholder="Enter your email address " required />
       
              <button class="submitButton" type="submit" name="send_link" value="Send Password Reset Link">Send Password Reset Link</button>
      
            </form> 
            
          </div> 
        </div>
      </div>
</body>
</html>