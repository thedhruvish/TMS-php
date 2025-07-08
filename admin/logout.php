<?php
session_start();
$full_url =  $_SERVER['REQUEST_URI'];
if (isset($_SESSION)) {
  session_unset();
  session_destroy();

  $new_path = str_replace("/admin/logout.php", "/login.php", $full_url);
  echo $new_path;
  // echo $url;
  header("Location: $new_path ");
} 


$new_path = str_replace("/admin/logout.php", "/login.php", $full_url);
header("Location: $new_path ");

?>
