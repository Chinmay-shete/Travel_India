<?php
   include("../config/connection.php");

   if (isset($_GET['Intern_Id'])) {
    $sql = "select * from create_intern_package where CIPackage_Id = " .  $_GET['Intern_Id'];
    $result = $conn->query($sql);
    $data = mysqli_fetch_assoc($result);
    if ($data) {
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
    <title>Erin's House</title>
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <link rel="stylesheet" href="../css/the-may-fair.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>
    <div class="main">   
        <div class="page1">
            <div class="backimg">
            <img src="<?php echo $data['Package_Image'] ?>"> 
            </div>
            <div class="nav">
                <div class="nav-part1">
                    <h5>Curated hotels from <br> The Real Housewives</h5>
                </div>
                <h2 style="text-transform: capitalize;">The Real travel</h2>
                <i class="ri-menu-line open"></i>
            </div>
            <div class="middle">
                <h1><?php echo $data['Package_Name'] ?></h1>
                <br><h3><?php echo $data['Package_Location'] ?></h3>
             </div>
             <div class="header">
                <h4> Real Housewives of Orange County <br> season 18  | episode(s) 14-16</h4>
                <div class="button">
                    <button><a href="../International_book/book_form.php ?Id=<?php echo $data['CIPackage_Id'] ?>">Book now</a></button>
                    <button><a href="#">read more</a></button>
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
                        <h1 class="animate-text" data-index="1">the real hotels</h1>
                        <h1 class="animate-text" data-index="2">home</h1>
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
             <div class="sign-in">  
                <form action="">
                    
                </form>
             </div>
        </div>
        <div class="page2"  >
           <div class="text">
            <h1><?php echo $data['Package_Details'] ?></h1>
           </div>
           <div class="middle2">
            <div class="middle2-part1">
                <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/672d06044084ac28403d0953_RHONY_S15E3-5__Erin%27s_%20House_2-topaz-upscale-2000w.avif" alt="">
            </div>
            <div class="middle2-part2">
                <h3>A new butter dish here, swap the position of the
                    paper towel roll there, and voilà! A remodeled
                    kitchen! One thing that was different for sure? The
                    snacks. They’re plentiful. Erin puts out a charcuterie
                    board that would make even a SLC Housewife blush..</h3>
            </div>
           </div>
           <div class="middle3">
            <div class="middle3-part1">
                <h3>She keeps it going with a Shabbat dinner complete
                    with Jenna’s homemade pavlova, in which Erin hides
                    plastic roaches because she is very good at jokes. It
                    is pretty funny when Ubah runs to throw up, Sai goes
                    to check on her, and then starts puking in the sink
                    Bridesmaids style.</h3>
                    <div class="button1">
                        <h4><a href="#">Book now</a></h4>
                        <h4><a href="#">explore</a></h4>
                        </div>  
            </div>
            <div class="middle3-part2">
                <img src="https://cdn.prod.website-files.com/66be216df5f5c498bc873efb/66db14faa6bb51e666d6f9bf_RHONY_S11E13-15_Villa%20Vendome_1.avif" alt="">
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
                    <img src="<?php echo $data['Package_Image'] ?>" alt="">
                </div>
                <h3>details</h3>
                <div class="details">
                    <div class="detail1">
                        <div class="detail2"><h3>Package Name</h3></div>
                        <div class="detail3"><h3><?php echo $data['Package_Name'] ?></h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>location</h3></div>
                        <div class="detail3"><h3><?php echo $data['Package_Location'] ?></h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>Package Type</h3></div>
                        <div class="detail3"><h3><?php echo $data['Package_Type'] ?></h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>price</h3></div>
                        <div class="detail3"><h3><?php echo $data['Price'] ?> Rs</h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>Contact No </h3></div>
                        <div class="detail3"><h3><?php echo $data['Phone'] ?> </h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>city</h3></div>
                        <div class="detail3"><h3><?php echo $data['City'] ?> </h3></div> 
                    </div>
                    <div class="detail1">
                        <div class="detail2"><h3>year</h3></div>
                        <div class="detail3"><h3> 2024</h3></div> 
                    </div>
                    
                    <div class="detail1">
                        <div class="detail2"><h3>guide</h3></div>
                        <div class="detail3"><h3>available</h3></div> 
                    </div>
                   
                </div>
            </div>
           </div>
            
        </div>
        <div class="container1">

    <div class="container">
        <div class="overlay"></div>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="slide-text">
                        <div class="slide-part1">
                            <img src="https://adamsandbutler.b-cdn.net/app/uploads/2020/08/icon_extra-mile.svg" alt="">
                        </div>
                        <h3 >Only Irish Virtuoso Agency</h3>
                        <p> Virtuoso, the world’s leading travel consortia, connects travelers to the world’s best travel advisors – and we’re proud to say we’re the only Virtuoso member here in Ireland. We’re local experts, operating at the highest global standards, and the winner of the best travel professional 2020 at the Irish Industry Travel Awards. And our accolades don’t stop there – we’re a Travel and Leisure A-list agency, accredited and bonded by the Irish Travel Agents Association, and frequently profiled by the New York Times, Washington Post, Forbes and Conde Nast, amongst others. </p>
                         
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="slide-text">
                        <div class="slide-part1">
                            <img src="https://adamsandbutler.b-cdn.net/app/uploads/2020/06/why_promise.svg" alt="">
                        </div>
                        <h3>our promise</h3>
                        <p> Real Travel is dedicated to putting your needs first. With our expertise and strong connections, we provide exclusive benefits, competitive rates, and unforgettable experiences. We collaborate with you to create itineraries that exceed your expectations.</p>
                        <p>
                            With years of experience in travel solutions, Real Travel prioritizes your health and happiness. We provide flexible credit and cancellation policies for your peace of mind.</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="slide-text">
                        <div class="slide-part1">
                            <img src="https://adamsandbutler.b-cdn.net/app/uploads/2020/08/icon_accommodation-amenities.svg" alt="">
                        </div>
                        <h3 class="why-title">Accommodation Amenities</h3>
                        <p >Adams &amp; Butler offers clients access to exclusive amenities and benefits at over 500 hotels and lodges worldwide. These amenities include, but are not limited to:<br>
                            • Room upgrade on arrival<br>
                            •&nbsp;Daily breakfast for two<br>
                            •&nbsp;Food &amp; beverage credit<br>
                            •&nbsp;Early check-in/late checkout<br>
                            •&nbsp;Spa treatment credits</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="slide-text">
                        <div class="slide-part1">
                            <img src="https://adamsandbutler.b-cdn.net/app/uploads/2020/08/icon_cruise-benefits.svg" alt="">
                        </div>
                        <h3 class="why-title">Exclusive Cruise Benefits</h3>
                        <p >When voyaging with Adams &amp; Butler on a cruise, clients reap exclusive on-board amenities, which may include:<br>
                            • Dedicated Onboard Hosts<br>
                            • Welcome Aboard Cocktail Reception<br>
                            • Camaraderie of Fellow Travelers<br>
                            • Exclusive Shore Excursion<br>
                            • Up to EUR 400 On Board Credit</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="slide-text">
                        <div class="slide-part1">
                            <img src="https://adamsandbutler.b-cdn.net/app/uploads/2020/08/icon_extra-mile.svg" alt="">
                        </div>
                        <h3 class="why-title">Extra Mile</h3>
                        <p class="text-style">Our highly personal level of service begins the moment you enquire through to your return home. We are on hand 24/7 at all stages to make sure your trip is seamless, memorable and extraordinary. Enjoy travelling with the peace of mind that we are always on hand to assist.<br>
                            <br>
                            Our handpicked guides and travel designers will provide a genuine experience, bringing the destination you are dreaming of to life with care and passion.</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="slide-text">
                        <div class="slide-part1">
                            <img src="https://adamsandbutler.b-cdn.net/app/uploads/2020/08/icon_uncover-your-options.svg" alt="">
                        </div>
                        <h3 class="why-title">Uncover Your Options </h3>
                        <p class="text-style">We are flexible and know how to take away the stress of planning a trip. Whether it’s a weekend break, an around-the-world adventure or multi-city itinerary, we’re here to help. Not only will we book your flights and hotels, but also, we will curate a complete itinerary with bespoke experiences and activities tailored to your individual interests and requirements. </p>
                    </div>
                </div>
            </div>
            <!-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>  -->
        </div>
    </div>
</div>
<div class="container2">
    <div class="main-news">

        
            <div class="news-part1">
                <h1>Featured Press</h1>
            </div>
            <div class="news-part2">
                <div class="news-box" onclick="window.location.href='https://www.travelandleisure.com/hotels-resorts/vacation-rentals/best-villa-rental-agencies'">
                    <div class="box1">
                        <img src="https://www.travelandleisure.com/thmb/fZ74dlPPlrNCgDdVwCg6Y8tMzGc=/750x0/filters:no_upscale():max_bytes(150000):strip_icc():format(webp)/parisperfect-villarentals0716-2000-a4d859f3b63b405d858f0f5d6eca289e.jpg" alt="">
                    </div>
                    <h4>trt news</h4>
                    <p>best villa rental agencies</p>
                </div>
                <div class="news-box" onclick="window.location.href='https://www.travelandleisure.com/a-list/best-europe-travel-agents'">
                    <div class="box1">
                        <img src="/hotel_image/01 tur.avif" alt="">
                    </div>
                    <h4>trt news</h4>
                    <p>the best travel agents for europe</p>
                </div>
                <div class="news-box" onclick="window.location.href='https://www.independent.ie/life/travel/world/forget-kenya-and-south-africa-heres-the-rising-star-of-african-safari/31514072.html'">
                    <div class="box1">
                        <img src="https://focus.independent.ie/thumbor/P877ppPA5uzW2bINF3ihT6_1Ns4=/0x83:1500x910/1280x853/prod-mh-ireland/65297194-be4f-11ed-b158-0210609a3fe2.JPG" alt="">
                    </div>
                    <h4>trt news</h4>
                    <p>forget kenya and south africa, these are the best places to visit in africa</p>
                </div>
            </div>
        
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
    <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="/js/the-may-fair.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 3, // Show three slides
      spaceBetween: 50,
      loop: true,
      centeredSlides: true, // Center the active slide
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });

    // Add active class to the current slide
    swiper.on('slideChange', function () {
      var slides = document.querySelectorAll('.swiper-slide');
      slides.forEach(slide => slide.classList.remove('active'));
      slides[swiper.activeIndex].classList.add('active');
    });
  </script>
</body>
</html>