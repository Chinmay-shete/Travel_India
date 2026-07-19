<?php
include("../config/connection.php");
error_reporting(0);


// echo $TourPackage = $_GET['Id'];
 $TourPackage = $_GET['Id'];
$_SESSION["CIPackage_Id"] = $TourPackage;

if (isset($_GET['Id'])) {
    $sql = "select * from create_intern_package where CIPackage_Id = " .  $_GET['Id'];
    $result = $conn->query($sql);
    $data = mysqli_fetch_assoc($result);
    if ($data) {
        // echo "data fetch succes";
    }
} else {
    echo "Invalid request..!";
    exit;
}

 
$user = $_SESSION["email"];


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
            <path  fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
        </svg>
        <a href="../other/homepage.php">to go Home</a></h3>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Booking details</h3>
            <div class="signUpPage-bottom">
              <h1>Book <br> Your <br> Journey</h1>
            </div>
    
          </div>
          <div class="container"> 
            <form id="booking-form" action="pay_now.php?id=<?php echo $data['CIPackage_Id'] ?>" method="post">  
              <label for="activity" class="required">full name</label>
              <input type="text" id="name" name="name" placeholder="Full Name" required>
    
              <label for="activity" class="required">email</label>
              <input type="email" id="email" name="email" placeholder="Email" required>
              

              <label for="activity" class="required">number of person</label>
              <input type="number" name="no_of_person" placeholder="Enter Number of Persons..!">

              <label for="activity" class="required">phone no</label>
              <input type="number" id="phone" name="Mobile_No" placeholder="Phone Number" required>

              <label for="activity" class="required">date</label>
              <input  type="date" id="date" name="date" required>
                 
              <label for="activity" class="required">package name</label>
              <input id="name" name="package_name" value="<?php echo $data['Package_Name'] ?>" readonly>
                 
              <label for="activity" class="required">Price</label>
              <input id="number" name="Price" value="<?php echo $data['Price'] ?>" readonly>
                 
              <label for="activity" class="required">select</label>
              <select class="submitButton2" id="destination" name="destination" required>
                <option value="">Select your Choise</option>
                <option value="2 Days, 1 Night">2 Days, 1 Night</option>
                <option value="4 Days, 3 Night">4 Days, 3 Night</option>
                <option value="6 Days, 5 Night">6 Days, 5 Night</option>
               </select>
                 
              <label for="password" class="required">Package Type</label>
              <input id="name" name="Package_Type" placeholder="Couple Package" value="<?php echo $data['Package_Type'] ?>" readonly>
      
              <button class="submitButton" type="submit" class="submit-btn" name="">Book Now</button>
      
            </form>

            
            <!--   -->
          </div> 
        </div>
      </div>
</body>
</html>