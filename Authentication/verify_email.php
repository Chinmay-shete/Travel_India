<?php
include("../config/connection.php");
error_reporting();
session_start();

echo "Your Email account is verified..!";

if(isset($_GET['email']) && isset($_GET['verify_token']))
{
  $email_id = $_GET['email'];
  $token = $_GET['verify_token'];
   
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verify_token = ?");
  $stmt->bind_param("ss", $email_id, $token);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  if($result){
    echo"connected";
    $stmt2 = $conn->prepare("SELECT * FROM users WHERE verify_token = ?");
    $stmt2->bind_param("s", $token);
    $stmt2->execute();
    $verify_query_run = $stmt2->get_result();
    $stmt2->close();
    echo"connected";
  }
}
?>
 