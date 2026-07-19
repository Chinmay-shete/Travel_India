<?php

if(isset($_SESSION['error'])){
    echo $_SESSION['error'];
    unset($_SESSION['error']);
}

if(isset($_SESSION['status'])){
  echo "<h4>" .$_SESSION['status']. "</h4>";
  unset($_SESSION['status']);
}
  ?>