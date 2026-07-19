<?php
require_once "../../../config/user_guard.php";
include("../../../config/connection.php");

$tour_package_id = (int)($_GET['Id'] ?? 0);
$_SESSION["TourPackage_Id"] = $tour_package_id;

if ($tour_package_id <= 0) {
    echo "Invalid request..!";
    exit;
}

// Fetch tour package securely
$stmt = $conn->prepare("SELECT TourPackage_Id, Package_Name, Price, Package_Type FROM tour_package WHERE TourPackage_Id = ? LIMIT 1");
$stmt->bind_param("i", $tour_package_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Tour Package not found..!";
    exit;
}

$user_email = $_SESSION["email"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>
    <link rel="stylesheet" href="../../assets/css/admin/hotel.css">
    <style>
      .submitButton:hover{
       background-color: black;
       color: white;
      }
    </style>
</head>
 
<body>
    <div class="signUpPage">
        <div class="nav">
            <div class="nav-part2">
                <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                        <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
                    </svg>
                    <a href="../Lakshadweep/tourlist.php">to go Back</a></h3>
                </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>fill details</h3>
            <div class="signUpPage-bottom">
              <h1>Start <br> Your <br> Journey</h1>
            </div>
          </div>
          <div class="container"> 
            <form id="booking-form" action="../payment/../payment/pay_now.php?id=<?php echo h($data['TourPackage_Id']); ?>" method="post">
              <?php echo csrf_field(); ?>
              <label for="activity" class="required">full name</label>
              <input type="text" id="name" name="name" placeholder="Full Name" required>

              <label for="activity" class="required">email</label>
              <input type="email" id="email" name="email" placeholder="Email" value="<?php echo h($user_email); ?>" required>
    
              <label for="activity" class="required">no of Persons</label>
              <input type="number" name="no_of_person" placeholder="Enter Number of Persons..!" required min="1"> 
               
              <label for="activity" class="required">phone no</label>
              <input type="number" id="phone" name="Mobile_No" placeholder="Phone Number" required>

              <label for="activity" class="required">date</label>
              <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">

              <label for="activity" class="required">package name</label>
              <input id="name" name="package_name" placeholder="Vaibhav Tours" value="<?php echo h($data['Package_Name']); ?>" readonly>
                  
              <label for="activity" class="required">Price</label>
              <input id="number" name="Price" value="<?php echo h($data['Price']); ?>" readonly> 
                 
              <label for="activity" class="required">destination</label>
              <select class="submitButton2" id="destination" name="destination" required>
                <option value="">Select your Choice</option>
                <option value="2">2 Days, 1 Night</option>
                <option value="4">4 Days, 3 Night</option>
                <option value="6">6 Days, 5 Night</option>
              </select>
 
              <label for="activity" class="required">package type</label>
              <input id="name" name="Package_Type" placeholder="Couple Package" value="<?php echo h($data['Package_Type']); ?>" readonly>
                  
              <button class="submitButton" type="submit">Book Now</button>
            </form>
          </div> 
        </div>
      </div>
</body>
</html>