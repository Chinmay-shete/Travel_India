<?php
include("../config/connection.php");
error_reporting(0);

if (isset($_GET['id'])) {
  $sql = "select * from users where user_Id = " . $_GET['id'];
  $result = $conn->query($sql);
  $users = mysqli_fetch_assoc($result);
  if (!$users) {
    echo "Invalid request..!";
    exit;
  }
}

if (isset($_POST['submit'])) {
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];

  $sql = "UPDATE users SET fname='$fname', lname='$lname', email='$email' where user_Id = " . $_GET['id'];
  $result = $conn->query($sql);

  if ($result) {
    echo "<script>alert('Data Updated Successfully..!')</script>";
    header("Refresh:0.5; url=user_data.php");
  } else {
    echo "Not Updated..!";
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
                    <a href="user_data.php">To go Back</a></h3>
                    
                </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Edit User</h3>
            <div class="signUpPage-bottom">
              <h1>Start <br> Your <br> Journey</h1>
            </div>
    
          </div>
          <div class="container"> 
            
              
            <form action=" " method="post">
                <label for="activity" class="required">First Name</label>
                <input type="text" name="fname" placeholder="FirstName " required value="<?php echo $users['fname'] ?>" />
                
                <label for="activity" class="required">Last Name</label>
                <input type="text" name="lname" placeholder="LastName " required value="<?php echo $users['lname'] ?>" />
                
                <label for="activity" class="required">Email</label>
                <input type="email" name="email" placeholder="Email " required value="<?php echo $users['email'] ?>" />
                  
                <button  class="submitButton" type="submit" name="submit">Update</button> 
            </form>
                    
 
          </div> 
        </div>
      </div>
</body>
</html>


 
