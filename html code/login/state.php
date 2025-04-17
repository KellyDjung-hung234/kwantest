<?php
session_start();
$state = isset($_SESSION['state']) ? $_SESSION['state'] : null;

switch ($state) {
  //resigter checking function
  case "connect_fail":
    echo 'Error: Could not connect to database.  Please try again later.'; 
    echo '<script>setTimeout(function() { window.location = "login.php"; }, 4000);</script>';
    break;

  case "password_wrong":
    echo "The password confirm is not same as password.";
    echo '<script>setTimeout(function() { window.location = "resiger.php"; }, 4000);</script>';
    break;

  case "have_empty":
    echo "You have not entered all the required details.<br />"
            ."Please go back and try again.";
    echo '<script>setTimeout(function() { window.location = "resiger.php"; }, 4000);</script>';
    break;

  case "resiger_fail":
    echo "register fail";
    echo '<script>setTimeout(function() { window.location = "resiger.php"; }, 4000);</script>';
    break;

  case "resiger_sucess":
      echo "register sucess";
      echo '<script>setTimeout(function() { window.location = "login.php"; }, 4000);</script>';
      break;

//login checking function
  case "login_fail":
      echo "Userid or Password is wrong!";
      echo '<script>setTimeout(function() { window.location = "login.php"; }, 4000);</script>';
      break;
//vote function
  case "vote_success":
       echo "Vote success!";
       echo '<script>setTimeout(function() { window.location = "MainContent.php"; }, 4000);</script>';
       break;
  case "vote_fail":
       echo "Vote fail!";
       echo '<script>setTimeout(function() { window.location = "MainContent.php"; }, 4000);</script>';
       break;
  case "create_fail":
        echo "create fail!";
        echo '<script>setTimeout(function() { window.location = "MainContent.php"; }, 4000);</script>';
        break;

  default:
   echo "Error";
   echo '<script>setTimeout(function() { window.location = "login.php"; }, 4000);</script>';
  }
  session_write_close();

?>