<?php
include("../config/connection.php");


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
              <a href="#">Back</a></h3>
            
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
              <!-- <label for="activity" class="required">package Name</label> -->
              <input type="hidden" name="activation_code" value="<?php if (isset($_GET['activation_code'])) {echo $_GET['activation_code']; } ?>" required>
    
              <label for="activity" class="required">Email</label>
              <input type="email" name="email"  placeholder="enter your current email"  value="<?php if (isset($_GET['email'])) {  echo $_GET['email'];  } ?>" required>
    
              <label for="activity" class="required">New Password</label>
              <input type="password" name="new_password" placeholder="Enter New Password" required>
               
              <label for="activity" class="required">Confirm Password</label>
              <input type="password" type="password"  name="cpassword" placeholder="Enter Confirm Password" required> 
                      
              <button class="submitButton" type="submit" name="update_password">Update Password</button> 
      
            </form>

            
  
          </div> 
        </div>
      </div>
</body>
</html>