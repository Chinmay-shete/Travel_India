<?php
include("../config/connection.php");


if (isset($_GET['get_Id'])) {

   //fetch Email_ID from Login Session
    $fetch_email = $_SESSION["email"];
    $fetch_email;
    $user_Id = $_GET['get_Id'];

     $sql = "select * from users where email = '$fetch_email' ";
    // $sql = "select * from users where email = '$fetch_email' && user_Id = " . $_GET['get_Id'];
    $result = $conn->query($sql);
    $users = mysqli_fetch_assoc($result);

    //
    // $user_id = $_GET['get_Id'];
    // $Id_data = "select * from users where user_Id = " . $_GET['get_Id'];




    if (!$users) {
      echo "Invalid request..!";
      exit;
    }
  }







  if (isset($_POST['submit'])) {
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
  
    $sql = "UPDATE users SET dob='$dob', Mobile_No='$phone', Address='$address' where user_Id = " . $_GET['get_Id'];
    $result = $conn->query($sql);
  
    if ($result) {
      echo "<script>alert('Data saved Successfully..!')</script>";
      header("Refresh:0.1; url=../other/homepage.php");
    } else {
      echo "Not Updated..!";
    }
  }





// $sql = "select * from users ";
// $result = $conn->query($sql);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head> 
<body>
    <div class="signUpPage">
        <div class="nav">
          <div class="nav-part2">

             <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;"> <a href="../other/homepage.php">
              <svg id="arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                  <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
              </svg>
               Home</a></h3>
            </div>
              <div class="nav-part1">
             <h3>EST-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
        <div class="container"> 
            <form action="" method="post">
              <label for="password" class="required">first name</label>
              <input type="text" id="first-name" name="first-name" value="<?php echo $users['fname'] ?>" readonly>

              <label for="password" class="required">last name</label>
              <input type="text" id="last-name" name="last-name" value="<?php echo $users['lname']; ?>" readonly>

              <label for="password" class="required">date of birth</label>
              <input type="date" id="dob" name="dob" value="<?php echo $users['dob'] ?>" >

              <label for="password" class="required">email</label>
              <input type="email" id="email" name="email" value="<?php echo $users['email'] ?>" readonly>

              <label for="password" class="required">phone no</label>
              <input type="tel" id="phone" name="phone" placeholder="phoneNo" value="<?php echo $users['Mobile_No'] ?>">

              <label for="password" class="required">address</label>
              <textarea id="address" name="address" rows="3" placeholder="Address"  > <?php echo $users['Address'] ?>  </textarea>
              <!-- <input   id="name" name="Package_Type" placeholder="Couple Package" value="" readonly> -->
              <button class="submitButton" type="submit" name="submit">Save Changes</button>
            </form> 
      
          </div> 
        





          <?php
          $sql = "select * from users";
          $result = $conn->query($sql);
          $user = $_SESSION["email"];
          if (isset($_POST['password'])) {
              $current_pwd = $_POST['current_password'];
              $pwd = $_POST['new_password'];
              $cpwd = $_POST['confirm_password'];

              $sql = "select password from users where email='$user'";
              $res = mysqli_query($conn, $sql);
              $row = mysqli_fetch_assoc($res);
              if ($current_pwd == $row['password']) {
                  if ($cpwd == '') {

                      echo "<script>alert('Please enter the confirm password..!')</script>";
                  } else if ($pwd != $cpwd) {

                      echo "<script>alert('passwords do not match..!')</script>";
                  } else if ($pwd == $cpwd) {
                      $update = mysqli_query($conn, "update users set password = '$pwd' where email = '$user'");
                      if ($update) {
                          echo "<script>alert('Password changed Succesfully..!')</script>";
                      }
                  }
              } else {
                  echo "<script>alert('Correct Password does not match..!')</script>";
              }
          }
          ?>

          <div class="container">
            
            <form action="" method="post"> 
              <label for="password" class="required">current password</label>
              <input type="number" id="current-password" name="current_password" placeholder="current password" required>

              <label for="password" class="required">new password</label>
              <input type="password" id="new-password" name="new_password" placeholder="new password" required>

              <label for="password" class="required">confirm password</label>
              <input type="password" id="confirm-password" name="confirm_password" placeholder="confirm password" required>

              <button class="submitButton" type="submit" name="password">Change Password</button>
            </form>

          </div>
        </div>
      </div>
</body>
</html>
 