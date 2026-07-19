<?php
require_once "../config/user_auth_acces.php";
include("../config/connection.php");

$hotel_id = (int)($_GET['id'] ?? 0);

if ($hotel_id <= 0) {
    echo "Invalid request..!";
    exit;
}

// Fetch hotel securely
$stmt = $conn->prepare("SELECT Hotel_Name, Hotel_Address, PhoneNo, email, NumberOfRooms, PriceOfRoom, amenities, Hotel_Image FROM create_hotel WHERE Hotel_Id = ? LIMIT 1");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Hotel not found..!";
    exit;
}

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
    } else {
        $folder = $data['Hotel_Image']; // Default to old image if no new file uploaded
        
        // Handle new file upload if provided
        if (isset($_FILES['Hotel_Image']) && $_FILES['Hotel_Image']['error'] === UPLOAD_ERR_OK) {
            $tempname = $_FILES['Hotel_Image']['tmp_name'];
            $original_name = $_FILES['Hotel_Image']['name'];
            
            $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            if (in_array($ext, $allowedExt)) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $tempname);
                finfo_close($finfo);

                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (in_array($mimeType, $allowedMimeTypes)) {
                    $new_filename = bin2hex(random_bytes(16)) . '.' . $ext;
                    $target_dir = dirname(__DIR__) . '/uploads/';
                    
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }
                    
                    if (move_uploaded_file($tempname, $target_dir . $new_filename)) {
                        $folder = '../uploads/' . $new_filename;
                    }
                }
            }
        }

        // Update database with prepared statement
        $update_stmt = $conn->prepare("UPDATE create_hotel SET Hotel_Name = ?, Hotel_Address = ?, PhoneNo = ?, email = ?, NumberOfRooms = ?, PriceOfRoom = ?, amenities = ?, Hotel_Image = ? WHERE Hotel_Id = ?");
        $update_stmt->bind_param("ssssiissi", $Hotel_Name, $Hotel_Address, $phoneno, $email, $NumberOfRooms, $PriceOfRoom, $amenities, $folder, $hotel_id);
        
        if ($update_stmt->execute()) {
            echo "<script>alert('Data updated Successfully..!')</script>";
            header("Refresh:0.5; url=hotellist.php");
        } else {
            echo "Not Updated..!";
        }
        $update_stmt->close();
    }
}     
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Hotel</title>
    <link rel="stylesheet" href="../assets/css/admin/hotel.css">
</head>
 
<body>
    <div class="signUpPage">
        <div class="nav">
            <div class="nav-part2">
                <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                        <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
                    </svg>
                    <a href="hotellist.php">Back</a></h3>
                    <h3><a href="hotellist.php">Hotel List</a></h3>
                </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Update Hotel</h3>
            <div class="signUpPage-bottom">
              <h1>Update <br> Your <br> Hotel</h1>
            </div>
          </div>
          <div class="container"> 
            <form action="" method="POST" enctype="multipart/form-data">
              <?php echo csrf_field(); ?>
              <label for="activity" class="required">Hotel name</label>
              <input type="text" id="hotelName" name="Hotel_Name" value="<?php echo h($data['Hotel_Name']); ?>" required>
            
              <label for="activity" class="required">Address</label>
              <input type="text" id="address" name="Hotel_Address" value="<?php echo h($data['Hotel_Address']); ?>" required>
              
              <label for="activity" class="required">Amenities</label>
              <textarea id="amenities" name="amenities" required><?php echo h($data['amenities']); ?></textarea>
            
              <label for="activity" class="required">Phone No</label>
              <input type="tel" id="phoneNumber" name="phoneno" value="<?php echo h($data['PhoneNo']); ?>" required>
            
              <label for="activity" class="required">Email</label>
              <input type="email" id="email" name="email" value="<?php echo h($data['email']); ?>" required>
            
              <label for="activity" class="required">No of Rooms</label>
              <input type="number" id="numberOfRooms" name="NumberOfRooms" value="<?php echo h($data['NumberOfRooms']); ?>" required>
            
              <label for="activity" class="required">Price of Room</label>
              <input type="number" id="PriceOfRoom" name="PriceOfRoom" value="<?php echo h($data['PriceOfRoom']); ?>" required>
            
              <label for="activity" class="required">Hotel Image</label>
              <input type="file" id="hotelImage" name="Hotel_Image" accept="image/*">
            
              <button class="submitButton" type="submit" name="submit">Update</button>
            </form>  
          </div> 
        </div>
      </div>
</body>
</html>