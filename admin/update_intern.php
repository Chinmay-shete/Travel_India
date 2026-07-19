<?php
require_once "../config/user_auth_acces.php";
include("../config/connection.php");

$ci_package_id = (int)($_GET['id'] ?? 0);

if ($ci_package_id <= 0) {
    echo "Invalid request..!";
    exit;
}

// Fetch international package securely
$stmt = $conn->prepare("SELECT Package_Name, Package_Type, Package_Location, Price, Package_Feature, Phone, Package_Image, City, Package_Details FROM create_intern_package WHERE CIPackage_Id = ? LIMIT 1");
$stmt->bind_param("i", $ci_package_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "International package not found..!";
    exit;
}

if (isset($_POST['submit'])) {
    $Package_Name = trim($_POST['package_name'] ?? '');
    $Package_Type = trim($_POST['package_type'] ?? '');
    $Package_Location = trim($_POST['Package_Location'] ?? '');
    $Package_Price = (int)($_POST['Package_price'] ?? 0);
    $Package_Features = trim($_POST['package_features'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $Package_Details = trim($_POST['package_details'] ?? '');

    // Server-side validation
    if (empty($Package_Name) || empty($Package_Type) || empty($Package_Location) || $Package_Price <= 0 || empty($Package_Features) || empty($phone) || empty($city) || empty($Package_Details)) {
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

        // Update database with prepared statement
        $update_stmt = $conn->prepare("UPDATE create_intern_package SET Package_Name = ?, Package_Type = ?, Package_Location = ?, Price = ?, Package_Feature = ?, Phone = ?, Package_Image = ?, City = ?, Package_Details = ? WHERE CIPackage_Id = ?");
        $update_stmt->bind_param("sssisssssi", $Package_Name, $Package_Type, $Package_Location, $Package_Price, $Package_Features, $phone, $folder, $city, $Package_Details, $ci_package_id);
        
        if ($update_stmt->execute()) {
            echo "<script>alert('Data updated Successfully..!')</script>";
            header("Refresh:0.5; url=International_tourlist.php");
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
    <title>Update International Tour</title>
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
                </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Update International Tour</h3>
            <div class="signUpPage-bottom">
              <h1>Update <br> Your <br> International Tour</h1>
            </div>
          </div>
          <div class="container"> 
            <form action="" method="POST" enctype="multipart/form-data">
              <?php echo csrf_field(); ?>
              <label for="activity" class="required">Package Name</label>
              <input type="text" name="package_name" placeholder="Package Name" value="<?php echo h($data['Package_Name']); ?>" required>
              
              <label for="activity" class="required">Package Type</label>
              <input type="text" name="package_type" placeholder="Package Type (Family/Couple)" value="<?php echo h($data['Package_Type']); ?>" required>
              
              <label for="activity" class="required">Location</label>
              <input type="text" name="Package_Location" placeholder="Package Location" value="<?php echo h($data['Package_Location']); ?>" required>
              
              <label for="activity" class="required">Price</label>
              <input type="number" name="Package_price" placeholder="Package Price" value="<?php echo h($data['Price']); ?>" required>
              
              <label for="activity" class="required">Package Features</label>
              <input type="text" name="package_features" placeholder="Package Features" value="<?php echo h($data['Package_Feature']); ?>" required>
              
              <label for="activity" class="required">Phone No</label>
              <input type="number" placeholder="phone" name="phone" value="<?php echo h($data['Phone']); ?>" required>
              
              <label for="activity" class="required">Package Image</label>
              <input type="file" name="package-img">
              
              <label for="city">Select City:</label>
              <select name="city" id="city" required>
                <option value="Orange County" <?php echo $data['City'] == 'Orange County' ? 'selected' : ''; ?>>Orange County</option>
                <option value="New York" <?php echo $data['City'] == 'New York' ? 'selected' : ''; ?>>New York</option>
                <option value="Atlanta" <?php echo $data['City'] == 'Atlanta' ? 'selected' : ''; ?>>Atlanta</option>
                <option value="New Jersey" <?php echo $data['City'] == 'New Jersey' ? 'selected' : ''; ?>>New Jersey</option>
                <option value="Dallas" <?php echo $data['City'] == 'Dallas' ? 'selected' : ''; ?>>Dallas</option>
                <option value="Salt Lake City" <?php echo $data['City'] == 'Salt Lake City' ? 'selected' : ''; ?>>Salt Lake City</option>
              </select>
              
              <label for="activity" class="required">Details</label>
              <input type="text" name="package_details" placeholder="Package Details" value="<?php echo h($data['Package_Details']); ?>" required>  
                 
              <input class="button-part1" type="submit" name="submit" value="Update">
            </form>
          </div> 
        </div>
      </div>
</body>
</html>