<?php
function validateUploadedImage($file) {
  // 1. Check for upload errors
  if ($file['error'] !== UPLOAD_ERR_OK) {
    return ['valid' => false, 'message' => 'Upload error occurred.'];
  }

  // 2. Whitelist allowed extensions
  $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
  $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  if (!in_array($ext, $allowed_extensions)) {
    return ['valid' => false, 'message' => 'Only JPG, PNG, and WebP files allowed.'];
  }

  // 3. Validate real MIME type (not just extension)
  $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);
  if (!in_array($mime, $allowed_mimes)) {
    return ['valid' => false, 'message' => 'Invalid file type detected.'];
  }

  // 4. Check file size (max 2MB)
  if ($file['size'] > 2 * 1024 * 1024) {
    return ['valid' => false, 'message' => 'File must be under 2MB.'];
  }

  return ['valid' => true];
}

require_once __DIR__ . '/../config/admin_guard.php';
include("../config/connection.php");
error_reporting(0);

if(isset($_GET['id'])){
    $stmt = $conn->prepare("SELECT * FROM tour_package WHERE TourPackage_Id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = mysqli_fetch_assoc($result);
    $stmt->close();
    if(!$data){
        echo "Invalid request..!";
        exit;
    }
}

if(isset($_POST['submit'])){
    $Package_Name = $_POST['package_name'];
    $Package_Type = $_POST['package_type'];
    $Package_Location = $_POST['Package_Location'];
    $Package_Price = $_POST['Package_price'];
    $Package_Features = $_POST['package_features'];
    $Package_Details = $_POST['package_details'];
    $phone = $_POST['phone'];
    $validation = validateUploadedImage($_FILES['package-img']);
    if (!$validation['valid']) {
        die($validation['message']);
    }

    // Only AFTER validation passes:
    $ext = strtolower(pathinfo($_FILES['package-img']['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid('package_', true) . '.' . $ext;
    $folder = '../uploads/' . $new_filename;
    move_uploaded_file($_FILES['package-img']['tmp_name'], $folder);

    $stmt2 = $conn->prepare("UPDATE tour_package SET Package_Name=?, Package_Type=?, Package_Location=?, Price=?, Package_Features=?, Package_Details=?, Phone=?, Package_Image=? WHERE TourPackage_Id = ?");
    $stmt2->bind_param("ssssssssi", $Package_Name, $Package_Type, $Package_Location, $Package_Price, $Package_Features, $Package_Details, $phone, $folder, $_GET['id']);
    $result = $stmt2->execute();
    $stmt2->close();

    if($result){
        echo "<script>alert('Data updated Successfully..!')</script>";
        header("Refresh:0.5; url=tourlist.php");
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
    <title>Update Package</title>
    <link rel="stylesheet" href="../css/admin/hotel.css">
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
              <label for="activity" class="required">Package Name</label>
              <input value="<?php echo $data['Package_Name'] ?>" type="text" name="package_name" placeholder="Create Package" autocomplete="off" required>
            
              <label for="activity" class="required">Package Type</label>
              <input value="<?php echo $data['Package_Type'] ?>" type="text" name="package_type" placeholder="Package Type (Family/Couple)" required />
            
              <label for="activity" class="required">Location</label>
              <input value="<?php echo $data['Package_Location'] ?>" type="text" name="Package_Location" placeholder="Package Location" required />
            
              <label for="activity" class="required">Price</label>
              <input value="<?php echo $data['Price'] ?>" type="number" name="Package_price" placeholder="Package price" required />
            
              <label for="activity" class="required">Features</label>
              <input value="<?php echo $data['Package_Features'] ?>" type="text" name="package_features" placeholder="Package features..!" autocomplete="off" required>
            
              <label for="activity" class="required">Phone No</label>
              <input type="number" placeholder="Phone" name="phone" value="<?php echo $data['Phone'] ?>" required>
            
              <label for="activity" class="required">Package Image</label>
              <input  name="package-img" type="file"  required> 
            
              <label for="activity" class="required">Details</label>
              <textarea rows='4' cols='1'  name="package_details" placeholder="Package Details" required><?php echo $data['Package_Details'] ?></textarea>  
            
              <input class="button-part1" type="submit" name="submit" value="Update" required />
            </form>  
               
            
          </div> 
        </div>
      </div>
</body>
</html>
 