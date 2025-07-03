<?php

if ($_SESSION['email']) {
  # code...
  session_unset();
  session_destroy();

  $full_url =  $_SERVER['REQUEST_URI'];
  $new_path = str_replace("/admin/logout.php", "/login.php", $full_url);
  echo $new_path;
  // echo $url;
  header("Location: $new_path ");
}
