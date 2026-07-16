<?php
require_once "../config/user_guard.php";
include("../config/connection.php");

$hotel_id = (int)($_GET['id'] ?? 0);

if ($hotel_id <= 0) {
    echo "Invalid request..!";
    exit;
}

// Fetch hotel securely
$stmt = $conn->prepare("SELECT Hotel_Id, Hotel_Name, Hotel_Address, PhoneNo, email, NumberOfRooms, PriceOfRoom, amenities, Hotel_Image FROM create_hotel WHERE Hotel_Id = ? LIMIT 1");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Hotel data not found..!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <link rel="stylesheet" href="../css/secondPage.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body>
    <div class="main">
        <div class="page1">
            <div class="backimg">
                <img src="<?php echo h($data['Hotel_Image']); ?>" alt="Hotel Image">
            </div>
            <div class="nav">
                <div class="nav-part1">
                    <h5>Curated hotels from <br> The Real Housewives</h5>
                </div>
                <h2><?php echo h($data['Hotel_Name']); ?></h2>
                <i class="ri-menu-line open"></i>
            </div>
            <div class="middle">
                <h1><?php echo h($data['Hotel_Name']); ?></h1>
                <br><h4><?php echo h($data['PriceOfRoom']); ?> Rs</h4>     
            </div>
            <div class="header">
                <h4> Real Housewives of Orange County <br> season 18  | episode(s) 14-16</h4>
                <div class="button">
                    <button><a href="../Lakshadweep/tourlist.php" class="buy-button">All Packages</a></button>
                </div>
            </div>
            <div class="page1-part1">
                <div class="nav">
                    <div class="nav-part1">
                        <h5>Curated hotels from <br> The Real Housewives</h5>
                    </div>
                    <i class="ri-close-line close"></i>
                </div>
                <div class="menu-section">
                    <div class="menu">
                        <h1 class="animate-text" data-index="1">the real Travel</h1>
                        <h1 class="animate-text" data-index="2"><a href="../homepage.php">home</a></h1>
                        <h1 class="animate-text" data-index="3"><a href="../Lakshadweep/tourlist.php">TourList</a></h1>
                        <h1 class="animate-text" data-index="4"><a href="book_tour.php">Packages</a></h1>
                        <h1 class="animate-text" data-index="5">get in touch</h1>
                    </div>
                    <div class="about">
                        <div class="text">
                            <h6><i class="ri-arrow-right-line"></i>instagram</h6>
                            <h6><i class="ri-arrow-right-line"></i>facebook</h6>
                            <h6><i class="ri-arrow-right-line"></i>e-mail</h6>
                        </div>
                        <div class="images">
                             <img src="https://cdn.prod.website-files.com/66bdbd95953ed41b630aa4ba/66bf66f79495e6d6e4419b14_bas-van-den-eijkhof-LchLjOB-XvE-unsplash.avif" alt="">
                             <img src="https://cdn.prod.website-files.com/66bdbd95953ed41b630aa4ba/66cf2d46bf18c7280d2f49ac_menu-2.avif" alt="">
                             <img src="https://cdn.prod.website-files.com/66bdbd95953ed41b630aa4ba/66cf2d4579c54a88014bc939_menu-1.avif" alt="">
                             <img src="https://cdn.prod.website-files.com/66bdbd95953ed41b630aa4ba/66bf66f712f954b018e2680f_getty-images-jDdUlr0UBlw-unsplash.avif" alt="">
                             <img src="https://cdn.prod.website-files.com/66bdbd95953ed41b630aa4ba/66bf66f7bc9a2f5fe688b26d_sj-objio-0WUa239Wm5s-unsplash.avif" alt="">           
                        </div>
                    </div>
                </div>
             </div>
        </div>
        <div class="page2">
           <div class="text">
            <h1>Stay in absolute luxury with The Real Travel collections. Experience high class accommodations.</h1>
           </div>
           <div class="middle2">
            <div class="middle2-part1">
                <img src="<?php echo h($data['Hotel_Image']); ?>" alt="Hotel image">
            </div>
            <div class="middle2-part2">
                <h3><?php echo h($data['amenities']); ?></h3>
            </div>
           </div>
           <div class="middle3">
            <div class="middle3-part1">
                <h3>Get ready to experience top-notch services. Book now to secure your stay!</h3>
                <div class="button1">
                     <button><a href="book_hotel_form.php?pass_hotel_id=<?php echo h($data['Hotel_Id']); ?>" class="buy-button">Book Now</a></button>
                </div>  
            </div>
            <div class="middle3-part2">
                <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/672d004a81c785b1a6d9cf4e_RHOC_S18E14-16_The%20May%20Fair%20Hotel_3-topaz-upscale-2000w.avif" alt="">
            </div>
           </div>

           <div class="middle5">
            <div class="middle5-part1">
                <img src="https://cdn.prod.website-files.com/66de71cc2bd368e4376f06b0/66debb8811be47c3a6471803_hikari_img_02.webp" alt="">
            </div>
            <div class="middle5-part2">
                <h3>info</h3>
                <h3>We offer carefully curated experiences ensuring absolute satisfaction. Our selected hotels feature top-class ratings.</h3>
                <div class="middle5-part3">
                    <img src="<?php echo h($data['Hotel_Image']); ?>" alt="Hotel Image">
                </div>
                <h3>details</h3>
                <div class="details">
                    <div class="detail1">
                        <div class="detail2"><h3>Hotel Name</h3></div>
                        <div class="detail3"><h3><?php echo h($data['Hotel_Name']); ?></h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>location</h3></div>
                        <div class="detail3"><h3><?php echo h($data['Hotel_Address']); ?></h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>Hotel phone no</h3></div>
                        <div class="detail3"><h3><?php echo h($data['PhoneNo']); ?></h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>price</h3></div>
                        <div class="detail3"><h3><?php echo h($data['PriceOfRoom']); ?> Rs</h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>year</h3></div>
                        <div class="detail3"><h3> 2024</h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>guide</h3></div>
                        <div class="detail3"><h3>available</h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>car</h3></div>
                        <div class="detail3"><h3>available</h3></div> 
                    </div>
                </div>
            </div>
           </div>
        </div>
    </div>
    <script src="https://unpkg.com/split-type"></script>
    <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="/js/secondPage.js"></script>
</body>
</html>