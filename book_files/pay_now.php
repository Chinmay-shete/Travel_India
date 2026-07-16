<?php
require_once "../config/user_guard.php";
include("../config/connection.php");

$tour_id = (int)($_GET['id'] ?? 0);
if ($tour_id <= 0) {
    echo "Invalid request..!";
    exit;
}

// Fetch tour package securely
$stmt = $conn->prepare("SELECT Package_Type FROM tour_package WHERE TourPackage_Id = ? LIMIT 1");
$stmt->bind_param("i", $tour_id);
$stmt->execute();
$res_tour = $stmt->get_result();
$data = $res_tour->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Tour Package not found..!";
    exit;
}

$user = $_SESSION["email"];
$Package_Id = $_SESSION["TourPackage_Id"] ?? $tour_id;

// Fetch user details securely
$stmt_user = $conn->prepare("SELECT user_Id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
$row = $res_user->fetch_assoc();
$id = $row['user_Id'] ?? 0;
$stmt_user->close();

// Process POST values securely
$person = (int)($_POST['no_of_person'] ?? 0);
$raw_price = (int)($_POST['Price'] ?? 0);
$duration = (int)($_POST['destination'] ?? 0);
$Total_Price = $person * $raw_price * $duration;

if (isset($_POST['submit'])) {
    $User_Name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $Phone = trim($_POST['Mobile_No'] ?? '');
    $Package_Date = $_POST['date'] ?? '';
    $Package_Name = trim($_POST['package_name'] ?? '');
    $Package_Type = $data['Package_Type'];

    // Server-side validation
    if (empty($User_Name) || empty($email) || empty($Phone) || empty($Package_Date) || empty($Package_Name) || $person <= 0 || $duration <= 0) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } else {
        // Save to session for payment page
        $_SESSION["username"] = $User_Name;
        $_SESSION["Email_ID"] = $email;
        $_SESSION["phone"] = $Phone;
        $_SESSION["Package_Date"] = $Package_Date;
        $_SESSION["Package_Name"] = $Package_Name;
        $_SESSION['rate'] = $Total_Price;
        $_SESSION["Package_Duration"] = $duration . ' Days';

        // Insert booking with status 'pending' securely using prepared statement
        $stmt_insert = $conn->prepare("INSERT INTO booking (user_Id, Package_Id, User_Name, Email_Id, Mobile_No, Tour_Date, Package_Name, Package_Price, Package_Duration, Package_Type, No_of_Person, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $duration_str = $duration . ' Days';
        $stmt_insert->bind_param("iisssssissi", $id, $Package_Id, $User_Name, $email, $Phone, $Package_Date, $Package_Name, $Total_Price, $duration_str, $Package_Type, $person);
        
        if ($stmt_insert->execute()) {
            $stmt_insert->close();
            header("Location: ./payment/razorpay.php?price=" . urlencode($Total_Price));
            exit;
        } else {
            echo "Invalid Query..!";
            $stmt_insert->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pay Now</title>
  <link rel="stylesheet" href="../css/admin/hotel.css">
  <style>
     Button:hover {
       background-color: green;
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
            <h3>confirm details</h3>
            <div class="signUpPage-bottom">
              <h1>Confirm <br> Details</h1>
            </div>
          </div>
          <div class="container"> 
            <form id="booking-form" action="" method="post">
              <?php echo csrf_field(); ?>
              <label for="activity" class="required">full name</label>
              <input type="text" id="name" name="name" placeholder="Full Name" value="<?php echo h($_POST['name'] ?? ''); ?>" readonly>
              
              <label for="activity" class="required">email</label>
              <input type="email" id="email" name="email" placeholder="Email" value="<?php echo h($_POST['email'] ?? ''); ?>" readonly>
              
              <label for="activity" class="required">no of person</label>
              <input type="number" name="no_of_person" value="<?php echo h($person); ?>" readonly>
              
              <label for="activity" class="required">phone no</label> 
              <input type="number" id="phone" name="Mobile_No" placeholder="phone-no" value="<?php echo h($_POST['Mobile_No'] ?? ''); ?>" readonly>
              
              <label for="activity" class="required">date</label>
              <input type="date" id="date" name="date" value="<?php echo h($_POST['date'] ?? ''); ?>" readonly>
  
              <label for="activity" class="required">package name</label>
              <input type="text" id="name" name="package_name" placeholder="vaibhav tours" value="<?php echo h($_POST['package_name'] ?? ''); ?>" readonly>
               
              <label for="activity" class="required">Price</label>
              <input type="text" id="number" name="Price" value="<?php echo h($Total_Price); ?>" readonly>
               
              <label for="activity" class="required">destination</label>
              <input id="name" name="destination" placeholder="destination" value="<?php echo h($duration); ?>" readonly>
  
              <label for="activity" class="required">Package Type</label>
              <input id="name" name="Package_Type" placeholder="Couple Package" value="<?php echo h($data['Package_Type']); ?>" readonly>
  
              <button class="button-part1" type="submit" name="submit">Pay Now</button> 
            </form>
          </div>
        </div> 
    </div>
</body>
</html>