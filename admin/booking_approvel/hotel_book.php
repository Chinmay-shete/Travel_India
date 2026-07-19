<?php
include("../../config/connection.php");
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

function Sendemail_hotel_approvel($email, $fname ,$Mobile_No, $Hotel_Date, $Hotel_Name, $Total_room, $Payment_Id, $Total_Price)
{

  $mail = new PHPMailer(true);

  try {
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'harsh1234vathare@gmail.com';
    $mail->Password   = 'olfq duvu rucq tvsv';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('travelindia9500@gmail.com', 'The Real-Travel.com');
    //$email = $_POST['email'];
    $emailmail = $email;
    $mail->addAddress($emailmail);

    $mail->addReplyTo('travelindia9500@gmail.com', 'Information');

    $mail->isHTML(true);
    $mail->Subject = ' Congratulation Your Hotel Book has been Approved..!';
    $mail->Body    = "<h3>Welcome " . $_POST['fname'] . " in Travel_India.com</h3><h3> Your Hotel Booking has been Succesfully Approved..!</h3><h3>Please check Your Account..!
    
    <br>
      <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f9f9f9;
            }
            .payment-slip {
                width: 60%;
                margin: 50px auto;
                padding: 20px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .payment-slip h1 {
                text-align: center;
                color: #333;
                margin-bottom: 20px;
            }
            .payment-slip table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            .payment-slip table th, .payment-slip table td {
                text-align: left;
                padding: 10px;
                border: 1px solid #ddd;
                color: white;
            }
            .payment-slip table th {
                background-color: #f4f4f4;
                font-weight: bold;
                color: white;
            }
            .payment-slip table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .payment-slip p {
                text-align: center;
                color: #555;
                margin-top: 10px;
            }

            @media only screen and (max-width:500px){
                .payment-slip {
                width: 85%;
                margin: 50px auto;
                
            }

            .payment-slip table {
                 font-size:14px;
            }
           }

            @media only screen and (max-width:440px){
                .payment-slip {
                width: 85%;
                margin: 50px auto;
                  
            }

            .payment-slip table {
                 font-size:14px;
            }
           }
        </style>
    </head>
    <body>
        <div class='payment-slip'>
            <h1>Payment Received</h1>
            <table>
                <tr>
                    <th>Name</th>
                    <td>$fname</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>$email</td>
                </tr>
                <tr>
                    <th>Mobile No</th>
                    <td>$Mobile_No</td>
                </tr>
                <tr>
                    <th>Staying Date</th>
                    <td>$Hotel_Date</td>
                </tr>
                <tr>
                    <th>Hotel Name</th>
                    <td>$Hotel_Name</td>
                </tr>
				<tr>
                    <th>Total Rooms</th>
                    <td>$Total_room</td>
                </tr>
				<tr>
                    <th>Payment Id</th>
                    <td>$Payment_Id</td>
                </tr>
				<tr>
                    <th>Total Price</th>
                    <td>$Total_Price Rs</td>
                </tr>
				<tr>
                    <th>Payment Status</th>
                    <td> <b style = color:green; >Success</b></td>
                </tr>
            </table>
            <p><b>Thank you for booking with us!</b></p><br>
			<p><b>Developed by The Real-Travel.com!</b></p>
        </div>
    </body>
    </html>
    ";

    $res = $mail->send();
    if ($res) {
      echo  "<script>alert('Your Messages succesfully Send..!')</script>";
    } else {
      echo  "<script>alert('Your Messages not Send..!')</script>";
    }
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}














?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Record</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
  <link rel="stylesheet" href="../../css/pwd_update.css">
  <style>
    html,
    body {
      width: 100%;
      height: 100%;
    }

    .page1 {
      width: 100%;
      min-height: 100vh;
      padding: 0 2vw;
      overflow-x: hidden;


    }

    .part1 {
      width: 100%;
      min-height: 10vh;
      /* align-items: center; */
      justify-content: center;
      display: flex;

      border: .2vw solid white;
    }

    table {
      font-family: twl;
      width: 100%;
      border-collapse: collapse;
      padding: 2vw;
      color: white;
    }

    th,
    td {
      padding: 1vw;
      text-align: center;
      color: white;
    }


    th {
      font-size: 1.2vw;
      font-weight: 400;
      text-transform: capitalize;
      font-family: regular;
      border-bottom: .2vw solid white;
      color: white;
    }

    td {
      font-size: 1.2vw;
      border-bottom: 0.2vw solid white;
      color: white;
    }

    button {
      font-size: 1.5vw;
      background-color: transparent;
      border: none;
    }

    #msg {
      text-align: left;
    }
    .submitButton {
			/* padding: 0.5vw;
			background-color: transparent;
			color: black;
			border: none;
			border-radius: 0.5vw;
			cursor: pointer;
			margin-bottom: 1vw;
			font-size: 1.2vw;
			border: 1px solid black; */
			padding: 0.5vw;
			background-color: black;
			color: #08fa08;
			border: none;
			/* border-radius: 0.5vw; */
			cursor: pointer;
			margin-bottom: 1vw;
			font-size: 1.2vw;
			/* border: 1px solid white; */
		}

		/* .submitButton:hover {
			background-color: black;
			transition: 0.7s;
			color: white;
			border: 1px solid white;
			border-radius: 0.8vw;
		} */

		.deleteButton{
			padding: 0.5vw;
			background-color: black;
			color: red;
			border: none;
			/* border-radius: 0.5vw; */
			cursor: pointer;
			margin-bottom: 1vw;
			font-size: 1.2vw;
		}
  </style>
