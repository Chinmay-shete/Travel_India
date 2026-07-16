<?php
require_once "../config/user_auth_acces.php";
include("../config/connection.php");

// Handle delete action securely via POST + CSRF
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    if ($user_id > 0) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_Id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('Data Deleted Successfully..!'); window.location.href='user_data.php';</script>";
            exit;
        } else {
            echo "<script>alert('Not Deleted..!');</script>";
        }
        $stmt->close();
    }
}

// Fetch user data using explicit column selection
$stmt = $conn->prepare("SELECT user_Id, fname, lname, email, user_type FROM users");
$stmt->execute();
$result = $stmt->get_result();
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
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo h($row['user_Id']); ?></td>
                                <td><?php echo h($row['fname']); ?></td>
                                <td><?php echo h($row['lname']); ?></td>
                                <td><?php echo h($row['email']); ?></td>
                                <td><?php echo h($row['user_type']); ?></td>
                                <td class="action-btn">
                                    <button><a href="edit_user.php?id=<?php echo h($row['user_Id']); ?>" style="text-decoration: none; color:#08fa08;">Update</a></button>
                                </td>
                                <td class="action-btn">
                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?php echo h($row['user_Id']); ?>">
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
<?php
$stmt->close();
?>