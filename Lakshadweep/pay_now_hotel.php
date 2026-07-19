<?php
 // session_start();
include("../config/connection.php");
error_reporting(0);


//email send///////////
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require '../PHPMailer/src/Exception.php';
// require '../PHPMailer/src/PHPMailer.php';
// require '../PHPMailer/src/SMTP.php';

// function Sendemail_approvel($User_Name, $email)
// {


//     //Create an instance; passing `true` enables exceptions
//     $mail = new PHPMailer(true);

//     try {
//         //Server settings
//         $mail->SMTPDebug = 0;                      //Enable verbose debug output  //SMTP::DEBUG_SERVER
//         $mail->isSMTP();                                            //Send using SMTP
//         $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
//         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
//         $mail->Username   = 'harsh1234vathare@gmail.com';                     //SMTP username
//         $mail->Password   = 'olfq duvu rucq tvsv';                               //SMTP password
//         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
//         $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
//         $mail->setFrom('travelindia9500@gmail.com', 'Travel_India.com');
//         $mail->addAddress('travelindia9500@gmail.com'); //$email      
//         $mail->addReplyTo('travelindia9500@gmail.com', 'Information');
//         $mail->isHTML(true);                                  //Set email format to HTML
//         $mail->Subject = ' Approvel Request From ' . $_POST['name'] . '..!';
//         $mail->Body    = "<h3>hello Travel_India Team !
//                        <p><h4>My name is " . $_POST['name'] . " . </h4></p></h3><br>
//                        <h4> I exited to book your Tour-Packages , So i hope You can approved my package in Your Accounts..!</h4>";
//         $res = $mail->send();
//         if ($res) {
//         } else
//             echo  "<script>alert('Your Messages not Send..!')</script>"; ///'Message has been not sent';

//     } catch (Exception $e) {
//         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//     }
// }





if (isset($_GET['id'])) {
    $sql = "select * from create_hotel where Hotel_Id = " .  $_GET['id'];
    $result = $conn->query($sql);
    $data = mysqli_fetch_assoc($result);
    if ($data) {
       // echo "data fetch succes";
    }
} else {
    echo "Invalid request..!";
    exit;
}




// echo $user = $_SESSION["email"]; //user_Email_ID

// echo $Hotel_Id = $_SESSION["Hotel_Id"]; //hotel_Id

 $user = $_SESSION["email"]; //user_Email_ID

 $Hotel_Id = $_SESSION["Hotel_Id"]; //hotel_Id

$query = mysqli_query($conn, "select * from users where email ='$user'");
$row = mysqli_fetch_array($query);
 $id = $row['user_Id'];  //user_Id

if (isset($_POST['submit'])) {


    $User_Name = $_POST['name'];
    $_SESSION["username"] = $User_Name;
    $email = $_POST['email'];
    $_SESSION["Email_ID"] = $email;

    $no_of_person = $_POST['no_of_person'];

    $Phone = $_POST['Mobile_No'];
    $_SESSION["phone"] = $Phone;

    $Hotel_Date = $_POST['date'];
    $_SESSION["Hotel_Date"] = $Hotel_Date;

    $Hotel_Name = $_POST['package_name'];
    $_SESSION["Hotel_Name"] = $Hotel_Name;

    $Hotel_Price = $_POST['Price'];
    $_SESSION['rate'] = $Hotel_Price;

    $Hotel_Duration = $_POST['days'];
    $_SESSION["total_room"] = $Hotel_Duration;

    //$Package_Type = $_POST['Package_Type'];
    //$no_of_person = $_POST['no_of_person'];


    



    $sql = "insert into hotel_booking( user_Id , Hotel_Id , User_Name , Email_Id , Mobile_No , date , Hotel_Name , No_of_Person , Price , Duration ) values 
        ('$id','$Hotel_Id','$User_Name','$email','$Phone','$Hotel_Date','$Hotel_Name','$no_of_person','$Hotel_Price','$Hotel_Duration')";
    $result = $conn->query($sql);

    if ($result) {
       // Sendemail_approvel("$User_Name", "$email");
       // echo "<script>alert('Thank you for booking with us! You will get Approvel to you within 24-hours...!')</script>";

        header("Refresh:0.2; url=razorpay.php?price=$Hotel_Price");
    } else {
        echo "Invalid Query..!";
    }
}




//no of persons total no = 3 * package_Price;

$room_price = $_POST['Price'];
$total_room = $_POST['total_room'];
$days = $_POST['days'];
$Rooms_Price = $room_price * $total_room;//room & total room
$Total_Price = $Rooms_Price * $days;
$_SESSION['hotel_price'] = $Total_Price;


 


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/admin/hotel.css">
</head>
 
<body>
    <div class="signUpPage">
        <div class="nav">
            <div class="nav-part2">

                <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
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
            <h3>Confirm Payment </h3>
            <div class="signUpPage-bottom">
              <h1>Start <br> Your <br> Journey</h1>
            </div>
            
          </div>
          <div class="container"> 
            <form id="booking-form" action="" method="post">
              <label for="activity" class="required">full name</label>
              <input type="text" id="name" name="name" placeholder="full name" value="<?php echo $_POST['name'] ?>" readonly>
              
              <label for="activity" class="required">email</label>
              <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $_POST['email'] ?>" readonly>
              
              <label for="activity" class="required">no of person</label>
              <input type="number" name="no_of_person"   value="<?php echo $_POST['no_of_person'] ?>" readonly> 
              
              <label for="activity" class="required">phone no </label>
              <input type="number" id="phone" name="Mobile_No" placeholder="phone-no" value="<?php echo $_POST['Mobile_No'] ?>" readonly>
              
              <label for="activity" class="required">date</label>
              <input type="date" id="date" name="date" value="<?php echo $_POST['date'] ?>" readonly>
              
              <label for="activity" class="required">package name</label>
              <input type="text"  id="name" name="package_name" placeholder="vaibhav tours" value="<?php echo $_POST['package_name'] ?>" readonly>
              
              <label for="activity" class="required">price</label>
              <input type="text"  id="number" name="Price" value="<?php echo $Total_Price ?>" readonly>
              
              <label for="activity" class="required">Days</label> 
              <input   id="name" name="days" placeholder="days" value="<?php echo $_POST['days'] ?>" readonly>
              
              <label for="activity" class="required">rooms</label>
              <input   id="name" name="total_room" placeholder="Select rooms" value="<?php echo $_POST['total_room'] ?>" readonly>
              
              <button class="submitButton" type="submit" class="submit-btn" name="submit">Pay Now</button> 
              
            </form>
 
            
          </div> 
        </div>
      </div>
</body>
</html>
