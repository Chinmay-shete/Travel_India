<?php
include("../config/connection.php");
include("../config/email_config.php");
include("../config/email_queue.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_password_reset($get_email, $token){
    global $conn;
    $reset_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST']
        . '/auth/password_change.php?email=' . urlencode($get_email) . '&verify_token=' . $token;

    $subject = '🔒 Password Reset Request — The Real Travel';
    $body    = getPasswordResetTemplate($reset_link);

    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug  = 0;
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = (MAIL_SECURE === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($get_email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = "Reset your password: $reset_link";
        $mail->send();
        error_log("Password reset email sent directly to $get_email");
    } catch (Exception $e) {
        // Direct send failed — fall back to queue for retry by worker
        error_log('Direct mail failed in reset, queuing for retry: ' . $mail->ErrorInfo);
        enqueue_email($conn, $get_email, $subject, $body);
    }
}

if(isset($_POST['send_link'])){
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.');</script>";
    } else {
        // Rate limiting check
        $limit_check = check_rate_limit($conn, 'password_reset_request', 5, 900);
        if (!$limit_check['allowed']) {
            echo "<script>alert('Too many password reset requests. Locked out for " . ceil($limit_check['time_left'] / 60) . " minutes.');</script>";
        } else {
            // Check if email exists
            $stmt = $conn->prepare("SELECT email FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                reset_rate_limit($conn, 'password_reset_request');
                
                $row = $result->fetch_assoc();
                $get_email = $row['email'];
                
                // Secure random token
                $token = bin2hex(random_bytes(32));

                // Save token in DB as activation_code
                $update_stmt = $conn->prepare("UPDATE users SET activation_code = ? WHERE email = ? LIMIT 1");
                $update_stmt->bind_param("ss", $token, $get_email);
                
                if($update_stmt->execute()){
                    send_password_reset($get_email, $token);
                    echo "<script>alert('Email sent successfully. Please check your Inbox.'); window.location.href='../index.php';</script>";
                } else {
                    echo "<script>alert('Something went wrong..!')</script>";
                }
                $update_stmt->close();
            } else {
                increment_rate_limit($conn, 'password_reset_request');
                echo "<script>alert('No Email Found..!'); window.location.href='password_reset.php';</script>";
            }
            $stmt->close();
        }
    }
}

if(isset($_POST['update_password'])){
    $email = trim($_POST['email'] ?? '');
    $pwd = $_POST['new_password'] ?? '';
    $cpwd = $_POST['cpassword'] ?? '';

    if (empty($email) || empty($pwd) || empty($cpwd)) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif($pwd === $cpwd){
        if (strlen($pwd) < 6) {
            echo "<script>alert('Password must be at least 6 characters long.');</script>";
        } else {
            // Hash password securely with Bcrypt
            $new_password_hash = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);
            
            // Prepared statement to reset password
            $stmt = $conn->prepare("UPDATE users SET password = ?, activation_code = '' WHERE email = ?");
            $stmt->bind_param("ss", $new_password_hash, $email);
            
            if($stmt->execute()){
                echo "<script>alert('New Password Successfully Updated..!'); window.location.href='../index.php';</script>";
            } else {
                echo "<script>alert('Password Not Updated..!')</script>"; 
            }
            $stmt->close();
        }
    } else {
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
    <link rel="stylesheet" href="../assets/css/otpNew.css">
</head>
 
<body>
    <div class="signUpPage">
        <div class="nav">
          <div class="nav-part2">
              <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
                  <svg id="arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                      <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
                  </svg>
                  <a href="../index.php">Back</a>
              </h3>
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
                <?php include("../config/alert.php"); ?> 
                <?php echo csrf_field(); ?>
                <label for="activity" class="required">Email</label>
                <input type="email" name="email" placeholder="Enter your email address" required />
                <button class="submitButton" type="submit" name="send_link" value="Send Password Reset Link">Send Password Reset Link</button>
            </form> 
          </div> 
        </div>
      </div>
</body>
</html>