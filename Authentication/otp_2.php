<?php 
 include("../config/connection.php");
 date_default_timezone_set("Asia/Karachi");

 if(isset($_POST['verify'])){
     if(isset($_GET['code'])){
         $activation_code = $_GET['code'];
         $otp = $_POST['otp'];

         $stmt = $conn->prepare("SELECT * FROM users WHERE activation_code = ?");
         $stmt->bind_param("s", $activation_code);
         $stmt->execute();
         $result = $stmt->get_result();
         $stmt->close();

         if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            $email = $row['email'];
            $submitted_otp = $_POST['otp'];

            // 1. Check expiry
            if (strtotime($row['otp_expires_at']) < time()) {
                die("OTP has expired. Please request a new one.");
            }

            // 2. Check attempt count
            if ($row['otp_attempts'] >= 5) {
                die("Too many attempts. Please request a new OTP.");
            }

            // 3. Increment attempt counter on wrong OTP
            if ($submitted_otp !== $row['otp']) {
                $stmt = $conn->prepare(
                    "UPDATE users SET otp_attempts = otp_attempts + 1 WHERE email = ?"
                );
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->close();
                die("Incorrect OTP.");
            }

            // 4. On success — clear OTP so it can't be reused
            $stmt = $conn->prepare(
                "UPDATE users SET otp = NULL, otp_expires_at = NULL, otp_attempts = 0, status = '1' WHERE email = ?"
            );
            $stmt->bind_param("s", $email);
            $result_update = $stmt->execute();
            $stmt->close();

            if($result_update){
                echo "<script>alert('Congratulation..! Your account suuccesfully Activated..!')</script>";
                header("Refresh:1; url=../index.php");
            }else{
                echo "<script>alert('Oops..! Your account not Activated..!')</script>";
            }
         }

         }
         else{
            header("location:../index.php");
         }
     }
//  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>otp verification</title> 
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
              <a href="../index.php"> Back</a></h3> 
          </div>
          <div class="nav-part1">
             <h3>EST-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>otp verification</h3>
            <div class="signUpPage-bottom">
              <h1>otp <br> verification</h1>
            </div>
    
          </div>
          <div class="container"> 
            <form action="" method="POST" enctype="multipart/form-data">
              <label for="activity" class="required">Enter OTP</label>
              <input type="number" name="otp" placeholder="Enter OTP" required> 
    
              <button class="submitButton" id="verify" type="submit" name="verify">Verify</button>
              
      
            </form> 
          </div> 
        </div>
      </div>
</body>
</html>