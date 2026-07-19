<?php
include("../config/connection.php");
// error_reporting(0);


?>


<?php 
 
 
 if (isset($_POST['submit'])) {
     // Get form data
     $Package_Name = $_POST['package_name'];
     $Package_Type = $_POST['package_type'];
     $Package_Location = $_POST['Package_Location'];
     $Package_Price = $_POST['Package_price'];
     $Package_Features = $_POST['package_features'];
     $Package_Details = $_POST['package_details'];
     $phone = $_POST['phone'];
     $city = $_POST['city'];
 
     // Handle file upload
     $file = $_FILES['package-img']['name'];
     $tempname = $_FILES['package-img']['tmp_name'];
    // $folder = "uploads/" . basename($file); // Store images in 'uploads/' folder
     $folder = '../image/'.$file;

     if (move_uploaded_file($tempname, $folder)) {
         // Insert data into the database using prepared statements
         $sql = "INSERT INTO create_intern_package 
                 (Package_Name, Package_Type, Package_Location, Price, Package_Feature, Phone, Package_Image, City, Package_Details) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
 
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("sssssssss", $Package_Name, $Package_Type, $Package_Location, $Package_Price, $Package_Features, $phone, $folder, $city, $Package_Details);
 
         if ($stmt->execute()) {
             echo "<script>alert('International Tour Package added successfully!');</script>";
             header("Refresh: 0.5; url=adminhomepage.php");
             exit;
         } else {
             echo "<script>alert('Database insertion failed.');</script>";
         }
 
         $stmt->close();
     } else {
         echo "<script>alert('Failed to upload image.');</script>";
     }
 }
 
 $conn->close();
 ?>
 
<?php

// Suppress error reporting (not recommended in production)
error_reporting(0);

// Include database connection
//include('db_connection.php'); // Make sure this file contains your database connection setup.

// if (isset($_POST['submit'])) {
//     // Retrieve form data
//     $Package_Name = $_POST['package_name'];
//     $Package_Type = $_POST['package_type'];
//     $Package_Location = $_POST['Package_Location'];
//     $Package_Price = $_POST['Package_price'];
//     $Package_Features = $_POST['package_features'];
//     $Package_Details = $_POST['package_details'];
//     $phone = $_POST['phone'];
//     $city = $_POST['city'];

//     // Handle file upload
//     $file = $_FILES['package-img']['name'];
//     $tempname = $_FILES['package-img']['tmp_name'];
//     $folder = "../image/" . $file;

//     // Check if file upload is successful
//     if (move_uploaded_file($tempname, $folder)) {
//         // Insert data into the database
//         $sql = "INSERT INTO create_intern_package 
//                 (Package_Name, Package_Type, Package_Location, Price, Package_Feature, Phone, Package_Image, City, Package_Details) 
//                 VALUES 
//                 ('$Package_Name', '$Package_Type', '$Package_Location', '$Package_Price', '$Package_Features', '$phone', '$folder', '$city', '$Package_Details')";

//         // Execute query
//         if ($conn->query($sql) === TRUE) {
//             echo "<script>alert('International Tour Package added successfully!')</script>";
//             header("Refresh: 0.5; url=adminhomepage.php");
//         } else {
//             echo "<script>alert('Database insertion failed: " . $conn->error . "')</script>";
//         }
//     } else {
//         echo "<script>alert('Failed to upload image.')</script>";
//     }
// }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add International Tour</title>
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
              <h3 id="none" ><a href="International_tourlist.php">International Tour List</a></h3>
          </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
          <h3>Build international tour</h3>
            <div class="signUpPage-bottom">
              <h1>Start <br> Your <br> Journey</h1>
            </div>
    
          </div>
          <div class="container"> 
            <form action="" method="POST" enctype="multipart/form-data">
              <label for="activity" class="required">Package Name</label>
              <input type="text" name="package_name" placeholder="Package Name" required>
    
              <label for="activity" class="required">Package Type</label>
              <input type="text" name="package_type" placeholder="Package Type (Family/Couple)" required>
    
              <label for="activity" class="required">Package Location</label>
              <input type="text" name="Package_Location" placeholder="Package Location" required>
               
              <label for="activity" class="required">Package Price</label>
              <input type="number" name="Package_price" placeholder="Package Price" required>

              <label for="activity" class="required">Package Features</label>
              <input type="text" name="package_features" placeholder="Package Features" required>

              <label for="activity" class="required">Phone No</label>
              <input type="number" placeholder="Phone Number" name="phone" required>
                  
              <label for="activity" class="required">Package Image</label>
              <input type="file" name="package-img" required>
                 
              <label for="activity" class="required">Select City</label>
              <select name="city" id="city" required>
                <option value="" disabled selected>Select a City</option>
                <option value="Orange County">Orange County</option>
                <option value="New York">New York</option>
                <option value="Atlanta">Atlanta</option>
                <option value="New Jersey">New Jersey</option>
                <option value="Dallas">Dallas</option>
                <option value="Salt Lake City">Salt Lake City</option>
            </select> 
                
              <label for="password" class="required">Package Details</label>
              <input type="text" name="package_details" placeholder="Package Details" required>     

              <input style="color: black;" class="button-part1" type="submit" name="submit" value="Create">
      
            </form>

             
 
          </div> 
        </div>
      </div>
</body>
</html>