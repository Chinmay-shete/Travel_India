<?php
 // session_start();
include("../config/connection.php");
error_reporting(0);
  
if (isset($_GET['id'])) {
    $sql = "select * from create_intern_package where CIPackage_Id = " .  $_GET['id'];
    $result = $conn->query($sql);
    $data = mysqli_fetch_assoc($result);
    if ($data) {
       // echo "data fetch succes";
    }
} else {
    echo "Invalid request..!";
    exit;
}
 
//echo $user = $_SESSION["email"];
 $user = $_SESSION["email"];

$query = mysqli_query($conn, "select * from users where email ='$user'");
$row = mysqli_fetch_array($query);
// echo $id = $row['user_Id'];

// echo $Package_Id = $_SESSION["CIPackage_Id"];

 $id = $row['user_Id'];

 $Package_Id = $_SESSION["CIPackage_Id"];

if (isset($_POST['submit'])) {


    $User_Name = $_POST['name'];
    $_SESSION["username"] = $User_Name;
    $email = $_POST['email'];
    $_SESSION["Email_ID"] = $email;

    $Phone = $_POST['Mobile_No'];
    $_SESSION["phone"] = $Phone;

    $Package_Date = $_POST['date'];
    $_SESSION["Package_Date"] = $Package_Date;

    $Package_Name = $_POST['package_name'];
    $_SESSION["Package_Name"] = $Package_Name;

    $Package_Price = $_POST['Price'];
    $_SESSION['rate'] = $Package_Price;

    $Package_Duration = $_POST['destination'];
    $_SESSION["Package_Duration"] = $Package_Duration;

    $Package_Type = $_POST['Package_Type'];
    $no_of_person = $_POST['no_of_person'];


    



    $sql = "insert into booking(user_Id,Package_Id,User_Name,Email_Id,Mobile_No,Tour_Date,Package_Name,Package_Price,Package_Duration,Package_Type,No_of_Person) values 
        ('$id','$Package_Id','$User_Name','$email','$Phone','$Package_Date','$Package_Name','$Package_Price','$Package_Duration',' $Package_Type','$no_of_person')";
    $result = $conn->query($sql);

    if ($result) {
       // Sendemail_approvel("$User_Name", "$email");
       // echo "<script>alert('Thank you for booking with us! You will get Approvel to you within 24-hours...!')</script>";

        header("Refresh:0.2; url=razorpay.php?price=$Package_Price");
    } else {
        echo "Invalid Query..!";
    }
 
}

 

$person = $_POST['no_of_person'];

$Package_Price = $_POST['Price'];

$Total_Price = $person * $Package_Price;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/admin/hotel.css">
</head>
 
<body>
    <div class="signUpPage">
        <div class="nav">
          <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
            <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
        </svg>
        <a href="../Lakshadweep/tourlist.php">to go Back</a></h3>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Confirm Your Payment</h3>
            <div class="signUpPage-bottom">
              <h1>Confirm <br> Your <br> Payment</h1>
            </div>
    
          </div>
          <div class="container"> 
            <form id="booking-form" action="" method="post"> 
              <label for="activity" class="required">full name</label>
              <input type="text" id="name" name="name" placeholder="Full Name" value="<?php echo $_POST['name'] ?>" readonly>
    
              <label for="activity" class="required">email</label>
              <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $_POST['email'] ?>" readonly>
    
              <label for="activity" class="required">number of person</label>
              <input type="number" name="no_of_person"   value="<?php echo $person ?>" readonly>
               
              <label for="activity" class="required">phone no</label>
              <input type="number" id="phone" name="Mobile_No" placeholder="phone-no" value="<?php echo $_POST['Mobile_No'] ?>" readonly>

              <label for="activity" class="required">date</label>
              <input type="date" id="date" name="date" value="<?php echo $_POST['date'] ?>" readonly>

              <label for="activity" class="required">package name</label>
              <input type="text"  id="name" name="package_name" placeholder="vaibhav tours" value="<?php echo $_POST['package_name'] ?>" readonly>
                  
              <label for="activity" class="required">Price</label>
              <input type="text"  id="number" name="Price" value="<?php echo $Total_Price ?>" readonly>
                 
              <label for="activity" class="required">destination</label>
              <input   id="name" name="destination" placeholder="destination" value="<?php echo $_POST['destination'] ?>" readonly>
                
              <label for="password" class="required">package type</label>
              <input   id="name" name="Package_Type" placeholder="Couple Package" value="<?php echo $data['Package_Type'] ?>" readonly>

              <button class="submitButton" type="submit" class="submit-btn" name="submit">Pay Now</button>
      
            </form>
 
          </div> 
        </div>
      </div>
</body>
</html>
