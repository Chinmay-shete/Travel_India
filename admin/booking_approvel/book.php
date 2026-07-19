<?php
include("../../config/connection.php");
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

function Sendemail_approvel($email, $fname, $Mobile_No, $Package_Date, $Package_Name, $Package_Duration, $Payment_Id, $Total_Price)
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
		$email = $_POST['email'];
		$mail->addAddress($email);
		$mail->addReplyTo('travelindia9500@gmail.com', 'Information');

		$mail->isHTML(true);
		$mail->Subject = 'Congratulation Your Tour Package has been Approved..!';
		$mail->Body    = "<h3>Welcome " . $fname . " in The Real-Travel.com</h3><h3>Your Booking Package has been Succesfully Approved..!</h3><h3>Please check Your Account..!</h3><br>
		        <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                
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
                border: 1px solid #fff;
            }
            .payment-slip table th {
                background-color: #f4f4f4;
                font-weight: bold;
                border: 1px solid #fff;
            }
            .payment-slip table tr:nth-child(even) {
                background-color: #f9f9f9;
                border: 1px solid #fff;
            }
            .payment-slip p {
                text-align: center;
                color: #555;
                margin-top: 10px;
                border: 1px solid #fff;
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
                    <th>Tour Date</th>
                    <td>$Package_Date</td>
                </tr>
                <tr>
                    <th>Package Name</th>
                    <td>$Package_Name</td>
                </tr>
				<tr>
                    <th>Package Duration</th>
                    <td>$Package_Duration</td>
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
	<title>package Record</title>
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
			justify-content: center;
			display: flex;

			border: .2vw solid black;
		}

		table {
			font-family: twl;
			width: 100%;
			border-collapse: collapse;
			padding: 2vw;
		}

		th,
		td {
			padding: 1vw;
			text-align: center;
			border: 1px solid #fff;
		}


		th {
			font-size: 1.2vw;
			font-weight: 400;
			text-transform: capitalize;
			font-family: regular;
			border-bottom: .2vw solid #fff;
		}

		td {
			font-size: 1.2vw;
			border-bottom: 0.2vw solid #fff;
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
			padding: 0.5vw;
			background-color: black;
			color: #08fa08;
			border: none;
			cursor: pointer;
			margin-bottom: 1vw;
			font-size: 1.2vw;
		}

		.deleteButton{
			padding: 0.5vw;
			background-color: black;
			color: red;
			border: none;
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
					<th scope="col">Id</th>
					<th scope="col">User_Id</th>
					<th scope="col">Package Name</th>
					<th scope="col">User Name</th>
					<th scope="col">Email_Id</th>
					<th scope="col">Mobile-No</th>
					<th scope="col">Package-Type</th>
					<th scope="col">Tour-Date</th>
					<th scope="col">Package-Duration</th>
					<th scope="col">Booking-Date</th>
					<th scope="col">Package-Price</th>
					<th scope="col">Status</th>
				</tr>
				<?php
				$query = "SELECT * FROM  booking WHERE status = 'pending' ORDER BY user_Id , id";
				$result = mysqli_query($conn, $query);
				while ($row = mysqli_fetch_array($result)) { ?>
					<tr>
						<td scope="row"><?php echo $row['id']; ?></td>
						<td><?php echo $row['user_Id']; ?></td>
						<td><?php echo $row['Package_Name']; ?></td>
						<td><?php echo $row['User_Name']; ?></td>
						<td><?php echo $row['Email_Id']; ?></td>
						<td><?php echo $row['Mobile_No']; ?></td>
						<td><?php echo $row['Package_Type']; ?></td>
						<td><?php echo $row['Tour_Date']; ?></td>
						<td><?php echo $row['Package_Duration']; ?></td>
						<td><?php echo $row['Booking_Date']; ?></td>
						<td><?php echo $row['Package_Price']; ?> Rs</td>
						<td>
							<form action="" method="POST">
								<input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
								<input type="hidden" name="fname" value="<?php echo $row['User_Name']; ?>">
								<input type="hidden" name="email" value="<?php echo $row['Email_Id']; ?>">
								<input class="submitButton" type="submit" name="approve" value="Approvel">
								<input class="deleteButton" type="submit" name="delete" value="Delete">
							</form>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>

		<?php

		$Mobile_No = $_SESSION["phone"];
		$Package_Date = $_SESSION["Package_Date"];
		$Package_Name = $_SESSION["Package_Name"];
		$Package_Duration = $_SESSION["Package_Duration"];
		$Payment_Id = $_SESSION['Payment_Id'];
		$Total_Price = $_SESSION['rate'];



		if (isset($_POST['approve'])) {
			$id = $_POST['id'];
			$fname = $_POST['fname'];
			$email = $_POST['email'];
			$select = "UPDATE booking SET status = 'Approved' WHERE id = '$id' ";
			$result = mysqli_query($conn, $select);


			if ($result) {
				Sendemail_approvel($email, $fname, $Mobile_No, $Package_Date, $Package_Name, $Package_Duration, $Payment_Id, $Total_Price);
				echo "<script>alert('Your Package Approved Succesfully..!')</script>";
				header("Refresh:0.5s; url=../adminhomepage.php");
			} else {
				echo "<script>alert('Your Package Not Approved ..!')</script>";
				header("Refresh:0.5s; url=../adminhomepage.php");
			}
		}

		if (isset($_POST['delete'])) {
			$id = $_POST['id'];
			$select = "DELETE FROM booking WHERE id = '$id' ";
			$result = mysqli_query($conn, $select);

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
					<th scope="col">Id</th>
					<th scope="col">Package Name</th>
					<th scope="col">User Name</th>
					<th scope="col">Email_Id</th>
					<th scope="col">Mobile-No</th>
					<th scope="col">Package-Type</th>
					<th scope="col">Tour-Date</th>
					<th scope="col">Package-Duration</th>
					<th scope="col">Booking-Date</th>
					<th scope="col">Package-Price</th>
					<th scope="col">Status</th>
				</tr>
				<?php
				$query = "SELECT * FROM  booking";
				$result = mysqli_query($conn, $query);
				while ($row = mysqli_fetch_array($result)) { ?>
					<tr>
						<td scope="row"><?php echo $row['user_Id']; ?></td>
						<td><?php echo $row['Package_Name']; ?></td>
						<td><?php echo $row['User_Name']; ?></td>
						<td><?php echo $row['Email_Id']; ?></td>
						<td><?php echo $row['Mobile_No']; ?></td>
						<td><?php echo $row['Package_Type']; ?></td>
						<td><?php echo $row['Tour_Date']; ?></td>
						<td><?php echo $row['Package_Duration']; ?></td>
						<td><?php echo $row['Booking_Date']; ?></td>
						<td><?php echo $row['Package_Price']; ?> Rs</td>
						<td  class=status-cell style="color:green; font-weight:bold;"><?php echo $row['Status']; ?></td>
					</tr>

					<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const statusCells = document.querySelectorAll(".status-cell");
                        statusCells.forEach(function(cell) {
                            if (cell.textContent === "Approved") {
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