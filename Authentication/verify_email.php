<?php
include("../config/connection.php");
error_reporting();
session_start();

echo "Your Email account is verified..!";

if(isset($_GET['email']) && isset($_GET['verify_token']))
{
  $email_id = $_GET['email'];
  $token = $_GET['verify_token'];
   
  $check = "SELECT * FROM users WHERE email='$email_id' AND verify_token='$token'";
  $result = $conn->query($check);

  if($result){
    echo"connected";
    $verify_query = "SELECT * FROM users WHERE verify_token='$token'";
    $verify_query_run = $conn->query($verify_query);
    echo"connected";
  }
}
?>
 