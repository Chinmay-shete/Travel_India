<?php
   include("../config/connection.php");
   error_reporting(0);

   if(isset($_GET['id'])){
    $sql = "select * from create_hotel where Hotel_Id = " .  $_GET['id'];
     $result = $conn->query($sql);
     $data = mysqli_fetch_assoc($result);
     if($data){
     }
 }
 else{
     echo "Invalid request..!";
     exit;
 }

 if(isset($_POST['submit'])){
    $Hotel_Name = $_POST['Hotel_Name'];
    $Hotel_Address= $_POST['Hotel_Address'];
    $phoneno = $_POST['phoneno'];
    $email = $_POST['email'];
    $NumberOfRooms = $_POST['NumberOfRooms'];
    $PriceOfRoom = $_POST['PriceOfRoom'];
    $amenities = $_POST['amenities'];

    $file = $_FILES['Hotel_Image']['name'];
    $tempname = $_FILES['Hotel_Image']['tmp_name'];
    $folder = '../hotel_image/'.$file;
    move_uploaded_file($tempname,$folder);

   $sql = "UPDATE create_hotel SET Hotel_Name='$Hotel_Name', Hotel_Address='$Hotel_Address',PhoneNo='$phoneno',email='$email',NumberOfRooms='$NumberOfRooms',PriceOfRoom='$PriceOfRoom',amenities='$amenities',Hotel_Image='$folder' WHERE Hotel_Id = " .  $_GET['id'];
   $result = $conn->query($sql);
   if($result){
    echo  "<script>alert('Data updated Successfully..!')</script>";
       header("Refresh:0.5; url=hotellist.php");
   }
   else{
       echo "Not Updated..!";
   }
 }     
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Hotel</title>
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
              <label for="activity" class="required">Hotel name</label>
              <input type="text" id="hotelName" name="Hotel_Name" value="<?php echo $data['Hotel_Name'] ?>" required>
            
              <label for="activity" class="required">Address</label>
              <input type="text" id="address" name="Hotel_Address" value="<?php echo $data['Hotel_Address'] ?>" required>
              <label for="activity" class="required">Amenities</label>
              <textarea id="amenities" name="amenities" value="<?php echo $data['amenities'] ?>" required><?php echo $data['amenities'] ?></textarea>
            
              <label for="activity" class="required">Phone No</label>
              <input type="tel" id="phoneNumber" name="phoneno" value="<?php echo $data['PhoneNo'] ?>" required>
            
              <label for="activity" class="required">Email</label>
              <input type="email" id="email" name="email" value="<?php echo $data['email'] ?>" required>
            
              <label for="activity" class="required">No of Rooms</label>
              <input type="number" id="numberOfRooms" name="NumberOfRooms" value="<?php echo $data['NumberOfRooms'] ?>" required>
            
              <label for="activity" class="required">Price of Room</label>
              <input type="number" id="PriceOfRoom" name="PriceOfRoom" value="<?php echo $data['PriceOfRoom'] ?>" required>
            
              <label for="activity" class="required">Hotel Image</label>
              <input type="file" id="hotelImage" name="Hotel_Image" required>
            
              <button class="submitButton" type="submit" name="submit">Update</button>
            </form>  
               
            
          </div> 
        </div>
      </div>
</body>
</html>


   