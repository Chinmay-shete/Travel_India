<?php
include("../config/connection.php");

if(isset($_GET['id'])){
    $sql = "select * from create_hotel where Hotel_Id = " .  $_GET['id'];
    $result = $conn->query($sql);
    $data = mysqli_fetch_assoc($result);
    if(!$data){
        echo "Data not found..!";
        exit;
    }
} else {
    echo "Invalid request..!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Details</title>
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <link rel="stylesheet" href="../css/secondPage.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body>
    <div class="main">
        <div class="page1">
            <div class="backimg">
            <img src="<?php echo $data['Hotel_Image']; ?>" alt="Package 1">
            </div>
            <div class="nav">
                <div class="nav-part1">
                    <h5>Curated hotels from <br> The Real Housewives</h5>
                </div>
                <h2><?php echo $data['Hotel_Name']; ?></h2>
                <i class="ri-menu-line open"></i>
            </div>
            <div class="middle">
            <h1><?php echo $data['Hotel_Name']; ?></h1>
                <br><h4><?php echo $data['PriceOfRoom']; ?>  Rs</h4>     <!-- //here want a location -->    
               
             </div>
             <div class="header">
                <h4> Real Housewives of Orange County <br> season 18  | episode(s) 14-16</h4>
                <div class="button">
                <button><a href="../Lakshadweep/tourlist.php" class="buy-button">All Packages</a></button>
                    <!-- <button>read more</button> -->
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
                        <h1 class="animate-text" data-index="2"><a href="/homepage.php">home</a></h1>
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
             <!-- <div class="sign-in">
                <form action="">
                    
                </form>
             </div> -->
        </div>
        <div class="page2">
           <div class="text">
            <h1>Some intrepid reporter needs to do an in-depth
                exposé on what exactly is wrong with Shannon
                Storms Beador’s digestive system. This woman has
                been trying to poop for years. We have been through
                so many trips to Dr. Moon with her, and so many
                enemas.</h1>
           </div>
           <div class="middle2">
            <div class="middle2-part1">
            <img src="<?php echo $data['Hotel_Image']; ?>" alt="Package 1">
            </div>
            <div class="middle2-part2">
                <h3><?php echo $data['amenities']; ?></h3>
            </div>
           </div>
           <div class="middle3">
            <div class="middle3-part1">
                <h3><?php echo $data['Package_Details']; ?></h3>
                    <div class="button1">
                        <!-- <button><a href="book_form_read.php?passid=<?php //echo $data['TourPackage_Id'] ?>" class="buy-button">Book Now</a></button> -->
                    
                        <!-- <button>read more</button> -->
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

                <h3>Hikari Villa is a luxury retreat in
                    Okinawa designed by Saisei.
                    Completed in 2021, it seamlessly
                    integrates modern amenities with
                    traditional Japanese design
                    elements, creating a serene and
                    light-filled environment. The villa’s
                    design prioritizes sustainability
                    while offering stunning views of
                    the coastal landscape.</h3>


           <div class="middle5-part3">
           <img src="<?php echo $data['Hotel_Image']; ?>" alt="Package 1">
                </div>
                <h3>details</h3>
                <div class="details">
                    <div class="detail1">
                        <div class="detail2"><h3>Package Name</h3></div>
                        <div class="detail3"><h3><?php echo $data['Hotel_Name']; ?></h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>location</h3></div>
                        <div class="detail3"><h3><?php echo $data['Hotel_Address']; ?></h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>Hotel phone no</h3></div>
                        <div class="detail3"><h3> <?php echo $data['PhoneNo']; ?></h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>price</h3></div>
                        <div class="detail3"><h3><?php echo $data['PriceOfRoom']; ?>Rs</h3></div> 
                    </div>
                    <!-- <div class="detail1">
                        <div class="detail2"><h3>Contact No </h3></div>
                        <div class="detail3"><h3><?php echo $data['Phone'] ?> </h3></div> 
                    </div> -->
                    <!-- <div class="detail1">
                        <div class="detail2"><h3>city</h3></div>
                        <div class="detail3"><h3><?php //echo $data['City'] ?> </h3></div> 
                    </div> -->
                    <div class="detail1">
                        <div class="detail2"><h3>year</h3></div>
                        <div class="detail3"><h3> 2024</h3></div> 
                    </div>
                    <!-- <div class="detail1">
                        <div class="detail2"><h3>location</h3></div>
                        <div class="detail3"><h3>pune</h3></div> 
                    </div> -->
                    
                    <div class="detail1">
                        <div class="detail2"><h3>guide</h3></div>
                        <div class="detail3"><h3>available</h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>car</h3></div>
                        <div class="detail3"><h3>available</h3></div> 
                    </div>
                    <!-- <div class="detail1">
                        <div class="detail2"><h3>bathroom</h3></div>
                        <div class="detail3"><h3>available</h3></div> 
                    </div> -->
                </div>
            </div>
           </div>



           <div class="middle4">
            <div class="booking">
                <div class="book-part1">
                    <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/672d0043e9147b9cab9aadaf_RHOC_S18E14-16_The%20May%20Fair%20Hotel_1-topaz-upscale-2000w.avif" alt="">
                </div>
                <div class="book-part2">
                    <h2>The May Fair Hotel</h2>
                    <h4>London, England</h4>
                </div>
                <div class="book-part3"> 
                    <h5>Some intrepid reporter needs to do an in-depth exposé on what exactly is wrong with Shannon Storms Beador’s digestive system. This woman has been trying to poop for years. We have been</h5>
                </div>
                <div class="book-part4"> 
                    <button>Book now</button>
                    <button>read more</button>
                </div>
            </div>
            <div class="booking">
                <div class="book-part1">
                    <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66e194f2e375a6029db9ceb1_RHOBH_S11E10-12_La%20Quinta-3-topaz.avif" alt="">
                </div>
                <div class="book-part2">
                    <h2>"Elizabeth Vargas's" Home</h2>
                    <h4>La Quinta, California</h4>
                </div>
                <div class="book-part3"> 
                    <h5>It’s a Tale of Two Girl’s Trips. Tamra brings her anti-Shannon weapon Alexis Bellino, along with Katie and Jenn, to her Big Bear cabin. Meanwhile, Gina, Heather, Shannon, and Emily head out to...</h5>
                </div>
                <div class="book-part4"> 
                    <button>Book now</button>
                    <button>read more</button>
                </div>
            </div>
            <div class="booking">
                <div class="book-part1">
                    <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66f44adc4ee8a07e24c81f1f_RHOC_S18E9-10_Dawn%20Ranch_1-topaz-upscale-2000w.avif" alt="">
                </div>
                <div class="book-part2">
                    <h2>Dawn Ranch</h2>
                    <h4>Guerneville, California</h4>
                </div>
                <div class="book-part3"> 
                    <h5>If only Heather could protect all of the ladies' feelings as well as she protects her clothing packed in individual sheets of white tissue paper....</h5>
                </div>
                <div class="book-part4"> 
                    <button>Book now</button>
                    <button>read more</button>
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