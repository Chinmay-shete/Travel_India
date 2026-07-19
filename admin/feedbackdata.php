<?php
include("../config/connection.php");
error_reporting(0);

$sql = "SELECT * FROM feedback";
$result = $conn->query($sql);

if (isset($_GET['IDe'])) {
    $sql = "DELETE FROM feedback WHERE msg_Id = " . $_GET['IDe'];
    $Result = $conn->query($sql);
    if ($Result) {
        header("location:feedbackdata.php");
    } else {
        echo "Not Deleted..!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Data</title>
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
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
            <?php include("include.php"); ?>
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
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['msg_Id']; ?></td>
                            <td id="msg"><?php echo $row['massage']; ?></td>
                            <td><button><a href="feedbackdata.php?IDe=<?php echo $row['msg_Id'] ?>" style="text-decoration: none; color:red;">Delete</a></button></td>
                        </tr>
                <?php
                    }
                }
                ?>
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