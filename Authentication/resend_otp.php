include("../config/connection.php");
include("../config/email_config.php");
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

function Sendemail_Verify($otp, $verify_email)
{
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug  = 0;
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_SECURE;
        $mail->Port       = MAIL_PORT;
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($verify_email);
        $mail->isHTML(true);
        $mail->Subject = '🔐 Your OTP Verification Code — The Real Travel';
        $mail->Body    = getOtpEmailTemplate('User', $otp);
        $mail->AltBody = "Your OTP is: $otp (valid for 1 minute)";
        $mail->send();
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
    }
}
?>

<?php
$otp_str = str_shuffle("0123456789");
$otp = substr($otp_str, 0, 6);
$_SESSION['otp'] = $otp;

$act_str = rand(100000, 1000000);
$activation_code = str_shuffle("abcdefghijklmno" . $act_str);
$_SESSION['activation_code'] = $activation_code;


if (isset($_POST['submit'])) {
    $otp = $_POST['otp'];
    $activation_code = $_POST['token'];
    $verify_email = $_POST['email'];
    $_SESSION['email_verify'] = $verify_email;

    $sql = "UPDATE users SET otp = ?, activation_code = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $otp, $activation_code, $verify_email);

        if ($stmt->execute()) {
            Sendemail_Verify($otp, $verify_email);
            echo "<script>alert('OTP sent Successfully..!'); window.location.href='otp_2.php?code=" . $activation_code . "';</script>";
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
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
              <a href="index.php">Back</a></h3>
            
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
                <?php include("../config/alert.php");  ?>
                <input type="hidden" name="otp" placeholder="Enter OTP" value="<?php echo $otp ?>" required />
                <input type="hidden" name="token" placeholder="Enter token" value="<?php echo $activation_code ?>" required />

              <label for="activity" class="required">Email</label>
              <input type="email" name="email" placeholder="Enter email address" required /> 
                      
              <button class="submitButton" type="submit" name="submit">Send OTP</button>
      
            </form>  
          </div> 
        </div>
      </div>
</body>
</html>