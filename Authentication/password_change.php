<?php
include("../config/connection.php");

$email = trim($_GET['email'] ?? $_POST['email'] ?? '');
$verify_token = trim($_GET['verify_token'] ?? $_POST['verify_token'] ?? '');

if (empty($email) || empty($verify_token)) {
    echo "<script>alert('Invalid or missing parameters.'); window.location.href='../index.php';</script>";
    exit;
}

if (isset($_POST['update_password'])) {
    $pwd = $_POST['new_password'] ?? '';
    $cpwd = $_POST['cpassword'] ?? '';

    // Verify token server-side in DB before updating
    $stmt = $conn->prepare("SELECT user_Id FROM users WHERE email = ? AND activation_code = ? LIMIT 1");
    $stmt->bind_param("ss", $email, $verify_token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $stmt->close();

        if (empty($pwd) || empty($cpwd)) {
            echo "<script>alert('All fields are required.');</script>";
        } elseif ($pwd === $cpwd) {
            if (strlen($pwd) < 6) {
                echo "<script>alert('Password must be at least 6 characters long.');</script>";
            } else {
                // Hash securely with Bcrypt
                $new_hash = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);

                // Update password and clear token
                $update_stmt = $conn->prepare("UPDATE users SET password = ?, activation_code = '' WHERE email = ? AND activation_code = ?");
                $update_stmt->bind_param("sss", $new_hash, $email, $verify_token);
                
                if ($update_stmt->execute() && $conn->affected_rows > 0) {
                    echo "<script>alert('New Password Successfully Updated..!'); window.location.href='../index.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Password update failed. The token may have expired.');</script>";
                }
                $update_stmt->close();
            }
        } else {
            echo "<script>alert('Password and Confirm Password do not match..!')</script>";
        }
    } else {
        $stmt->close();
        echo "<script>alert('Invalid password reset token or expired session.'); window.location.href='../index.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change</title>
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
             <h3>EST-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>password change</h3>
            <div class="signUpPage-bottom">
              <h1>Password <br> Change</h1>
            </div>
          </div>
          <div class="container"> 
            <form action="" method="POST">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="email" value="<?php echo h($email); ?>">
              <input type="hidden" name="verify_token" value="<?php echo h($verify_token); ?>">
    
              <label for="activity" class="required">Email</label>
              <input type="email" placeholder="enter your current email" value="<?php echo h($email); ?>" disabled>
    
              <label for="activity" class="required">New Password</label>
              <input type="password" name="new_password" placeholder="Enter New Password" required>
               
              <label for="activity" class="required">Confirm Password</label>
              <input type="password" name="cpassword" placeholder="Enter Confirm Password" required> 
                      
              <button class="submitButton" type="submit" name="update_password">Update Password</button> 
            </form>
          </div> 
        </div>
      </div>
</body>
</html>