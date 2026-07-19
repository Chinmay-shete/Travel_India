<?php
include("../config/connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

function send_password_reset($get_email, $token){
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'harsh1234vathare@gmail.com';
        $mail->Password = 'olfq duvu rucq tvsv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->setFrom('travelindia9500@gmail.com', 'The Real_Travel.com');
        $email = $_POST['email'];
        $mail->addAddress($email);
        $mail->addReplyTo('travelindia9500@gmail.com', 'Information');
        $mail->isHTML(true);
        $mail->Subject = ' Reset Password Link..!';
        $mail->Body = "<h3>hello users </h3><h3> You are receiving this email because we received a password reset request for your account.</h3><br>
                <br/><br/>
                <a href='http://localhost:3000/Authentication/password_change.php?email=$get_email&verify_token=$token'>Reset Password..! </a>";

                // <a href='http://localhost:3000/travel_india-new-main/password_change.php?email=$get_email&verify_token=$token'>Reset Password..! </a>";
           
                 
        $res = $mail->send();
        if($res){
        }
        else {
            echo "<script>alert('Your Messages not Send..!')</script>";
        }
    } 
    catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if(isset($_POST['send_link'])){
    $email = mysqli_escape_string($conn, $_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $check_email);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        $get_email = $row['email'];

        $update_token = "UPDATE users SET activation_code = '$token' WHERE email = '$get_email' LIMIT 1";
        $update_token_run = mysqli_query($conn, $update_token);

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
        $reset_pwd = mysqli_query($conn, "UPDATE users SET password ='$pwd' WHERE email = '$email'");

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