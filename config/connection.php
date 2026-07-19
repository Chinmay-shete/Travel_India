<?php
 session_start();
 //session_destroy();

 
// connect Mysql with object orianted approches
  $servername = "localhost:3380";
  $username = "root";                                       
  $password = "";
  $dbname = "major_project";

  //create connection
 $conn = new mysqli($servername, $username, $password, $dbname);

 //Check Connection
 
 if($conn -> connect_error) {
    die("connection failed : ". $conn -> connect_error);
      
 }

 //echo "Connection Succesfull..!";
 ?>