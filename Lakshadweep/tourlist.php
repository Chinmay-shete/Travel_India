<?php
include("../config/feedback.php");
error_reporting(0);
$sql = "select * from tour_package ";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hotels</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <link rel="stylesheet" href="../css/pwd_update.css">
</head>

<body>
    <div class="page1">
        <div class="nav">
            <div class="nav-part1">
                <h2>packages</h2>
            </div>
            <h1>the real travel</h1>
            <div class="nav-part2">
                <h3><a href="../other/homepage.php">Home</a></h3>
                <h3><a href="tourlist.php">Packages</a></h3>
                <h3><a href="hotellist.php">Hotels</a></h3>
                <!-- <h3><a href="#contact">Contact</a></h3> -->
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
                                <img src="<?php echo $row['Package_Image']; ?>" alt="img">
                            </div>
                            <div class="book-part2">
                                <h2><?php echo $row['Package_Name']; ?></h2>
                                <h4><?php echo $row['Package_Location']; ?></h4>
                            </div>
                        </div>
                        <div class="booking2">

                            <div class="book-part3">
                                <h5><?php echo $row['Package_Features']; ?>....</h5>
                            </div> 
                            <div class="book-part4"  >
                                <button><a href="../book_files/book_form.php?Id=<?php echo $row['TourPackage_Id']; ?>">Book now</a></button>
                                <button><a href="../book_files/book_tour.php?Id=<?php echo $row['TourPackage_Id']; ?>">read more</a></button> 
                            </div>
                        </div>
                    </div>
                </div>

        <?php
            }
        }
        ?>
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