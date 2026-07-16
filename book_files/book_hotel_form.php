<?php
require_once "../config/user_guard.php";
include("../config/connection.php");
include("../config/email_config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function Sendemail_hotel_approvel($User_Name, $email)
{
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;                      
        $mail->isSMTP();                                            
        $mail->Host       = MAIL_HOST;                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = MAIL_USERNAME;                     
        $mail->Password   = MAIL_PASSWORD;                               
        $mail->SMTPSecure = (MAIL_SECURE === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;            
        $mail->Port       = MAIL_PORT;                                    

        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress('travelindia9500@gmail.com');      
        $mail->addReplyTo($email, $User_Name);

        $mail->isHTML(true);                                  
        $mail->Subject = ' Approval Hotel Request From ' . h($User_Name) . '..!';
        $mail->Body    = "<h3>Hello Travel_India Team,</h3>
                          <p>My name is " . h($User_Name) . ".</p>
                          <p>I am excited to book your Hotel-Packages, so I hope you can approve my package in your accounts.</p>";

        $mail->send();
    } catch (Exception $e) {
        error_log("PHPMailer error in hotel request: " . $mail->ErrorInfo);
    }
}

$pass_hotel_id = (int)($_GET['pass_hotel_id'] ?? 0);

if ($pass_hotel_id <= 0) {
    echo "Invalid request..!";
    exit;
}

// Fetch hotel details securely
$stmt = $conn->prepare("SELECT Hotel_Name, PriceOfRoom, Hotel_Address FROM create_hotel WHERE Hotel_Id = ? LIMIT 1");
$stmt->bind_param("i", $pass_hotel_id);
$stmt->execute();
$res_hotel = $stmt->get_result();
$data = $res_hotel->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Hotel not found..!";
    exit;
}

$user = $_SESSION["email"];

// Fetch user ID securely
$stmt_user = $conn->prepare("SELECT user_Id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
$row = $res_user->fetch_assoc();
$id = $row['user_Id'] ?? 0;
$stmt_user->close();

if (isset($_POST['submit'])) {
    $User_Name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $Phone = trim($_POST['Mobile_No'] ?? '');
    $Hotel_Date = $_POST['date'] ?? '';
    $Hotel_Name = $data['Hotel_Name'];
    $Hotel_Price = $data['PriceOfRoom'];
    $Hotel_Duration = $_POST['destination'] ?? '';
    $Hotel_Address = $data['Hotel_Address'];
    $massage = trim($_POST['massage'] ?? '');

    // Server-side validation
    if (empty($User_Name) || empty($email) || empty($Phone) || empty($Hotel_Date) || empty($Hotel_Duration)) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO hotel_booking (user_Id, User_Name, Email_Id, Mobile_No, date, Hotel_Name, Price, Duration, Hotel_Address, Massage, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt_insert->bind_param("isssssssss", $id, $User_Name, $email, $Phone, $Hotel_Date, $Hotel_Name, $Hotel_Price, $Hotel_Duration, $Hotel_Address, $massage);
        
        if ($stmt_insert->execute()) {
            Sendemail_hotel_approvel($User_Name, $email);
            echo "<script>alert('Thank you for booking with us! You will get Approval within 24-hours...!'); window.location.href='../Book_data.php';</script>";
            exit;
        } else {
            echo "Invalid Query..!";
        }
        $stmt_insert->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hotel</title>
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
                    <a href="book_hotel.php?id=<?php echo h($pass_hotel_id); ?>">to go Back</a></h3>
                </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Build Hotel</h3>
            <div class="signUpPage-bottom">
              <h1>Start <br> Your <br> Journey</h1>
            </div>
          </div>
          <div class="container"> 
            <form id="booking-form" action="" method="post">
              <?php echo csrf_field(); ?>
              <label for="activity" class="required">full name</label>
              <input type="text" id="name" name="name" placeholder="John Doe" required>
              
              <label for="activity" class="required">email</label>
              <input type="email" id="email" name="email" placeholder="john.doe@example.com" value="<?php echo h($user); ?>" required>
              
              <label for="activity" class="required">phone no</label>
              <input type="number" id="phone" name="Mobile_No" placeholder="+1234567890" required>
              
              <label for="activity" class="required">date</label>
              <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
              
              <label for="activity" class="required">select days</label>
              <select id="destination" name="destination" required>
                <option value="">Select your Choice</option>
                <option value="2 Days, 1 Night">2 Days, 1 Night</option>
                <option value="4 Days, 3 Night">4 Days, 3 Night</option>
                <option value="6 Days, 5 Night">6 Days, 5 Night</option>
              </select>
              
              <label for="activity" class="required">message</label>
              <textarea id="message" name="massage" rows="4" placeholder="Any special requests or requirements"></textarea>
              
              <button type="submit" class="button-part1" name="submit">Book Now</button>
            </form>  
          </div> 
        </div>
      </div>
</body>
</html>