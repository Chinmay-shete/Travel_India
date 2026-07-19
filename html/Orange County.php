<?php
include("../config/connection.php");

// echo "hello ";
// echo $City_Id = $_GET['cityId'];
 //"hello ";
 $City_Id = $_GET['cityId'];

$sql = "select * from city where City_Id = $City_Id";
$result = $conn->query($sql);

$row = mysqli_fetch_assoc($result);
$City_Name = $row['City_Name'];
// echo $City_Name;
 $City_Name;


//fetch row wise data
$queryfordata = mysqli_query($conn, "select * from create_intern_package where City ='$City_Name'");
 $rowdata = mysqli_fetch_array($queryfordata);




?>


<!-- Orange County -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orange County</title>
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <link rel="stylesheet" href="../css/new-york.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body>
    <div class="main">
    <div class="page1">
            
            <div class="nav">
            <h3 class="closeSignUp" style="align-items: center; justify-content: center;">
            <a href="../other/homepage.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="5vh" viewBox="0 0 24 24">
                            <path fill="white" fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
                          </svg>
                        </a></h3>
                    <div class="nav-part1">
                        <h5>That's my opinion</h5>
                    </div>
                    <h1>the real travel</h1>
                    <i class="ri-menu-line open"></i>
                </div>
               
                 <div class="header">
                    <h1  style="font-size: 13vw;" >Orange County</h1>
                 </div>
                 <div class="page1-part1">
                 <div class="nav">
                        <div class="nav-part1">
                            <h5>Curated hotels from <br> The Real Housewives</h5>
                        </div>
                        <h1>the real travel</h1>
                        <i class="ri-close-line close"></i>
                    </div>
                    <div class="menu-section">
                        <div class="menu">
                            <h1 class="animate-text" data-index="1">the real hotels</h1>
                            <h1 class="animate-text" data-index="2">sign in</h1>
                            <h1 class="animate-text" data-index="3">sign up</h1>
                            <h1 class="animate-text" data-index="4">about us</h1>
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
        <?php
                 if (mysqli_num_rows($queryfordata) > 0) {
                    while ($rowdata = mysqli_fetch_assoc($queryfordata)) {


           ?>


            <div class="booking">
                <div class="booking1">

                    <div class="book-part1">
                        <img src="<?php echo $rowdata['Package_Image']; ?>" alt="">
                    </div>
                    <div class="book-part2">
                        <h2><?php echo $rowdata['Package_Name'] ?></h2>
                        <h4><?php echo $rowdata['Package_Location'] ?></h4>
                    </div>
                </div>
                <div class="booking2">

                    <div class="book-part3"> 
                        <h5><?php echo $rowdata['Package_Feature'] ?></h5>
                    </div>
                    <div class="book-part4"  >
                        <button><a href="../International_book/book_form.php ?Id=<?php echo $rowdata['CIPackage_Id'] ?>">Book now</a></button>
                        <button><a href="./read_more.php?Intern_Id=<?php echo $rowdata['CIPackage_Id'] ?>">read more</a></button>
                    </div>
                </div>
            </div>
           
            <?php
                    }
                 }

            ?>

            <!-- <div class="booking">
                <div class="booking1">

                    <div class="book-part1">
                        <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66e194f2e375a6029db9ceb1_RHOBH_S11E10-12_La%20Quinta-3-topaz.avif" alt="">
                    </div>
                    <div class="book-part2">
                        <h2>"Elizabeth Vargas's" Home</h2>
                        <h4>La Quinta, California</h4>
                    </div>
                </div>
                <div class="booking2">

                    <div class="book-part3"> 
                        <h5>It’s a Tale of Two Girl’s Trips. Tamra brings her anti-Shannon weapon Alexis Bellino, along with Katie and Jenn, to her Big Bear cabin. Meanwhile, Gina, Heather, Shannon, and Emily head out to...</h5>
                    </div>
                    <div class="book-part4"  >
                        <button><a href="#">Book now</a></button>
                        <button><a href="./orange county/Elizabeth-Home.php">read more</a></button> 
                    </div>
                </div>
            </div>
            <div class="booking">
                <div class="booking1">

                    <div class="book-part1">
                        <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66f44adc4ee8a07e24c81f1f_RHOC_S18E9-10_Dawn%20Ranch_1-topaz-upscale-2000w.avif" alt="">
                    </div>
                    <div class="book-part2">
                        <h2>Dawn Ranch</h2>
                        <h4>Guerneville, California</h4>
                    </div>
                </div>
                <div class="booking2">

                    <div class="book-part3"> 
                        <h5>If only Heather could protect all of the ladies' feelings as well as she protects her clothing packed in individual sheets of white tissue paper....</h5>
                    </div>
                    <div class="book-part4"  >
                        <button><a href="#">Book now</a></button>
                        <button><a href="./orange county/Dawn-Ranch.php">read more</a></button> 
                    </div>
                </div>
            </div> -->
        </div>

        <?php
        //     }
        // }
        ?>

        <div class="page3"></div>
        <div class="lastPage1">
            <h1>Stay in the know</h1>
            <h3>Be the first to know about new <br> hotels we’ve uncovered</h3>
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
            <h5>This site features affiliate links. When you click on a  <br> link and book a trip, The Real Hotels may earn a small  <br> commission at no cost to you.</h5>
        </div>
    </div>
    <script src="https://unpkg.com/split-type"></script>
    <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="../js/Orange County.js"></script>
</body>
</html>