<?php
require_once "../../config/user_auth_acces.php";
include("../../config/connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function Sendemail_approvel($email, $fname, $Mobile_No, $Package_Date, $Package_Name, $Package_Duration, $Payment_Id, $Total_Price)
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
		$mail->addAddress($email);
		$mail->addReplyTo(MAIL_FROM_EMAIL, 'Information');

		$mail->isHTML(true);
		$mail->Subject = 'Congratulations! Your Tour Package has been Approved..!';
		$mail->Body    = "<h3>Welcome " . h($fname) . " to The Real-Travel.com</h3>
                          <h3>Your Booking Package has been Successfully Approved..!</h3>
                          <h3>Please check Your Account..!</h3><br>
                          <html>
                          <head>
                              <style>
                                  body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                                  .payment-slip { width: 60%; margin: 50px auto; padding: 20px; background: #fff; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
                                  .payment-slip h1 { text-align: center; color: #333; margin-bottom: 20px; }
                                  .payment-slip table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                                  .payment-slip table th, .payment-slip table td { text-align: left; padding: 10px; border: 1px solid #ddd; }
                                  .payment-slip table th { background-color: #f4f4f4; font-weight: bold; }
                                  .payment-slip p { text-align: center; color: #555; margin-top: 10px; }
                              </style>
                          </head>
                          <body>
                              <div class='payment-slip'>
                                  <h1>Payment Received</h1>
                                  <table>
                                      <tr><th>Name</th><td>" . h($fname) . "</td></tr>
                                      <tr><th>Email</th><td>" . h($email) . "</td></tr>
                                      <tr><th>Mobile No</th><td>" . h($Mobile_No) . "</td></tr>
                                      <tr><th>Tour Date</th><td>" . h($Package_Date) . "</td></tr>
                                      <tr><th>Package Name</th><td>" . h($Package_Name) . "</td></tr>
                                      <tr><th>Package Duration</th><td>" . h($Package_Duration) . "</td></tr>
                                      <tr><th>Payment Id</th><td>" . h($Payment_Id) . "</td></tr>
                                      <tr><th>Total Price</th><td>" . h($Total_Price) . " Rs</td></tr>
                                      <tr><th>Payment Status</th><td><b style='color:green;'>Success</b></td></tr>
                                  </table>
                                  <p><b>Thank you for booking with us!</b></p>
                              </div>
                          </body>
                          </html>";

		$mail->send();
	} catch (Exception $e) {
		error_log("PHPMailer error in booking approval: " . $mail->ErrorInfo);
	}
}

if (isset($_POST['approve'])) {
	$id = (int)($_POST['id'] ?? 0);
	$fname = trim($_POST['fname'] ?? '');
	$email = trim($_POST['email'] ?? '');

	// Fetch booking details securely from DB to pass to email template
	$stmt_details = $conn->prepare("SELECT Mobile_No, Tour_Date, Package_Name, Package_Duration, Package_Price FROM booking WHERE id = ? LIMIT 1");
	$stmt_details->bind_param("i", $id);
	$stmt_details->execute();
	$res_details = $stmt_details->get_result();
	$row_details = $res_details->fetch_assoc();
	$stmt_details->close();

	if ($row_details) {
		$Mobile_No        = $row_details['Mobile_No'];
		$Package_Date     = $row_details['Tour_Date'];
		$Package_Name     = $row_details['Package_Name'];
		$Package_Duration = $row_details['Package_Duration'];
		$Total_Price      = $row_details['Package_Price'];
		$Payment_Id       = "PAY-" . bin2hex(random_bytes(6)); // Fake/DB Payment ID fallback

		$stmt_update = $conn->prepare("UPDATE booking SET status = 'Approved' WHERE id = ?");
		$stmt_update->bind_param("i", $id);
		
		if ($stmt_update->execute()) {
			Sendemail_approvel($email, $fname, $Mobile_No, $Package_Date, $Package_Name, $Package_Duration, $Payment_Id, $Total_Price);
			echo "<script>alert('Your Package Approved Successfully..!'); window.location.href='book.php';</script>";
            exit;
		} else {
			echo "<script>alert('Your Package Not Approved ..!');</script>";
		}
		$stmt_update->close();
	}
}

if (isset($_POST['delete'])) {
	$id = (int)($_POST['id'] ?? 0);
	$stmt_delete = $conn->prepare("DELETE FROM booking WHERE id = ?");
	$stmt_delete->bind_param("i", $id);
	
	if ($stmt_delete->execute()) {
		echo "<script>alert('Your Package Deleted Successfully..!'); window.location.href='book.php';</script>";
        exit;
	} else {
		echo "<script>alert('Your Package Not Deleted ..!');</script>";
	}
	$stmt_delete->close();
}

// Fetch pending list securely
$pending_stmt = $conn->prepare("SELECT id, user_Id, Package_Name, User_Name, Email_Id, Mobile_No, Package_Type, Tour_Date, Package_Duration, Booking_Date, Package_Price FROM booking WHERE status = 'pending' ORDER BY user_Id, id");
$pending_stmt->execute();
$pending_result = $pending_stmt->get_result();

// Fetch approved list securely
$all_stmt = $conn->prepare("SELECT user_Id, Package_Name, User_Name, Email_Id, Mobile_No, Package_Type, Tour_Date, Package_Duration, Booking_Date, Package_Price, Status FROM booking");
$all_stmt->execute();
$all_result = $all_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>package Record</title>
	<link rel="stylesheet" href="https://unpkg.com/lenis@1.1.18/dist/lenis.css" />
	<link rel="stylesheet" href="../../../assets/css/pwd_update.css">
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
				while ($row = $pending_result->fetch_assoc()) { ?>
					<tr>
						<td scope="row"><?php echo h($row['id']); ?></td>
						<td><?php echo h($row['user_Id']); ?></td>
						<td><?php echo h($row['Package_Name']); ?></td>
						<td><?php echo h($row['User_Name']); ?></td>
						<td><?php echo h($row['Email_Id']); ?></td>
						<td><?php echo h($row['Mobile_No']); ?></td>
						<td><?php echo h($row['Package_Type']); ?></td>
						<td><?php echo h($row['Tour_Date']); ?></td>
						<td><?php echo h($row['Package_Duration']); ?></td>
						<td><?php echo h($row['Booking_Date']); ?></td>
						<td><?php echo h($row['Package_Price']); ?> Rs</td>
						<td>
							<form action="" method="POST">
                                <?php echo csrf_field(); ?>
								<input type="hidden" name="id" value="<?php echo h($row['id']); ?>" />
								<input type="hidden" name="fname" value="<?php echo h($row['User_Name']); ?>">
								<input type="hidden" name="email" value="<?php echo h($row['Email_Id']); ?>">
								<input class="submitButton" type="submit" name="approve" value="Approvel">
								<input class="deleteButton" type="submit" name="delete" value="Delete">
							</form>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>

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
				while ($row = $all_result->fetch_assoc()) { ?>
					<tr>
						<td scope="row"><?php echo h($row['user_Id']); ?></td>
						<td><?php echo h($row['Package_Name']); ?></td>
						<td><?php echo h($row['User_Name']); ?></td>
						<td><?php echo h($row['Email_Id']); ?></td>
						<td><?php echo h($row['Mobile_No']); ?></td>
						<td><?php echo h($row['Package_Type']); ?></td>
						<td><?php echo h($row['Tour_Date']); ?></td>
						<td><?php echo h($row['Package_Duration']); ?></td>
						<td><?php echo h($row['Booking_Date']); ?></td>
						<td><?php echo h($row['Package_Price']); ?> Rs</td>
						<td class="status-cell"><?php echo h($row['Status']); ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
	<script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>
	<script>
		const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        });
        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);
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
</body>

</html>
<?php
$pending_stmt->close();
$all_stmt->close();
?>