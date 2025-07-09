<?php
require_once "./Database.php";

if (isset($_SESSION['user_id'])) {
  if ($_SESSION['role'] === 'admin') {
      header("Location: ./admin");
    } else {
      header("Location: ./staff");
    }
}
  include_once "config.php";
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  <title><?php echo isset($pageTitle) ? $pageTitle : 'Authentication'; ?> </title>
  <link rel="icon" type="image/x-icon" href="./src/assets/img/favicon.ico" />
  <link href="./layouts/vertical-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
  <link href="./layouts/vertical-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
  <script src="./layouts/vertical-light-menu/loader.js"></script>
  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
  <link href="./src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <link href="./layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
  <link href="./src/assets/css/light/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />

  <link href="./layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
  <link href="./src/assets/css/dark/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />
  <!-- END GLOBAL MANDATORY STYLES -->

</head>

<body class="form">

  <!-- BEGIN LOADER -->
  <div id="load_screen">
    <div class="loader">
      <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
      </div>
    </div>
  </div>
  <!--  END LOADER -->

  <div class="auth-container d-flex">

    <div class="container mx-auto align-self-center">

      <div class="row">

        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
          <div class="card mt-3 mb-3">
            <div class="card-body">