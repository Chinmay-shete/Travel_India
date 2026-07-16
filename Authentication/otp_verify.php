<?php 
include("../config/connection.php");

// Set timezone for OTP expiry checking
date_default_timezone_set("Asia/Karachi");

if (isset($_POST['verify'])) {
    $activation_code = trim($_GET['code'] ?? '');
    $otp = trim($_POST['otp'] ?? '');

    if (empty($activation_code) || empty($otp)) {
        echo "<script>alert('Invalid parameters.'); window.location.href='../index.php';</script>";
        exit;
    }

    // Rate limiting check to prevent OTP brute-forcing
    $limit_check = check_rate_limit($conn, 'otp_verify', 5, 900);
    if (!$limit_check['allowed']) {
        echo "<script>alert('Too many verification attempts. Locked out for " . ceil($limit_check['time_left'] / 60) . " minutes.'); window.location.href='../index.php';</script>";
        exit;
    }

    // Prepared Statement to retrieve user details
    $stmt = $conn->prepare("SELECT user_Id, otp, created_at FROM users WHERE activation_code = ? LIMIT 1");
    $stmt->bind_param("s", $activation_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row_otp = $row['otp'];
        $row_signup_time = $row['created_at'];

        // OTP expiration check (1 minute validity)
        $signup_timestamp = strtotime($row_signup_time);
        $time_expiry = $signup_timestamp + 60; // 1 minute

        if ($row_otp !== $otp) {
            increment_rate_limit($conn, 'otp_verify');
            echo "<script>alert('Please provide correct OTP..!')</script>";
        } else {
            if (time() >= $time_expiry) {
                echo "<script>alert('Your verification time has expired. Please request a new OTP.'); window.location.href='resend_otp.php';</script>";
            } else {
                // Successful verification: activate account and reset rate limit
                reset_rate_limit($conn, 'otp_verify');
                
                $stmt_update = $conn->prepare("UPDATE users SET otp = '', status = 'active' WHERE otp = ? AND activation_code = ?");
                $stmt_update->bind_param("ss", $otp, $activation_code);
                
                if ($stmt_update->execute()) {
                    echo "<script>alert('Congratulations! Your account has been successfully activated.'); window.location.href='../index.php';</script>";
                } else {
                    echo "<script>alert('Activation failed. Please try again.');</script>";
                }
                $stmt_update->close();
            }
        }
    } else {
        header("location:../index.php");
        exit;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title> 
    <link rel="stylesheet" href="../css/otpNew.css">
</head>

<body>
<div class="signUpPage">
    <div class="nav">
        <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
            <svg id="arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
            </svg>
            <a href="../index.php" style="color: white;">Home</a>
        </h3>
        <div class="nav-part1">
            <h3>EST-2024</h3>
        </div>
    </div>
    <hr class="animated-hr" />
    <div class="signUpPage-part1"> 
        <div class="signUpPage-part11">
            <h3>otp verification</h3>
            <div class="signUpPage-bottom">
                <h1>OTP <br> Verification</h1>
            </div>
        </div>
        <div class="container"> 
            <form action="" method="POST">  
                <?php echo csrf_field(); ?>
                <label for="activity" class="required">Enter OTP</label>
                <input type="number" name="otp" placeholder="Enter OTP" required> 
                <button class="submitButton" id="verify" type="submit" name="verify">Verify</button>
            </form>  
        </div> 
    </div>
</div>
</body>

</html>