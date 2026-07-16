<?php
require_once "../config/user_auth_acces.php";
include("../config/connection.php");

if (isset($_POST['submit'])) {
    $Hotel_Name = trim($_POST['Hotel_Name'] ?? '');
    $Hotel_Address = trim($_POST['Hotel_Address'] ?? '');
    $phoneno = trim($_POST['phoneno'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $NumberOfRooms = (int)($_POST['NumberOfRooms'] ?? 0);
    $PriceOfRoom = (int)($_POST['PriceOfRoom'] ?? 0);
    $amenities = trim($_POST['amenities'] ?? '');

    // Server-side validation
    if (empty($Hotel_Name) || empty($Hotel_Address) || empty($phoneno) || empty($email) || empty($amenities) || $NumberOfRooms <= 0 || $PriceOfRoom <= 0) {
        echo "<script>alert('All fields are required and numeric values must be positive.');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } elseif (!isset($_FILES['Hotel_Image']) || $_FILES['Hotel_Image']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Please upload a valid hotel image.');</script>";
    } else {
        // Harden file upload
        $tempname = $_FILES['Hotel_Image']['tmp_name'];
        $original_name = $_FILES['Hotel_Image']['name'];
        
        // Whitelist extensions
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        
        if (!in_array($ext, $allowedExt)) {
            echo "<script>alert('Invalid file extension. Only JPG, PNG, WEBP, and GIF are allowed.');</script>";
        } else {
            // Verify MIME Type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tempname);
            finfo_close($finfo);

            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mimeType, $allowedMimeTypes)) {
                echo "<script>alert('Invalid MIME type. Only images are allowed.');</script>";
            } else {
                // Generate secure random filename
                $new_filename = bin2hex(random_bytes(16)) . '.' . $ext;
                $target_dir = dirname(__DIR__) . '/uploads/';
                
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                
                $target_file = $target_dir . $new_filename;
                
                // Move uploaded file securely
                if (move_uploaded_file($tempname, $target_file)) {
                    // Save relative path using secure uploads folder
                    $db_folder_path = '../uploads/' . $new_filename;
                    
                    // Prepared statement
                    $stmt = $conn->prepare("INSERT INTO create_hotel (Hotel_Name, Hotel_Address, PhoneNo, email, NumberOfRooms, PriceOfRoom, amenities, Hotel_Image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssiiss", $Hotel_Name, $Hotel_Address, $phoneno, $email, $NumberOfRooms, $PriceOfRoom, $amenities, $db_folder_path);
                    
                    if ($stmt->execute()) {
                        echo "<script>alert('New Hotel Info Added Successfully..!')</script>";
                        header("Refresh:0.5; url=hotellist.php");
                        exit;
                    } else {
                        echo "Invalid Query..!";
                    }
                    $stmt->close();
                } else {
                    echo "<script>alert('Failed to upload image.');</script>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hotel</title>
    <link rel="stylesheet" href="../css/admin/hotel.css">
</head>
 
<body>
    <div class="signUpPage">
        <div class="nav">
            <div class="nav-part2">
                <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
                <svg id="arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                  <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
                </svg>
                    <a href="adminhomepage.php">To go Back</a></h3>
                    <h3><a href="./hotellist.php">Hotel List</a></h3>
                </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Build Hotel</h3>
            <div class="signUpPage-bottom">
              <h1>Start <br> Your <br> Journey</h1>
            </div>
          </div>
          <div class="container"> 
            <form action="" method="POST" enctype="multipart/form-data">
              <?php echo csrf_field(); ?>
              <label for="activity" class="required">Hotel Name</label>
              <input type="text" id="hotelName" name="Hotel_Name" placeholder="Hotel Name" required>
              
              <label for="activity" class="required">Hotel Address</label>
              <input type="text" id="address" name="Hotel_Address" placeholder="Hotel Address" required>
    
              <label for="activity" class="required">Amenities</label>
              <textarea id="amenities" name="amenities" placeholder="Amenities" required></textarea>
               
              <label for="activity" class="required">Phone No</label>
              <input type="tel" id="phoneNumber" name="phoneno" placeholder="Phone Number" required>
 
              <label for="activity" class="required">Email</label>
              <input type="email" id="email" name="email" placeholder="Enter Email" required>
 
              <label for="activity" class="required">Number of Rooms</label>
              <input type="number" id="numberOfRooms" name="NumberOfRooms" placeholder="Number of Rooms" required>
                  
              <label for="activity" class="required">Price</label>
              <input type="number" id="PriceOfRoom" name="PriceOfRoom" placeholder="Price" required>
                 
              <label for="activity" class="required">Hotel Image</label>
              <input type="file" id="hotelImage" name="Hotel_Image" accept="image/*" placeholder="Hotel Image" required>
                  
              <button class="submitButton" type="submit" name="submit">Create Hotel</button>
            </form>
          </div> 
        </div>
      </div>
</body>
</html>