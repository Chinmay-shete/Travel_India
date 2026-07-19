<?php
include('../config/connection.php');
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  
  require '../PHPMailer/src/Exception.php';
  require '../PHPMailer/src/PHPMailer.php';
  require '../PHPMailer/src/SMTP.php';

   if(isset($_POST['send'])){
    $name = $_POST['name'];                
    $email = $_POST['email'];
    $massage = $_POST['massage'];
 
    $sql = "insert into feedback (name, email, massage) 
    values('$name','$email','$massage')";

    $result = $conn->query($sql);
 
    if($result){
       

    

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

    //Recipients
    $mail->setFrom('harsh1234vathare@gmail.com', 'Travel_India.com');
    $mail->addAddress('tourism@mailinator.com');     //Add a recipient
    $mail->addAddress('travelindia9500@gmail.com');               //Name is optional
    $mail->addReplyTo('harsh1234vathare@gmail.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name



   //  $sql_query = "select * from feedback ";
   //  $feedback_data = $conn->query($sql_query);
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Feedback From '. $_POST['name'] .'..!';
    $mail->Body    = '<h3>Hello Travel_India Team ,</h3> <p><b> You got a new message from '. $_POST['name'] .' ,</b></p>-

    <p><b> Their Email_Id :-</b>'. $_POST['email'] .' </p>- 
    <p><b> Messages :- </b>'. $_POST['massage'] . '</p>


    <p><h3>Best wishes ,</h3><b> Travel_India Team </b></p>
 
  
       ';
             
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $res = $mail->send();
    if($res)
       echo  "<script>alert('Your Messages succesfully Send..!')</script>";//'Message has been sent';
    else 
    echo  "<script>alert('Your Messages not Send..!')</script>";///'Message has been not sent';

 } 
catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    }
    else{
       echo "Invalid Query..!";
    }
 
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../css/otpNew.css">
</head> 
<body>
    <div class="signUpPage">
        <div class="nav">
          <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
            <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
        </svg>
        <a href="../other/homepage.php" style="color: white;">Home</a></h3>
          <div class="nav-part1">
              <h3>EST-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Connect with us</h3>
            <div class="signUpPage-bottom">
              <h1>Connect <br> With Us</h1>
            </div>
    
          </div>
          <div class="container"> 
            <form action="" method="post">  
              <label for="activity" class="required">Name</label>
              <input type="text" id="name" name="name" placeholder="Enter your name" required>
    
              <label for="activity" class="required">email</label>
              <input type="email" id="email" name="email" placeholder="Enter your email" required>
                 
              <label for="password" class="required">Message</label>
              <textarea id="message" name="massage" placeholder="Write your message here" required></textarea>
      
              <button type="submit" name="send">Send Message</button>
      
            </form>
          </div> 
        </div>
      </div>
</body>
</html>