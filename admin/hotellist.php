    <?php
   include("../config/connection.php");
   error_reporting(0);
   $sql = "select * from create_hotel ";
   $result = $conn->query($sql);
  if(isset($_GET['ID'])){
    extract($_GET);
       $sql = "DELETE FROM create_hotel WHERE Hotel_Id = " . $_GET['ID'];
       $Result = $conn->query($sql);
       if($Result){
        echo  "<script>alert('Data Deleted Successfully..!')</script>";
         header("Refresh:0.5; url=hotellist.php");
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
    <title>Hotel List</title>
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <link rel="stylesheet" href="../css/pwd_update.css">
    <style>
    
      @font-face {
    font-family: twl;
    src: url(/font/66bdbff11e188b5245c6ce47_TWKLausanne-400.ttf);
  }
  @font-face {
    font-family: regular;
    src: url(/font/66bdbfd4186d4f1e46bc84ab_Tartuffo-Regular.ttf);
  }
  @font-face {
    font-family: regular2;
    src: url(/font/66bdbfce81e82ca87a30c297_Tartuffo-Light.ttf);
  }
  * {
    padding: 0%;
    margin: 0%;
    box-sizing: border-box;
    /* background-color: #F3F1F1; */
  }
  html,
  body {
    height: 100%;
    width: 100%;
  }
 
  .page1 {
    width: 100%;
    min-height: 100vh;
    background-color: black; 
  }
  .nav {
    width: 100%;
    height: 10vh;
    /* color: white; */
    align-items: center;
    justify-content: space-between;
    display: flex;
    padding: 2vw 4vw;
  }
  .nav1 {
    width: 100%;
    height: 10vh;
    /* color: white; */
    margin-top: 10vh;
    align-items: center;
    justify-content: space-between;
    display: flex;
    padding: 2vw 4vw;
  }
  .nav h3 {
    font-family: regular;
    font-size: 1.5vw;
    color: white;
    font-weight: 400;
  }
  
  .nav-part1 h2 {
    font-weight: 400;
    font-size: 2.5vw;
    font-family: regular;
    text-transform: capitalize;
  }
  
  .nav h1 {
    font-family: regular;
    text-transform: capitalize;
    font-weight: 400;
    font-size: 2.5vw;
  }
  .nav1 h1 {
    font-family: regular;
    text-transform: capitalize;
    font-weight: 400;
    font-size: 2.5vw;
  }
  .nav-part2 {
    gap: 2vw;
    display: flex;
    align-items: center;
    font-family: twl;
    justify-content: space-between;
  }
  .nav-part2 h3 {
    font-family: twl;
    font-size: 1.5vw;
    font-weight: 400;
    color: white;
  }
  .nav-part2 a {
    text-decoration: none;
    text-transform: capitalize;
    color: black;
  }
  .middle4 {
     
    width: 100%;
    
    padding: 1vw 2vw;
  }
  .booking{
    width: 100%;
    height: 35vh;
    color: #0b0a0e;
    border-top: 1px solid rgb(155, 151, 151);
    align-items: center;
    justify-content: space-around;
    display: flex;
    /* padding: 0 1vw; */
  }
  .booking1{
    width: 50%;
    display: flex;
    align-items: center;
    gap: 1.5vw;
    height: 100%;
    justify-content: center; 
  }
  .booking2{
    width: 50%;
    height: 100%;
    align-items: center;
    justify-content: space-between;
    display: flex; 
  }
  .book-part11{
    display: flex; 

  }
  .book-part1{
    width: 50%;
    height: 25vh;
   
  
  }
  .book-part1 img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    background-position: center;
    background-size: cover;
  }
  .book-part2{
    width: 50%;
    height: 25vh;
    /* align-items: center; */
    justify-content: center;
    display: flex;
    flex-direction: column;
    /* text-align: left; */
     
  }
  .book-part2 h2{
    font-family: regular2;
    font-weight: 400;
    font-size: 2.5vw;
  }
  .book-part2 h4{
    font-family: twl;
    font-size: 1.3vw;
    font-weight: 400;
    opacity: 0.5;
  }
  .book-part3{
    width: 60%;
    margin-left: 2.5vw;
    align-items: center;
    justify-content: center;
    display: flex;
    height: 25vh;
   
  }
  .book-part3 h5{
    font-family: twl;
    font-size: 1.2vw;
    font-weight: 200;
    opacity: 0.5;
  }


  .book-part4 {
    display: flex;
    width: 30%;
    padding: 2vw;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    gap: 0.8vw;
  }
  
  .book-part4 button {
    width: 80%;
    letter-spacing: 1px;
    height: 5vh;
    background-color: black;
    text-align: center;
    text-transform: uppercase;
    padding: 0.5vw;
    cursor: pointer;
    border: 1px solid silver;
    font-weight: 500;
    font-family: twl;
    color: white;
    transition: all ease 0.4s;
    position: relative;
    border-radius: 1.5vw;
    overflow: hidden;
  }
  
  .book-part4 button:hover {
    background-color: white;
    color: black;
  }
  
  .book-part4 button a {
    color: white;
    text-decoration: none;
    position: relative;
    z-index: 9;
  }
  
  .book-part4 button:hover a {
    color: black;
  }
       @media (max-width: 600px){
          .page1{
            min-height: 100vh;
          }
          .nav{
            height: 10vh;
            padding: 0 1vh;
          }
          .nav h1 {
            display: none;
          }
          .nav-part1 h2 {
             font-size: 3vh; 
             letter-spacing: 1px;
          }
          .nav-part2 h3{
            font-size: 2.5vh;
          }
          .part1{
            justify-content: start;
            align-items: start;
          }
          .part1 table{
            padding: 0;
          }
          .part1 th,td,button{
            padding: 0%;
            font-size: 1.5vh;
          }
          .booking{
            flex-direction: column;
            gap: 0;
            height: 50vh; 
          }
          .booking1{
            width: 100%; 
            justify-content: space-between; 
            gap: 0;
          }
         .book-part1{
          width: 40%;
          padding: 2vw;
          height: 25vh;
         }
         .book-part1 img{
          border-radius: 1vw;
         }
         .book-part2{
          width: 60%;
          height: 25vh;
          align-items: start;
          justify-content: start; 
          padding: 2vw;
         }
         .book-part2 h2{
          font-size: 4vh;
          text-transform: capitalize;
         }
         .book-part2 h4{
          font-size: 2.5vh;
          text-transform: capitalize;
         }
          .booking2{
            width: 100%;
            display: block; 
          }
          .book-part3{ 
            width: 100%;
            height: 15vh;
            line-height: 2.5vh;
            overflow: hidden; 
            padding: 0 2vw;
          }
          .book-part3 h5{
            font-size: 2vh;
          }
          .book-part4{
            width: 70%;
            flex-direction: row;
             
          }
          .book-part4 button{
            height: 5vh;
            width: 80%;
            letter-spacing: 0.6vw;
            border-radius: 2.5vh;
            align-items: center; 
             
          }
          .book-part4>button:first-child{
            background-color: white;
            color: black;
          }
          .book-part4>button:first-child a{
             
            color: black;
          }
           

         
          
           
        }
    </style>
    
</head>
<body>
   <div class="page1">
    <div class="nav">
        <div class="nav-part1">
            <h2>Hotels</h2>
        </div>
        <h1>The Real Travel</h1>
        <div class="nav-part2">
            <h3><a href="adminhomepage.php" style="color: white">Home</a></h3>
            <h3><a href="#" style="color: white">Hotel</a></h3>
            <h3><a href="tourlist.php" style="color: white">Package</a></h3>
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
                    <img src="<?php echo $row['Hotel_Image']; ?>" alt="img">
                </div>
                <div class="book-part2">
                    <h2><?php echo $row['Hotel_Name']; ?></h2>
                    <h4><?php echo $row['Hotel_Address']; ?></h4>
                </div>
            </div>
            <div class="booking2">

                <div class="book-part3"> 
                    <h5><?php echo $row['amenities']; ?></h5>
                </div>
                <div class="book-part4"> 
                    <button  ><a href="update_hotel.php?id=<?php echo $row['Hotel_Id'] ?>" >Update</a> </button>
                    <button  ><a href="hotellist.php?ID=<?php echo $row['Hotel_Id'] ?>">Delete</a></button>
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