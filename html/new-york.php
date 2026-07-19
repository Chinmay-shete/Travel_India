<?php
include("../config/connection.php");




//   echo "hello ";
 $City_Id = $_GET['cityId'];

  $sql = "select * from city where City_Id = $City_Id";
  $result = $conn->query($sql);

  $row = mysqli_fetch_assoc($result);
  $City_Name = $row['City_Name'];
   $City_Name;


  //fetch row wise data
  $queryfordata = mysqli_query($conn, "select * from create_intern_package where City ='$City_Name'");
   $rowdata = mysqli_fetch_array($queryfordata);
  



//   if ($result->num_rows > 0) {
//     while ($row = mysqli_fetch_assoc($result)) {
//          echo $row['City_Name'];
//     }
//   }


?>
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
        <h3 class="closeSignUp" style="align-items: center; justify-content: center; ">
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
                <h1 style="font-size: 20vw;" >new york</h1>
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
                        <button><a href="./read_more.php ?Intern_Id=<?php echo $rowdata['CIPackage_Id'] ?>">read more</a></button> 
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
                            <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/672d05fb3d29a2341a311c13_RHONY_S15E3-5__Erin%27s_%20House_1-topaz-upscale-2000w.avif" alt="">
                        </div>
                        <div class="book-part2">
                            <h2>"Erin's" House"</h2>
                            <h4>The Hamptons, New York</h4>
                        </div>
                    </div>
                    <div class="booking2">

                        <div class="book-part3"> 
                            <h5>What constitutes a “remodel?” Who can say, really? Has Erin’s kitchen been remodeled, as she claims? Or is this a new island and fixtures? Or, perhaps, this is not a freshening up, but the final</h5>
                        </div>
                        <div class="book-part4"  >
                            <button><a href="#">Book now</a></button>
                            <button><a href="./new-york/Erin's-House.php">read more</a></button> 
                        </div>
                    </div>
            </div>
            <div class="booking">
                <div class="booking1">

                    <div class="book-part1">
                        <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66db195f408ef2ce2af4ad34_RHONY_S14E9-11_Long%20Bay%20Villas_1.avif" alt="">
                    </div>
                    <div class="book-part2">
                        <h2>Long Bay Villas </h2>
                        <h4>Long Bay Beach, Anguilla</h4>
                    </div>
                </div>
                <div class="booking2">

                    <div class="book-part3"> 
                        <h5>One might think that Jenna Lyons’ choice to fly first while the rest of the apples suffer in coach would make her the villain of the trip. Nope! Not with Erin here. This meeting of the We Hate Erin club kicked off with a lovely boat trip....</h5>
                    </div>
                    <div class="book-part4"  >
                        <button><a href="#">Book now</a></button>
                        <button><a href="./new-york/long-bay-villas.php">read more</a></button> 
                    </div>
                </div>
            </div>
            <div class="booking">
                <div class="booking1">

                    <div class="book-part1">
                        <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66db17d423125d804532d619_RHONY_S13E3-4_Hamptons-1.avif" alt="">
                    </div>
                    <div class="book-part2">
                        <h2>"Ramona's" Vacation House</h2>
                        <h4>The Hamptons, New York</h4>
                    </div>
                </div>
                <div class="booking2">

                    <div class="book-part3"> 
                        <h5>This is one of the least aspirational trips a group of Housewives has ever taken. Not because Ramona’s mansion, decked out in an HGTV palette of white and light gray, is not comfortable....</h5>
                    </div>
                    <div class="book-part4"  >
                        <button><a href="#">Book now</a></button>
                        <button><a href="./new-york/RamonasHouse.php">read more</a></button> 
                    </div>
                </div> -->
            <!-- </div> -->
        </div>
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