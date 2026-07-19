<?php
   include("../config/connection.php");
 
   error_reporting(0);

   $sql = "select * from tour_package ";
   $result = $conn->query($sql);

  if(isset($_GET['ID'])){
    extract($_GET);
       $sql = "DELETE FROM tour_package WHERE TourPackage_Id = " . $_GET['ID'];
       $Result = $conn->query($sql);
       if($Result){
        echo  "<script>alert('Data Deleted Successfully..!')</script>";
         header("Refresh:0.5; url=tourlist.php");
      }
      else{
          echo "Not  Deleted..!";
      }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package List</title>
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <link rel="stylesheet" href="../css/pwd_update.css">
    <style>
      
        .middle4{
         padding-inline: 2vw;
        }
    </style>
</head>
<body>
   <div class="page1">
    <div class="nav">
        <div class="nav-part1">
            <h2 id="nav-part3">Package</h2>
        </div>
        <h1>The Real Travel</h1>
        <div class="nav-part2">
            <h3><a href="adminhomepage.php">Home</a></h3>
            <h3><a href="hotellist.php">Hotels</a></h3>
            <h3><a href="#">Package</a></h3>
        </div>
    </div>

    
 <?php 
    if($result->num_rows > 0){
        while($row = mysqli_fetch_assoc($result)){
   
  ?>
    <div class="middle4">
        <div class="booking">
            <div class="booking1">

                <div class="book-part1">
                    <img src="<?php echo $row['Package_Image']; ?>" alt="img">
                </div>
                <div class="book-part2">
                    <h2><?php echo $row['Package_Name']; ?></h2>
                    <h4><?php echo $row['Package_Location']; ?></h4>
                </div>
            </div>
            <div class="booking2">

                <div class="book-part3"> 
                    <h5><?php echo $row['Package_Features']; ?>....</h5>
                </div>
                <div class="book-part4"> 
                    <button  ><a href="update_tour.php?id=<?php echo $row['TourPackage_Id'] ?>"  >Update</a> </button>
                    <button  ><a href="tourlist.php?ID=<?php echo $row['TourPackage_Id'] ?>"   >Delete</a></button>
                </div>
            </div>
        </div>
    </div>
    <?php
          }
        }
        ?> 
</div>
<script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.js"></script>
<script>
    const locoScroll = new LocomotiveScroll({
    el: document.querySelector(".page1"),
    smooth: true,
  });
</script>
</body>
</html>