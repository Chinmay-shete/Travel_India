<?php
require_once "../config/user_auth_acces.php";
include("../config/connection.php");

$tour_id = (int)($_GET['id'] ?? 0);

if ($tour_id <= 0) {
    echo "Invalid request..!";
    exit;
}

// Fetch tour package securely
$stmt = $conn->prepare("SELECT Package_Name, Package_Type, Package_Location, Price, Package_Features, Package_Details, Phone, Package_Image FROM tour_package WHERE TourPackage_Id = ? LIMIT 1");
$stmt->bind_param("i", $tour_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Tour package not found..!";
    exit;
}

if (isset($_POST['submit'])) {
    $Package_Name = trim($_POST['package_name'] ?? '');
    $Package_Type = trim($_POST['package_type'] ?? '');
    $Package_Location = trim($_POST['Package_Location'] ?? '');
    $Package_Price = (int)($_POST['Package_price'] ?? 0);
    $Package_Features = trim($_POST['package_features'] ?? '');
    $Package_Details = trim($_POST['package_details'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Server-side validation
    if (empty($Package_Name) || empty($Package_Type) || empty($Package_Location) || $Package_Price <= 0 || empty($Package_Features) || empty($Package_Details) || empty($phone)) {
        echo "<script>alert('All fields are required and price must be positive.');</script>";
    } else {
        $folder = $data['Package_Image']; // Default to old image
        
        // Handle new file upload if provided
        if (isset($_FILES['package-img']) && $_FILES['package-img']['error'] === UPLOAD_ERR_OK) {
            $tempname = $_FILES['package-img']['tmp_name'];
            $original_name = $_FILES['package-img']['name'];
            
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

        // Update database securely
        $update_stmt = $conn->prepare("UPDATE tour_package SET Package_Name = ?, Package_Type = ?, Package_Location = ?, Price = ?, Package_Features = ?, Package_Details = ?, Phone = ?, Package_Image = ? WHERE TourPackage_Id = ?");
        $update_stmt->bind_param("sssissssi", $Package_Name, $Package_Type, $Package_Location, $Package_Price, $Package_Features, $Package_Details, $phone, $folder, $tour_id);
        
        if ($update_stmt->execute()) {
            echo "<script>alert('Data updated Successfully..!')</script>";
            header("Refresh:0.5; url=tourlist.php");
            exit;
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
    <title>Update Package</title>
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
                    <a href="adminhomepage.php">Back</a></h3>
                    <h3><a href="tourlist.php">Tour List</a></h3>
                </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>update tour</h3>
            <div class="signUpPage-bottom">
              <h1>Update <br> Your <br> Tour</h1>
            </div>
          </div>
          <div class="container"> 
            <form action="" method="POST" enctype="multipart/form-data">
              <?php echo csrf_field(); ?>
              <label for="activity" class="required">Package Name</label>
              <input value="<?php echo h($data['Package_Name']); ?>" type="text" name="package_name" placeholder="Create Package" autocomplete="off" required>
            
              <label for="activity" class="required">Package Type</label>
              <input value="<?php echo h($data['Package_Type']); ?>" type="text" name="package_type" placeholder="Package Type (Family/Couple)" required />
            
              <label for="activity" class="required">Location</label>
              <input value="<?php echo h($data['Package_Location']); ?>" type="text" name="Package_Location" placeholder="Package Location" required />
            
              <label for="activity" class="required">Price</label>
              <input value="<?php echo h($data['Price']); ?>" type="number" name="Package_price" placeholder="Package price" required />
            
              <label for="activity" class="required">Features</label>
              <input value="<?php echo h($data['Package_Features']); ?>" type="text" name="package_features" placeholder="Package features..!" autocomplete="off" required>
            
              <label for="activity" class="required">Phone No</label>
              <input type="number" placeholder="Phone" name="phone" value="<?php echo h($data['Phone']); ?>" required>
            
              <label for="activity" class="required">Package Image</label>
              <input name="package-img" type="file"> 
            
              <label for="activity" class="required">Details</label>
              <textarea rows='4' cols='1' name="package_details" placeholder="Package Details" required><?php echo h($data['Package_Details']); ?></textarea>  
            
              <input class="button-part1" type="submit" name="submit" value="Update" />
            </form>  
          </div> 
        </div>
      </div>
</body>
</html>