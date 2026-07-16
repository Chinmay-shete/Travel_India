<?php
require_once "../config/user_auth_acces.php";
include("../config/connection.php");

// Handle DELETE securely via POST + CSRF
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $msg_id = (int)($_POST['msg_id'] ?? 0);
    if ($msg_id > 0) {
        $stmt = $conn->prepare("DELETE FROM feedback WHERE msg_Id = ?");
        $stmt->bind_param("i", $msg_id);
        if ($stmt->execute()) {
            echo "<script>alert('Feedback Deleted Successfully..!'); window.location.href='feedbackdata.php';</script>";
            exit;
        } else {
            echo "<script>alert('Not Deleted..!');</script>";
        }
        $stmt->close();
    }
}

// Fetch feedback securely
$stmt = $conn->prepare("SELECT name, email, msg_Id, massage FROM feedback");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Data</title>
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.18/dist/lenis.css" />
    <link rel="stylesheet" href="../css/pwd_update.css">
    <style>
        * {
            font-family: aeonik;
        }

        html,
        body {
            width: 100%;
            height: 100%;
        }

        .page1 {
            width: 100%;
            min-height: 150vh;
            padding: 0 2vw;
        }
        .part1 {
            width: 100%;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            display: flex;
            border: .05vw solid white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            padding: 2vw;
        }

        th,
        td {
            padding: 1vw;
            text-align: center;
            border: .05vw solid white;
        }

        th {
            font-size: 2vw;
            font-weight: 500;
            text-transform: uppercase;
            background-color: black;
        }

        td {
            font-size: 1.3vw;
        }
 
        button {
            font-size: 1.5vw;
            background-color: transparent;
            border: none;
        }
        #msg{
            text-align: left;
        }
        @media (max-width: 600px){
          .page1{
            min-height: 50vh;
            padding: 0 3vw;
          }
          .nav{
            height: 10vh;
            padding: 0 2vh;
          }
          .nav h1 {
            display: none;
          }
          .nav-part1 h2 {
             font-size: 2.5vh; 
          }
          .nav-part2 h3{
            font-size: 2.5vh;
          }
          .part1{
            justify-content: start;
            align-items: start;
            border: 1px solid black;
            overflow-x: auto;
          }
          .part1 table{
            padding: 2vh;
            min-width: 600px;
          }
          .part1 th{
            font-size: 2.2vh;
            padding: 1.5vh;
          }
          .part1 td{
            font-size: 2vh;
            padding: 1.5vh;
          }
          button{
            font-size: 2vh;
          }
          #msg{
            max-width: 150px;
            word-wrap: break-word;
          }
        }
    </style>
</head>

<body>
    <div class="page1">
        <div class="nav">
            <div class="nav-part1">
                <h2 id="nav-part3">Feedback Data</h2>
            </div>
            <h1>The Real Travel</h1>
            <div class="nav-part2">
                <h3><a href="adminhomepage.php">Home</a></h3>
                <h3><a href="hotellist.php">Hotel</a></h3>
                <h3><a href="tourlist.php">Package</a></h3>
            </div>
        </div>

        <div class="part1">
            <table>
                <tr>
                    <th colspan="2">User Details</th>
                    <th colspan="2">Message Details</th>
                    <th rowspan="2">Action</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Email ID</th>
                    <th>Message ID</th>
                    <th>Message</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo h($row['name']); ?></td>
                            <td><?php echo h($row['email']); ?></td>
                            <td><?php echo h($row['msg_Id']); ?></td>
                            <td id="msg"><?php echo h($row['massage']); ?></td>
                            <td>
                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="msg_id" value="<?php echo h($row['msg_Id']); ?>">
                                    <button type="submit" style="text-decoration: none; color:red; cursor:pointer;">Delete</button>
                                </form>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
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
</script>
</body>

</html>
<?php
$stmt->close();
?>