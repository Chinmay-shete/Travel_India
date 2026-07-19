<?php
include("../config/connection.php");
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>the real travel</title>
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.18/dist/lenis.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
     
    <div class="main">
        <div class="page1">
            
        <div class="nav">
                <div class="nav-part1">
                    <h5>Curated hotels from <br> The Real Housewives</h5>
                </div>
                <i class="ri-menu-line open"></i>
            </div>
            <div class="middle">
                <h2>Money can’t buy you class,<br> but it can buy you a vacation.</h2>
                <br>
                <h4>Check in to the iconic hotels and resorts <br> featured on The Real Housewives. </h4>
                <div class="backSide">
                    <div class="back-img1 backimg">
                        <img src="https://cdn.prod.website-files.com/66bdbd95953ed41b630aa4ba/66c658c0fc8c1bc4501bcb52_sunset.avif" alt="">
                    </div>
                    <div class="back-img2 backimg">
                        <img src="https://cdn.prod.website-files.com/66bdbd95953ed41b630aa4ba/66d6c595c70f3242c59d7e4d_Hero%20Visual-1.avif" alt="">
                    </div>
                    <div class="back-img3 backimg">
                        <img src="https://cdn.prod.website-files.com/66bdbd95953ed41b630aa4ba/66d6c5951dac7641e38ec4f8_Hero%20Visual.avif" alt="">
                    </div>
                    <div class="back-img4 backimg">
                        <img src="https://cdn.prod.website-files.com/66bdbd95953ed41b630aa4ba/66d6c59597a8522719a2cc1b_Hero%20Visual-3.avif" alt="">
                    </div>
                    <div class="back-img5 backimg">
                        <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/6786e90dbb20d6b0311567bf_RHOP_S9E12-14_The%20Buenaventura%20Golf%20%26%20Beach%20Resort_1-topaz-upscale-2000w.avif" alt="">
                    </div>
                </div>
            </div>
            <div class="header">
                <h1>The Real Travel</h1>
            </div>
             <div class="page1-part1">
                <div class="nav">
                    <div class="nav-part1">
                        <h5>Curated hotels from <br> The Real Housewives</h5>
                    </div>
                    <h3 id="account" style="color:chartreuse;"><a href="profile/profile.php"><?php $_SESSION["email"]; ?></a> </h3>
                    <i class="ri-close-line close"></i>
                </div>
               
                <?php

                $email = $_SESSION["email"];
                $sql = "select * from users where email = '$email'";
                $result = $conn->query($sql); 
                if ($result->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?> 
                <div class="menu-section">
                    <div class="menu">
                        <h1 class="animate-text" data-index="1"><a href="../profile/profile.php?get_Id=<?php echo $row['user_Id'] ?>"> Your Account </a> </h1>
                        <h1 class="animate-text signIn" data-index="2" id="a"><a href="../Lakshadweep/tourlist.php"> Packages</a></h1>
                        <h1 class="animate-text sign" data-index="3" id="a"><a href="Book_data.php">Booking</a></h1>
                        <h1 class="animate-text" data-index="4" id="a"><a href="../index.php">logout</a></h1>
                        <h1 class="animate-text" data-index="5"><a href="../Get_in_Touch/contact.php">get in touch</a></h1>
                    </div>


                    <!-- <?php
                           }
                        }
                    ?> -->


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
            <div class="video">
                <video autoplay muted loop src=" https://www.fourseasons.com/content/dam/fourseasons/video/FSH/FSH_festive_ambient_shorter.mp4"></video>
            </div>
            <div class="text">
                <h6>Our collections span the globe, offering you <br>
                    the chance to stay in the luxurious, beautiful, <br>
                    and bizarre accommodations you see on The <br>
                    Real Housewives. Get the gang together in the family <br>
                    van and prepare to squabble over who <br>
                    gets their own room.</h6>
            </div>
        </div>
        <div class="page3">
            <h5>browse hotels by <br>
                your favorite series</h5> 
           <?php
                $sqlquery = "select * from city";
                $queryresult = $conn->query($sqlquery);

                $datarow = mysqli_fetch_assoc($queryresult); 
            ?>
            <div class="pageImg">
                    <div class="pageImg1">
                        <div class="pageImg-part1">
                            <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66daf6d5b7409c4b64695c92_1_SF-1-topaz.avif" alt="">
                        </div>
                    </div>
                    <div class="pageImg2">
                        <div class="pageImg-part2">
                            <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66db01e4d0ef5ace65b5f1c0_RHONY_S6E9_Berkshires-2-topaz.avif" alt="">
                        </div>
                        <div class="pageImg-part3">
                            <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66dafd5eb8c30c4d8a394aa5_RHONY_S4E7-10_Morocco-2-topaz.avif" alt="">
                        </div>
                    </div>
                </div>


            <div class="location">
                <h1><a href="../html/Orange County.php?cityId=100">Orange County</a></h1>
               
                <h1><a href="../html/new-york.php?cityId=200">new york</a></h1>
                
                <h1><a href="../html/Atlanta.php?cityId=300">Atlanta</a></h1>
                <h1><a href="../html/New Jersey.php?cityId=400">New Jersey</a></h1>
                <h1><a href="../html/Dallas.php?cityId=500">Dallas</a></h1>
                <h1><a href="../html/Salt Lake City.php?cityId=600">Salt Lake City</a></h1> 
            </div> 

        </div> 
         
        <div class="lastPage1">
            <h1>Stay in the know</h1>
            <h3>Be the first to know about new hotels we’ve uncovered</h3>
            <form action="">
                <input type="email" name="" id="" placeholder="EMAIL ADDRESS">
                <button>&rarr;</button>
            </form>
            <div class="lastPage2">
                <div class="last-part1">
                    <div class="last-part11">
                        <h3>map</h3>
                        <h3>Series </h3>
                        <h3>About</h3>
                    </div>
                    <div class="last-part11">
                        <h3>submit</h3>
                        <h3>press</h3>
                        <h3>contact</h3>
                    </div>
                </div>
                <div class="last-part2">
                    <div class="last-part11">
                        <h3>credits</h3>
                        <h3>accessibility </h3>
                        <h3>privacy</h3>
                    </div>
                    <div class="last-part11">
                        <h3>facebook</h3>
                        <h3>instagram</h3>
                        <h3>1ax consulting</h3>
                    </div>
                </div>

            </div>
            <h5>This site features affiliate links. When you click on a link and book a trip,<br> The Real Hotels may earn a small commission at no cost to you.</h5>
        </div>
        
    </div>
    <script src="https://unpkg.com/split-type"></script>
    
    <script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
   
     <script src="../js/script.js"></script>
</body>
</html>