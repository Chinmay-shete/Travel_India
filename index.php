<?php
include("config/connection.php");
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function Sendemail_Verify($fname, $email, $otp)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'harsh1234vathare@gmail.com';
        $mail->Password = 'olfq duvu rucq tvsv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('travelindia9500@gmail.com', 'The Real_Travel.com');
        $email = $_POST['email'];
        $mail->addAddress($email);
        $mail->addReplyTo('travelindia9500@gmail.com', 'Information');

        $mail->isHTML(true);
        $mail->Subject = 'Verification code for verify your email address..!';
        $mail->Body = "<h3>hello " . $_POST['fname'] . "</h3><h3> You need to verify your account with this tourism website !</h3>
                   <h3> Enter this verification code for activate your account : <b>" . $_POST['otp'] . "</b></h3>
                   <br/><br/>";

        $res = $mail->send();
        if (!$res) {
            echo "<script>alert('Your Messages not Send..!')</script>";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

$otp_str = str_shuffle("0123456789");
$otp = substr($otp_str, 0, 6);

$act_str = rand(100000, 1000000);
$activation_code = str_shuffle("abcdefghijklmno" . $act_str);

if (isset($_POST['submit'])) {
    $otp = $_POST['otp'];
    $activation_code = $_POST['activation_code'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $_SESSION["fname"] = $fname;

    $email_check = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($email_check);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email_Id Or Password already Exists..!')</script>";
    } else {
        $sql = "INSERT INTO users (fname, lname, email, password, user_type ,otp, activation_code) VALUES('$fname','$lname','$email','$password','$user_type' , '$otp','$activation_code')";
        $qury = $conn->query($sql);

        if ($qury) {
            Sendemail_Verify("$fname", "$email", " $otp");
            echo "<script>alert('Your registration succesfully..! Please Verify your Email Address..!')</script>";
            header("Refresh:0.5; url=Authentication/otp_verify.php?code=" . $activation_code);
        } else {
            $_SESSION['status'] = "Registration Failed..!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>the real hote</title>
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.18/dist/lenis.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
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
                    <i class="ri-close-line close"></i>
                </div>
                <div class="menu-section">
                    <div class="menu">
                        <h1 class="animate-text" data-index="1">the real hotels</h1>
                        <h1 class="animate-text signIn" data-index="2">sign in</h1>
                        <h1 class="animate-text signUp" data-index="3">sign up</h1>
                        <h1 class="animate-text" data-index="4">about us</h1>
                        <h1 class="animate-text" data-index="5"> <a href="Get_in_Touch/contact_index.php"> get in touch</a></h1>
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
        <!-- sign In page -->
        <div class="signUpPage signInPage">
        <div class="nav">
            <div class="nav-part2">

            <h3 class="closeSignIn" style="align-items: center; justify-content: center; display: flex;">
              <svg id="arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
              </svg>
                    back</h3>
                </div>
          <div class="nav-part1">
             <h3>est-2024</h3>
          </div>
        </div>
        <hr class="animated-hr" />
        <div class="signUpPage-part1"> 
          <div class="signUpPage-part11">
            <h3>Sign in</h3>
            <div class="signUpPage-bottom"> 
              <h1>Begin <br> Your <br> Adventure </h1>
            </div>
    
          </div>






          <div class="container">
  <?php
  // Start the session
  session_start();

  // Include your database connection file
  include("config/connection.php"); // Update with the correct path

  if (isset($_POST['Login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      if ($row["user_type"] == "user") {
        if ($row['status'] == 'active') {
          $_SESSION["email"] = $email;
          echo "<script>alert('Welcome users, Explore this Real_Travel website..!');</script>";
          echo "<script>window.location.href = 'other/homepage.php';</script>";
        } else {
          echo "<script>alert('Your account is not verified. Please click Verify Email_ID..!');</script>";
        }
      } elseif ($row["user_type"] == "admin") {
        if ($row['status'] == 'active') {
          echo "<script>window.location.href = 'admin/adminhomepage.php';</script>";
        } else {
          echo "<script>alert('Your account is not verified. Please click Verify Email_ID..!');</script>";
        }
      } else {
        echo "<script>alert('Invalid Login info..!');</script>";
      }
    } else {
      echo "<script>alert('Invalid email or password.');</script>";
    }

    // Close the prepared statement
    $stmt->close();
  }
  ?>
  <form action="" method="POST">
    <?php include("config/alert.php"); ?>
    <label for="bravolebrity" class="required">Email</label>
    <input type="email" name="email" placeholder="email" required />
    <label for="activity" class="required">Password</label>
    <input type="password" name="password" placeholder="password" required />
    <button class="button-part1" type="submit" name="Login">Login</button>
    <button class="button-part1" id="xyz"><a href="Authentication/password_reset.php">Forget Password</a></button>
    <button class="button-part1" id="xyz"><a href="Authentication/resend_otp.php">Verify Email</a></button>
  </form>
</div>






          <!-- <div class="container"> 
          <?php
    //   if(isset($_POST['Login'])) {
    //     $email = $_POST['email'];
    //     $password = $_POST['password'];

    //     $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    //     $result = $conn->query($sql);
    //     $result = mysqli_query($conn, $sql);
    //     $row = mysqli_fetch_array($result);

    //     if ($row["user_type"] == "user") {
    //       if ($row['status'] == 'active') {
    //        echo $_SESSION["email"] = $email;
    //       // header("Refresh:0.3; url=other/homepage.php");
    //         echo "<script >alert('Welcome users, Explore this Real_Travel-website..!')</script>";
    //         header("Refresh:0.3; url=other/homepage.php");
    //       } else {
    //         echo "<script>alert('Your account is not verified, Please click Verify Email_ID..!')</script>";
    //       }
    //     } else if ($row["user_type"] == "admin") {
    //       if ($row['status'] == 'active') {
    //         header('location:admin/adminhomepage.php');
    //       } else {
    //         echo "<script>alert('Your account is not verified, Please click Verify Email_Id..!')</script>";
    //       }
    //     } else {
    //       echo "<script>alert('Invalid Login info..!')</script>";
    //     }
    //   }
      ?>
             <form action="" method="POST">
                <?php // include("config/alert.php"); ?> 
              <label for="bravolebrity" class="required">email</label>
              <input type="email"  name="email"  placeholder="email " required /> 
              <label for="activity" class="required">password</label>
              <input  type="password" name="password"  placeholder="password  " required />

              <button class="button-part1" type="submit" name="Login">login</button> 
      
              <button class="button-part1"  id="xyz"><a href="Authentication/password_reset.php">forget password</a></button>
              <button class="button-part1"  id="xyz"><a href="Authentication/resend_otp.php">Verify Email</a></button>
     
            </form>
          </div>  -->








        </div>
      </div>

      <!-- Sign up page -->
        <div class="signUpPage">
    <div class="nav">
        <div class="nav-part2">

        <h3 class="closeSignUp" style="align-items: center; justify-content: center; display: flex;">
              <svg id="arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="1.2vw" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M11.708 19.273a.686.686 0 0 0-.05-.966l-6.121-5.55h14.71c.416 0 .753-.338.753-.756a.755.755 0 0 0-.752-.758H5.53l6.129-5.548a.69.69 0 0 0 .05-.969.676.676 0 0 0-.961-.05l-7.522 6.812a.69.69 0 0 0 0 1.017l7.52 6.82c.28.252.71.23.962-.052Z"></path>
              </svg>
                back</h3>
            </div>
      <div class="nav-part1">
         <h3>est-2024</h3>
      </div>
    </div>
    <hr class="animated-hr" />
    <div class="signUpPage-part1"> 
      <div class="signUpPage-part11">
        <h3>Create Profile</h3>
        <div class="signUpPage-bottom">
          <h1>Start <br> Your <br> Journey</h1>
        </div>

      </div>
      <div class="container"> 
        <form action="" method="post">
          <?php include("config/alert.php"); ?>
          <input type="hidden" name="otp" value="<?php echo "$act_str"; ?>">
                    <input type="hidden" name="activation_code" value="<?php echo "$activation_code"; ?>">
          <label for="bravolebrity" class="required">first name</label>
          <input type="text" name="fname" placeholder="FirstName  " required />

          <label for="activity" class="required">last Name</label>
          <input type="text" name="lname" placeholder="LastName " required />

          <label for="activity" class="required">email</label>
          <input type="email" name="email" placeholder="Email " required />
             
          <label for="password" class="required">Password</label>
          <input type="password" name="password" placeholder="Password " required />

          <!-- User Type Selection -->
    <label for="user_type" class="required">User Type</label>
    <select name="user_type" id="user_type" required>
        <option value="">-- Select User Type --</option>
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
  
          <button class="button-part1" type="submit" name="submit">create account</button>
 
        </form>
      </div> 
    </div>
  </div>
        <div class="page2">
            <div class="video">
            <!-- <video autoplay muted loop src="https://videos.pexels.com/video-files/4133023/4133023-sd_640_360_30fps.mp4"></video> -->
            <!-- https://videos.pexels.com/video-files/4133023/4133023-sd_640_360_30fps.mp4 -->
                <video autoplay muted loop src=" https://www.fourseasons.com/content/dam/fourseasons/video/FSH/FSH_festive_ambient_shorter.mp4"></video>
                <!-- https://videos.pexels.com/video-files/4133023/4133023-sd_640_360_30fps.mp4 -->
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
                <h1><a href="before_Login/Orange County.php">Orange County</a></h1>
                <h1><a href="before_Login/new-york.php">new york</a></h1>
                <h1><a href="before_Login/Atlanta.php">Atlanta</a></h1>
                <h1><a href="before_Login/New Jersey.php">New Jersey</a></h1>
                <h1><a href="before_Login/Dallas.php">Dallas</a></h1>
                <h1><a href="before_Login/Salt Lake City.php">Salt Lake City</a></h1>
            </div>
        </div>
        <div id="page6">
            <div class="page-text"> 
                <h1>the real travel </h1>
                <h4>These curated collections of popular and highly-rated travel <br> experiences offer well-organized itineraries, premium accommodations, <br> guided tours, exclusive deals, memorable moments, exceptional services, <br> personalized options, and unique destinations for all travelers</h4>
            </div>
            <div class="cards" id="card-one">
                <div class="nav">

                    <h2 style="text-transform: capitalize; color:white">The Real travel</h2>
                </div>
                <div class="middle">
                    <h1>"Elizabeth Vargas's" Home</h1>
                    <br>
                    <h4>La Quinta, California</h4>
                </div>
                <div class="header">
                    <h4> Real Housewives of Orange County <br> season 18 | episode(s) 14-16</h4>
                    <button> <a href="#" onclick="redirect()">explore</a></button>
                </div>
            </div>
            <div class="cards" id="card-two">
                <div class="nav">

                    <h2 style="text-transform: capitalize;color:white ">The Real travel</h2>
                </div>
                <div class="middle">
                    <h1>"Elizabeth Vargas's" Home</h1>
                    <br>
                    <h4>La Quinta, California</h4>
                </div>
                <div class="header">
                    <h4> Real Housewives of Orange County <br> season 18 | episode(s) 14-16</h4>
                    <button> <a href="#" onclick="redirect()">explore</a></button>
                </div>
            </div>
            <div class="cards" id="card-three">
                <div class="nav">

                    <h2 style="text-transform: capitalize;color:white ">The Real travel</h2>
                </div>
                <div class="middle">
                    <h1>The May Fair Hotel</h1>
                    <br>
                    <h4>London, England</h4>
                </div>
                <div class="header">
                    <h4> Real Housewives of Orange County <br> season 18 | episode(s) 14-16</h4>
                    <button> <a href="#" onclick="redirect()">explore</a></button>
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.js"></script> -->
    <script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        let redirect = () => {
            alert('Please Sign In..!')
            //window.location.href="../index.php"
        }
    </script>
</body>

</html>