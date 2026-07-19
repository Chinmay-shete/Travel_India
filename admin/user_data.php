<?php
include("../config/connection.php");
error_reporting(0);

$sql = "select * from users";
$result = $conn->query($sql);

if (isset($_GET['ID'])) {
    $sql = "DELETE FROM users WHERE user_Id = " . $_GET['ID'];
    $Result = $conn->query($sql);
    if ($Result) {
        echo "<script>alert('Data Deleted Successfully..!')</script>";
        header("location:user_data.php");
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
    <title>User Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <link rel="stylesheet" href="../css/pwd_update.css">
    <style>
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
            font-family: aeonik;
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
            font-weight: 400;
            font-family: aeonik;
            text-transform: capitalize;
            border-bottom: .05vw solid white;
        }

        td {
            font-size: 1.3vw;
            border-bottom: 0.05vw solid white;
        }

        button {
            font-size: 1.5vw;
            background-color: transparent;
            border: none;
        }

        #msg {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="page1">
            <div class="nav">
                <div class="nav-part1">
                    <h2 id="nav-part3">User Data</h2>
                </div>
                <h1>The Real Travel</h1>
                <div class="nav-part2">
                    <h3><a href="adminhomepage.php">Home</a></h3>
                    <h3><a href="hotellist.php">Hotels</a></h3>
                    <h3><a href="tourlist.php">Package</a></h3>
                </div>
            </div>
            <div class="part1">


                <table>
                    <tr>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email ID</th>
                        <th>User Type</th>
                        <th colspan="2">Action</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <td><?php echo $row['user_Id']; ?></td>
                                <td><?php echo $row['fname']; ?></td>
                                <td><?php echo $row['lname']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['user_type']; ?></td>
                                <td class="action-btn"><button><a href="edit_user.php?id=<?php echo $row['user_Id'] ?>" style="text-decoration: none; color:#08fa08;">Update</a></button></td>
                                <td class="action-btn"><button><a href="user_data.php?ID=<?php echo $row['user_Id'] ?>" style="text-decoration: none; color:red;">Delete</a></button></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </table>

            </div>
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