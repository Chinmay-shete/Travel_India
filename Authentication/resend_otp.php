<?php
include("../config/connection.php");
include("../config/email_config.php");
include("../config/email_queue.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function Sendemail_Verify($otp, $verify_email)
{
    global $conn;
    $subject = '🔐 Your OTP Verification Code — The Real Travel';
    $body    = getOtpEmailTemplate('User', $otp);

    // Try direct send first (fast path)
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
        $mail->addAddress($verify_email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = "Your OTP is: $otp (valid for 1 minute)";
        $mail->send();
        error_log("OTP email sent directly to $verify_email");
    } catch (Exception $e) {
        // Direct send failed — fall back to queue for retry by worker
        error_log('Direct mail failed in resend, queuing for retry: ' . $mail->ErrorInfo);
        enqueue_email($conn, $verify_email, $subject, $body);
    }
}

// Generate OTP securely
$otp = (string)random_int(100000, 999999);
$_SESSION['otp'] = $otp;

$activation_code = bin2hex(random_bytes(16));
$_SESSION['activation_code'] = $activation_code;

if (isset($_POST['submit'])) {
    $verify_email = trim($_POST['email'] ?? '');
    
    if (empty($verify_email) || !filter_var($verify_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.');</script>";
    } else {
        // Rate limiting check
        $limit_check = check_rate_limit($conn, 'resend_otp', 5, 900);
        if (!$limit_check['allowed']) {
            echo "<script>alert('Too many OTP requests. Locked out for " . ceil($limit_check['time_left'] / 60) . " minutes.');</script>";
        } else {
            // Verify if email exists
            $check_stmt = $conn->prepare("SELECT user_Id FROM users WHERE email = ? LIMIT 1");
            $check_stmt->bind_param("s", $verify_email);
            $check_stmt->execute();
            $check_res = $check_stmt->get_result();
            
            if ($check_res->num_rows > 0) {
                $check_stmt->close();
                $_SESSION['email_verify'] = $verify_email;

                // Update OTP, creation timestamp to verify expiration, and activation code
                $sql = "UPDATE users SET otp = ?, activation_code = ?, created_at = CURRENT_TIMESTAMP WHERE email = ?";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("sss", $otp, $activation_code, $verify_email);

                    if ($stmt->execute()) {
                        reset_rate_limit($conn, 'resend_otp');
                        Sendemail_Verify($otp, $verify_email);
                        echo "<script>alert('OTP sent Successfully..!'); window.location.href='otp_2.php?code=" . $activation_code . "';</script>";
                    } else {
                        echo "Error updating record: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }
            } else {
                $check_stmt->close();
                increment_rate_limit($conn, 'resend_otp');
                echo "<script>alert('Email address not found.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend OTP</title>
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
            <h3>resend otp</h3>
            <div class="signUpPage-bottom">
              <h1>Resend <br> OTP</h1>
            </div>
          </div>
          <div class="container"> 
            <form action="" method="post">
                <?php include("../config/alert.php"); ?>
                <?php echo csrf_field(); ?>
                <label for="activity" class="required">Email</label>
                <input type="email" name="email" placeholder="Enter email address" required /> 
                <button class="submitButton" type="submit" name="submit">Send OTP</button>
            </form>  
          </div> 
        </div>
      </div>
</body>
</html>