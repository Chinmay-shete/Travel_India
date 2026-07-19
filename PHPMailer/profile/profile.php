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
    <title>New Hotel Creation Form</title>
    <link rel="stylesheet" href="../css/admin/package.css">
</head>

<body>
    <div class="main">
        <div class="page1">
            <div class="nav">
                <div class="nav-part1">
                    <h2>profile update</h2>
                </div>
                <h1>the real travel</h1>
                <div class="nav-part2">
                    <h3><a href="../other/homepage.php">Back</a></h3>

                </div>
            </div>
            <div class="package">
                <div class="form-section">
 
                    <h2>personal info</h2>
                    <form action="" method="post">
                        <div style="display: flex; gap: 1vw;">
                            <input type="text" id="first-name" name="first-name" value="<?php echo $users['fname'] ?>" readonly>
                            <input type="text" id="last-name" name="last-name" value="<?php echo $users['lname']; ?>" readonly>
                        </div>
                        <input type="date" id="dob" name="dob" value="<?php echo $users['dob'] ?>" >
                        <div style="display: flex; gap: 1vw;">
                            <input type="email" id="email" name="email" value="<?php echo $users['email'] ?>" readonly>
                            <input type="tel" id="phone" name="phone" placeholder="phoneNo" value="<?php echo $users['Mobile_No'] ?>">
                        </div>


                       Address <textarea id="address" name="address" rows="3" placeholder="Address"  > <?php echo $users['Address'] ?>  </textarea>
                        <!-- <input   id="name" name="Package_Type" placeholder="Couple Package" value="" readonly> -->
                        <button class="submitButton" type="submit" name="submit">Save Changes</button>
                    </form>
                </div>
                       
                      
                       
<!-- change the Password  ...!   -->

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



                <div class="form-section">
                    <h2>Change Password</h2>
                    <form action="" method="post">
                        <div style="display: flex; gap: 1vw;">
                            <input type="number" id="current-password" name="current_password" placeholder="current password" required>
                        </div>
                        <input type="password" id="new-password" name="new_password" placeholder="new password" required>
                        <div style="display: flex; gap: 1vw;">
                            <input type="password" id="confirm-password" name="confirm_password" placeholder="confirm password" required>
                        </div>
                        <button class="submitButton" type="submit" name="password">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
</body>

</html>