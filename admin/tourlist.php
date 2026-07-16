<?php
require_once "../config/user_auth_acces.php";
include("../config/connection.php");

// Handle DELETE securely via POST + CSRF
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $tour_id = (int)($_POST['tour_id'] ?? 0);
    if ($tour_id > 0) {
        $stmt = $conn->prepare("DELETE FROM tour_package WHERE TourPackage_Id = ?");
        $stmt->bind_param("i", $tour_id);
        if ($stmt->execute()) {
            echo "<script>alert('Data Deleted Successfully..!'); window.location.href='tourlist.php';</script>";
            exit;
        } else {
            echo "<script>alert('Not Deleted..!');</script>";
        }
        $stmt->close();
    }
}

// Fetch tour packages securely
$stmt = $conn->prepare("SELECT TourPackage_Id, Package_Name, Package_Location, Package_Features, Package_Image FROM tour_package");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package List</title>
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.18/dist/lenis.css" />
    <link rel="stylesheet" href="../css/pwd_update.css">
    <style>
        .middle4{
         padding-inline: 2vw;
        }
    </style>
</head>
<body>
   <div class="page1">
    <div class="nav">
        <div class="nav-part1">
            <h2 id="nav-part3">Package</h2>
        </div>
        <h1>The Real Travel</h1>
        <div class="nav-part2">
            <h3><a href="adminhomepage.php">Home</a></h3>
            <h3><a href="hotellist.php">Hotels</a></h3>
            <h3><a href="#">Package</a></h3>
        </div>
    </div>

    <?php 
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $imgSrc = h($row['Package_Image']);
            if (strpos($imgSrc, '../uploads/') === 0) {
                $filename = basename($imgSrc);
                $imgSrc = "../uploads/serve.php?file=" . urlencode($filename);
            }
    ?>
    <div class="middle4">
        <div class="booking">
            <div class="booking1">
                <div class="book-part1">
                    <img src="<?php echo $imgSrc; ?>" alt="img">
                </div>
                <div class="book-part2">
                    <h2><?php echo h($row['Package_Name']); ?></h2>
                    <h4><?php echo h($row['Package_Location']); ?></h4>
                </div>
            </div>
            <div class="booking2">
                <div class="book-part3"> 
                    <h5><?php echo h($row['Package_Features']); ?>....</h5>
                </div>
                <div class="book-part4"> 
                    <button><a href="update_tour.php?id=<?php echo h($row['TourPackage_Id']); ?>">Update</a></button>
                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');" style="display:inline; width:80%;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="tour_id" value="<?php echo h($row['TourPackage_Id']); ?>">
                        <input type="submit" value="Delete" style="background:none; border:none; color:white; width:100%; cursor:pointer;">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
        }
    }
    ?> 
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