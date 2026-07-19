<?php
//email send///////////
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

 require '../PHPMailer/src/Exception.php';
 require '../PHPMailer/src/PHPMailer.php';
 require '../PHPMailer/src/SMTP.php';

 function Sendemail_approvel( $Payment_Id, $total_rate, $Name, $emailforpayment, $phone, $Hotel_Date, $Hotel_Name, $Hotel_Duration)
 {


     //Create an instance; passing `true` enables exceptions
     $mail = new PHPMailer(true);

     try {
         //Server settings
         $mail->SMTPDebug = 0;                      //Enable verbose debug output  //SMTP::DEBUG_SERVER
         $mail->isSMTP();                                            //Send using SMTP
         $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
         $mail->Username   = 'harsh1234vathare@gmail.com';                     //SMTP username
         $mail->Password   = 'olfq duvu rucq tvsv';                               //SMTP password
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
         $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
         $mail->setFrom('travelindia9500@gmail.com', 'The Real-Travel.com');
         $mail->addAddress('travelindia9500@gmail.com'); //$email      
         $mail->addReplyTo('travelindia9500@gmail.com', 'Information');
         $mail->isHTML(true);                                  //Set email format to HTML
        // $mail->Subject = ' Approvel Request From ' . $_POST['name'] . '..!';
        $mail->Subject = ' Approvel Request From ' . $Name . '..!';
         $mail->Body    = "<h3>hello The Real-Travel Team !
                        <p><h4>My name is " . $Name . " . </h4></p></h3><br>
                        <h2><b> Payment Info !</b></h2>
                        <p><b>  Name : </b>" . $Name . "  <br> 
                           <b>  Email Id : </b>" . $emailforpayment . "  <br> 
                           <b>  Mobile No : </b>" . $phone .  " <br>
                           <b>  Staying Date : </b>" . $Hotel_Date . "  <br>
                           <b>  Hotel Name : </b> " . $Hotel_Name . "   <br>
                           <b>  Staying Days : </b> " . $Hotel_Duration . " <br>
                              <br>

                             <p><h2> Payment Status ! </h2></p>
                             <p><b>Payment Id : </b>". $Payment_Id . " </p>
                             <p><b>Total Price : </b>". $total_rate . "Rs </p>
                             <p><b>Payment Status : </b> <b style=color:green;> Success </b> </p><br><br>
                             <button> <a href='http://localhost/main/travel_india-new/admin/adminhomepage.php'>Approval </a></button>
                        ";
         $res = $mail->send();
         if ($res) {
         } else
             echo  "<script>alert('Your Messages not Send..!')</script>"; ///'Message has been not sent';

     } catch (Exception $e) {
         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
     }
 }


?>











<?php
   include("../config/connection.php");

   $order_Id = $_GET['razorpay_payment_id'];
   //  $order_Id = $_SESSION['razorpay_payment_id'];
     $order_Id;

     $_SESSION['Payment_Id'] = $order_Id;

   $name = $_SESSION["username"];
     $email = $_SESSION["Email_ID"];
     $phone = $_SESSION["phone"];
     $rate = $_SESSION['rate'];
     $Hotel_Id = $_SESSION["Hotel_Id"];


     $Hotel_Date = $_SESSION["Hotel_Date"];
    $Hotel_Name = $_SESSION["Hotel_Name"];
    $Hotel_Duration = $_SESSION["total_room"];
     










//find user_Id
$user = $_SESSION["email"];

$query = mysqli_query($conn, "select * from users where email ='$user'");
$row = mysqli_fetch_array($query);

$User_Id = $row['user_Id']; //user_Id = > 
$Hotel_Id; //           TourPackage_Id
//$order_Id;       //           Payment_Id


///Insert into Payment table 
if (isset($_POST['submit'])) {
  $Payment_Id = $_POST['Payment_Id'];
  $total_rate = $_POST['rate'];
  $Name = $_POST['name'];
  $emailforpayment = $_POST['emailforpayment'];

  $sql = "INSERT INTO hotel_payment (User_Id, Hotel_Id , Razorpay_Payment_Id, Total_Price, Name, Email_Id) VALUES
  ('$User_Id','$Hotel_Id','$Payment_Id','$total_rate', '$Name','$emailforpayment')";
  $qury = $conn->query($sql);

  if ($qury) {
    //echo "<script>alert('Data Inserted Successfully..!')</script>";
    Sendemail_approvel($Payment_Id, $total_rate, $Name, $emailforpayment, $phone, $Hotel_Date, $Hotel_Name, $Hotel_Duration);
    header("Refresh:0.2; url=../other/Book_data.php");
  } else {
    //echo "Not Inserted..!";
  }
}

//    //echo $_SESSION["email"];

 
?>
<html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
        }

        .card {
            background: white;
            padding: clamp(20px, 5vw, 50px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            width: min(90%, 500px);
            margin: auto;
        }

        .icon-wrapper {
            width: min(150px, 30vw);
            height: min(150px, 30vw);
            background: #f8f9fa;
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fa-hourglass-half {
            font-size: clamp(30px, 8vw, 60px);
            color: #fd7e14;
        }

        h1 {
            color: #343a40;
            font-size: clamp(24px, 5vw, 36px);
            margin-bottom: 20px;
        }

        p {
            color: #6c757d;
            font-size: clamp(14px, 3vw, 18px);
            line-height: 1.6;
            margin-bottom: 25px;
        }

        button {
            background: #fd7e14;
            color: white;
            border: none;
            padding: clamp(8px, 2vw, 10px) clamp(15px, 4vw, 25px);
            border-radius: 25px;
            font-size: clamp(14px, 3vw, 16px);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: fit-content;
        }

        button:hover {
            background: #f76707;
            transform: translateY(-2px);
        }

        form {
            margin: 0;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 480px) {
            .card {
                padding: 30px 20px;
            }
            
            .icon-wrapper {
                margin-bottom: 20px;
            }
            
            h1 {
                margin-bottom: 15px;
            }
            
            p {
                margin-bottom: 20px;
            }
        }
    </style>
    <!-- <body>
      <div class="card">
      style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;"
      <div >
        <i class="fa-regular fa-hourglass-half"></i>
        
      </div>
        <h1> Pending </h1> 
        <p>Your booking request is under review !<br/> we'll be in touch shortly! <br>
        <form action="" method="post">
          <input type="hidden" name="Payment_Id" value="<?php //echo $order_Id ?>">
          <input type="hidden" name="name" value="<?php //echo $name; ?>">
          <input type="hidden" name="rate" value="<?php //echo $rate; ?>">
          <input type="hidden" name="emailforpayment" value="<?php //echo $email; ?>">
          <input type="hidden" name="phone" value="<?php //echo $phone; ?>">
        Please click <button type="submit" name="submit" style="cursor: pointer;">here</button></p>
        </form>
      </div>
    </body> -->

    <body>
    <div class="card">
        <div class="icon-wrapper">
            <i class="fa-regular fa-hourglass-half"></i>
        </div>
        <h1>Request Pending</h1>
        <p>Your booking request is currently under review.<br>We'll get back to you shortly!</p>
        <form action="" method="post">
            <input type="hidden" name="Payment_Id" value="<?php echo $order_Id ?>">
            <input type="hidden" name="name" value="<?php echo $name; ?>">
            <input type="hidden" name="rate" value="<?php echo $rate; ?>">
            <input type="hidden" name="emailforpayment" value="<?php echo $email; ?>">
            <input type="hidden" name="phone" value="<?php echo $phone; ?>">
            <button type="submit" name="submit">Continue</button>
        </form>
    </div>
</body>
</html>


 