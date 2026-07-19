<?php
include("config/connection.php");
include("config/email_config.php");
include("config/email_queue.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function Sendemail_Verify($fname, $email, $otp)
{
    global $conn;
    $subject = '🔐 Your OTP Verification Code — The Real Travel';
    $body    = getOtpEmailTemplate($fname, $otp);

    // Try direct send first (fast path)
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug  = 0;
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = (MAIL_SECURE === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = "Hello $fname, Your OTP is: $otp (valid for 1 minute)";
        $mail->send();
        error_log("OTP email sent directly to $email");
    } catch (Exception $e) {
        // Direct send failed — fall back to queue for retry by worker
        error_log('Direct mail failed, queuing for retry: ' . $mail->ErrorInfo);
        enqueue_email($conn, $email, $subject, $body);
    }
}

// Generate OTP securely
$otp = (string)random_int(100000, 999999);
$activation_code = bin2hex(random_bytes(16));

if (isset($_POST['submit'])) {
    $otp = $_POST['otp'];
    $activation_code = $_POST['activation_code'];
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? '';

    // Input validation
    if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($user_type)) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } elseif (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters long.');</script>";
    } elseif (!in_array($user_type, ['user', 'admin'])) {
        echo "<script>alert('Invalid user type.');</script>";
    } else {
        $_SESSION["fname"] = $fname;

        // Check if email already exists
        $stmt = $conn->prepare("SELECT user_Id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>alert('Email already exists!')</script>";
            $stmt->close();
        } else {
            $stmt->close();
            
            // Hash password securely with Bcrypt
            $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

            // Rate limit check before registration
            $limit_check = check_rate_limit($conn, 'register', 5, 900);
            if (!$limit_check['allowed']) {
                echo "<script>alert('Too many registration attempts. Locked out for " . ceil($limit_check['time_left'] / 60) . " minutes.');</script>";
            } else {
                $sql = "INSERT INTO users (fname, lname, email, password, user_type, otp, activation_code, status, dob, Mobile_No, Address) VALUES(?, ?, ?, ?, ?, ?, ?, 'inactive', '', '', '')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $fname, $lname, $email, $password_hash, $user_type, $otp, $activation_code);
                
                if ($stmt->execute()) {
                    Sendemail_Verify($fname, $email, $otp);
                    echo "<script>alert('Your registration was successful! Please verify your email.'); window.location.href='Authentication/otp_verify.php?code=" . $activation_code . "';</script>";
                } else {
                    echo "<script>alert('Registration failed. Please try again.');</script>";
                }
                $stmt->close();
            }
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
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.18/dist/lenis.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css" />
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
                        <h1 class="animate-text" data-index="5"> <a href="pages/contact/contact_index.php"> get in touch</a></h1>
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
  // session already started in connection.php - do NOT call session_start() again

  if (isset($_POST['Login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        // Rate limit check before checking credentials
        $limit_check = check_rate_limit($conn, 'login', 5, 900);
        if (!$limit_check['allowed']) {
            echo "<script>alert('Too many failed login attempts. Locked out for " . ceil($limit_check['time_left'] / 60) . " minutes.');</script>";
        } else {
            // Select user by email (only fetching explicit columns)
            $stmt = $conn->prepare("SELECT user_Id, fname, password, status, user_type FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $authenticated = false;
                $rehash_needed = false;

                // Validate password
                if (password_verify($password, $row['password'])) {
                    $authenticated = true;
                    if (password_needs_rehash($row['password'], PASSWORD_BCRYPT, ['cost' => 12])) {
                        $rehash_needed = true;
                    }
                } else {
                    // Fallback migration path for legacy plaintext passwords
                    if (strpos($row['password'], '$2y$') !== 0 && $row['password'] === $password) {
                        $authenticated = true;
                        $rehash_needed = true;
                    }
                }

                if ($authenticated) {
                    // If migration/rehash needed
                    if ($rehash_needed) {
                        $new_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_Id = ?");
                        $update_stmt->bind_param("si", $new_hash, $row['user_Id']);
                        $update_stmt->execute();
                        $update_stmt->close();
                    }

                    // Reset rate limits on success
                    reset_rate_limit($conn, 'login');

                    if ($row['status'] == 'active') {
                        $_SESSION["email"] = $email;
                        $_SESSION["user_id"] = $row["user_Id"];
                        $_SESSION["user_type"] = $row["user_type"];
                        $_SESSION["fname"] = $row["fname"];

                        if ($row["user_type"] == "user") {
                            echo "<script>alert('Welcome users, Explore this Real_Travel website..!');</script>";
                            echo "<script>window.location.href = 'homepage.php';</script>";
                        } elseif ($row["user_type"] == "admin") {
                            echo "<script>window.location.href = 'admin/adminhomepage.php';</script>";
                        }
                    } else {
                        echo "<script>alert('Your account is not verified. Please click Verify Email_ID..!');</script>";
                    }
                } else {
                    increment_rate_limit($conn, 'login');
                    echo "<script>alert('Invalid email or password.');</script>";
                }
            } else {
                increment_rate_limit($conn, 'login');
                echo "<script>alert('Invalid email or password.');</script>";
            }

            $stmt->close();
        }
    }
  }
  ?>
  <form action="" method="POST">
    <?php include("config/alert.php"); ?>
    <?php echo csrf_field(); ?>
    <label for="bravolebrity" class="required">Email</label>
    <input type="email" name="email" placeholder="email" required />
    <label for="activity" class="required">Password</label>
    <input type="password" name="password" placeholder="password" required />
    <button class="button-part1" type="submit" name="Login">Login</button>
    <button class="button-part1" id="xyz"><a href="auth/password_reset.php">Forget Password</a></button>
    <button class="button-part1" id="xyz"><a href="auth/resend_otp.php">Verify Email</a></button>
  </form>
</div>









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
          <?php echo csrf_field(); ?>
          <input type="hidden" name="otp" value="<?php echo "$otp"; ?>">
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
                <h1><a href="pages/public/orange-county.php">Orange County</a></h1>
                <h1><a href="pages/public/new-york.php">new york</a></h1>
                <h1><a href="pages/public/Atlanta.php">Atlanta</a></h1>
                <h1><a href="pages/public/new-jersey.php">New Jersey</a></h1>
                <h1><a href="pages/public/Dallas.php">Dallas</a></h1>
                <h1><a href="pages/public/salt-lake-city.php">Salt Lake City</a></h1>
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
    <!-- <script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script> -->
    <script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        let redirect = () => {
            alert('Please Sign In..!')
            //window.location.href="../index.php"
        }
    </script>
</body>

</html>