</head>

<body>
  <div class="page1">
    <div class="nav">
      <div class="nav-part1">
        <h2 id="nav-part3">pending list</h2>
      </div>
      <h1>the real travel</h1>
      <div class="nav-part2">
        <h3><a href="../adminhomepage.php">Home</a></h3>
        <h3><a href="../hotellist.php">hotels</a></h3>
        <h3><a href="../tourlist.php">package</a></h3>
      </div>
    </div>
    <div class="part1">

      <table>
        <tr>
          <th scope="col">id</th>
          <th scope="col">user_id</th>
          <th scope="col">Hotel Name</th>
          <th scope="col">User Name</th>
          <th scope="col">Email_Id</th>
          <th scope="col">Mobile-No</th>
          <!-- <th scope="col">Hotel Address</th> -->
          <th scope="col">Date</th>
          <th scope="col">Duration</th>
          <th scope="col">Booking-Date</th>
          <th scope="col">Hotel-Price</th>
          <th scope="col">Status</th>
        </tr>
        <?php

        $query = "SELECT * FROM  hotel_booking WHERE status = 'pending' ORDER BY user_Id , id";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($result)) { ?>

          <tr>
          <td><?php echo $row['id']; ?></td>
            <th scope="row"><?php echo $row['user_Id']; ?></th>
            <td><?php echo $row['Hotel_Name']; ?></td>
            <td><?php echo $row['User_Name']; ?></td>
            <td><?php echo $row['Email_Id']; ?></td>
            <td> <?php echo $row['Mobile_No']; ?></td>
            <!-- Remove the hotel address -->
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['Duration']; ?></td>
            <td><?php echo $row['created_booking']; ?></td>
            <td><?php echo $row['Price']; ?> Rs</td>

            <td>
              <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                <input type="hidden" name="fname" value="<?php echo $row['User_Name']; ?>" placeholder="fname">
                <input type="hidden" name="email" placeholder="email" value="<?php echo $row['Email_Id']; ?>">
                <input class="submitButton" type="submit" name="approve" value="Approvel"> &nbsp &nbsp <br>
                <input class="deleteButton" type="submit" name="delete" value="Delete">
              </form>
            </td>
          </tr>

        <?php } ?>
      </table>
    </div>

    <?php

    $Mobile_No = $_SESSION["phone"];
		$Hotel_Date = $_SESSION["Hotel_Date"];
		$Hotel_Name = $_SESSION["Hotel_Name"];

		$Total_room = $_SESSION["total_room"];
		$Payment_Id = $_SESSION['Payment_Id'];
		$Total_Price = $_SESSION['rate'];


    if (isset($_POST['approve'])) {
      $id = $_POST['id'];
      $fname = $_POST['fname'];
      $email = $_POST['email'];
      $select = "UPDATE hotel_booking SET status = 'Approved' WHERE id = '$id' ";
      $result = mysqli_query($conn, $select);
      Sendemail_hotel_approvel($email, $fname ,$Mobile_No, $Hotel_Date, $Hotel_Name, $Total_room, $Payment_Id, $Total_Price);
      if ($result) {
        echo "<script>alert('Your Package Approved Succesfully..!')</script>";
        header("Refresh:0.5s; url=../adminhomepage.php");
      } else {
        echo "<script>alert('Your Package Not Approved ..!')</script>";
        header("Refresh:0.5s; url=../adminhomepage.php");
      }
    }

    if (isset($_POST['delete'])) {
      $id = $_POST['id'];
      $select = "DELETE FROM hotel_booking WHERE id = '$id' ";
      $resut = mysqli_query($conn, $select);
      if ($result) {
        echo "<script>alert('Your Package Deleted Succesfully..!')</script>";
        header("Refresh:0.5s; url=../adminhomepage.php");
      } else {
        echo "<script>alert('Your Package Not Deleted ..!')</script>";
        header("Refresh:0.5s; url=../adminhomepage.php");
      }
    }
    ?>
    <div class="nav1">
      <div class="nav-part1">
        <h2 id="nav-part3">Approve list</h2>
      </div>
      <h1>the real travel</h1>
      <div class="nav-part2">
        <h3><a href="../adminhomepage.php">Home</a></h3>
        <h3><a href="../hotellist.php">hotels</a></h3>
        <h3><a href="../tourlist.php">package</a></h3>
      </div>
    </div>
    <div class="part1">

      <table>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Hotel Name</th>
          <th scope="col">User Name</th>
          <th scope="col">Email_Id</th>
          <th scope="col">Mobile-No</th>
          <th scope="col">Hotel Address</th>
          <th scope="col">Date</th>
          <th scope="col">Duration</th>
          <th scope="col">Booking-Date</th>
          <th scope="col">Hotel-Price</th>
          <th scope="col">Status</th>
        </tr>

        <?php
        $query = "SELECT * FROM  hotel_booking";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($result)) { ?>
          <tr>
            <th scope="row"><?php echo $row['user_Id']; ?></th>
            <td><?php echo $row['Hotel_Name']; ?></td>
            <td><?php echo $row['User_Name']; ?></td>
            <td><?php echo $row['Email_Id']; ?></td>
            <td> <?php echo $row['Mobile_No']; ?></td>
            <td><?php echo $row['Hotel_Address']; ?> </td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['Duration']; ?></td>
            <td><?php echo $row['created_booking']; ?></td>
            <td><?php echo $row['Price']; ?> Rs</td>
            <td class=status-cell style=color:green; font-weight:bold;><?php echo $row['Status']; ?></td>
          </tr>

          <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const statusCells = document.querySelectorAll(".status-cell");
                        statusCells.forEach(function(cell) {
                            if (cell.textContent === "Approved") {
                                // cell.style.color = "green";
                                cell.style.color = "#08fa08";
                                cell.style.fontWeight = "bold";
                            } else if (cell.textContent === "Pending") {
                                cell.style.color = "red";
                                cell.style.fontWeight = "bold";
                              } else if (cell.textContent === "Cancelled") {
                                cell.style.color = "darkorange"
                                cell.style.fontWeight = "bold";
                            }
                        });
                    });
                </script>         



        <?php } ?>
      </table>
    </div>

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