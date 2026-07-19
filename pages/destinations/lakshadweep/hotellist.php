<?php
include("../../../../../config/feedback.php");
error_reporting(0);
$sql = "select * from create_hotel ";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hotels</title>
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.18/dist/lenis.css" />
    <link rel="stylesheet" href="../../../assets/css/pwd_update.css">
</head>

<body>
    <div class="page1">
        <div class="nav">
            <div class="nav-part1">
                <h2>hotels</h2>
            </div>
            <h1>the real travel</h1>
            <div class="nav-part2">
                <h3><a href="../../../../../homepage.php">Home</a></h3>
                <h3><a href="tourlist.php">Packages</a></h3>
                <h3><a href="hotellist.php">Hotels</a></h3> 
            </div>
        </div>

        <?php
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

        ?>
                <div class="middle4">
                    <div class="booking">
                        <div class="booking1">

                            <div class="book-part1">
                                <img src="<?php echo $row['Hotel_Image']; ?>" alt="img">
                            </div>
                            <div class="book-part2">
                                <h2><?php echo $row['Hotel_Name']; ?></h2>
                                <h4><?php echo $row['Hotel_Address']; ?></h4>
                            </div>
                        </div>
                        <div class="booking2">

                            <div class="book-part3">
                                <h5><?php echo $row['amenities']; ?>....</h5>
                            </div>
                            <div class="book-part4">
                                <button><a href="../../../bookings/lakshadweep/../../../bookings/lakshadweep/hotel_form.php?id=<?php echo $row['Hotel_Id']; ?>">Book Now</a></button>
                                <button><a href="../book_files/book_hotel.php?id=<?php echo $row['Hotel_Id']; ?>">read more</a></button>
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