<?php  
include("../config/connection.php");

if(isset($_POST['Login'])){
    extract($_POST);
    $sql = "select * from users where email='$email' AND password='$password'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_array($result);
    
    if($row["user_type"]=="user" ) {
        if($row['status']== 1){
            header('location:homepage.php');
        } else {
            echo "<script>alert('Your account is not verified , Please check your Email..!')</script>";
        }
    } else if($row["user_type"]=="admin") {
        if($row['status']== 1){
            header('location:../admin/adminhomepage.php');
        } else {
            echo "<script>alert('Your account is not verified , Please check your Email..!')</script>";
        }
    } else {
        echo "<script>alert('Invalid Login info..!')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                    <a href="../index.php">Home</a>
                </h3>
            </div>
            <div class="nav-part1">
                <h3>EST-2024</h3>
            </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1">
            <div class="signUpPage-part11">
                <h3>Welcome Back</h3>
                <div class="signUpPage-bottom">
                    <h1>Login <br> Your <br> Account</h1>
                </div>
            </div>
            <div class="container">
                <form action="" method="POST">
                    <?php include("../config/alert.php"); ?>
                    <label for="email" class="required">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    
                    <label for="password" class="required">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    
                    <button type="submit" name="Login">Login</button>
                    <p class="signup-link">Don't have an account? <a href="signup.php">Sign Up</a></p>
                    <p class="forgot-link"><a href="forgot_password.php">Forgot Password?</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
