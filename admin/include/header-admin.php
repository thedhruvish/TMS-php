<?php
include_once "../config/db.php";
include_once "../Database.php";
if (!(isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin')) {
  session_destroy();
  $new_path = preg_replace("#/admin/[^/]+\.php$#", "/login.php", $_SERVER['REQUEST_URI']);
  header("location: $new_path?error=login Required");
}
$db = new Database($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  <title> <?php echo isset($pageTitle) ? $pageTitle : 'Admin'; ?> </title>

  <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico" />
  <link href="../layouts/vertical-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
  <link href="../layouts/vertical-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
  <script src="../layouts/vertical-light-menu/loader.js"></script>

  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
  <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="../layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
  <link href="../layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
  <!-- END GLOBAL MANDATORY STYLES -->

  <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
  <link href="../src/plugins/src/apex/apexcharts.css" rel="stylesheet" type="text/css">
  <link href="../src/assets/css/light/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
  <link href="../src/assets/css/dark/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
  <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

  <!-- BEGIN PAGE LEVEL STYLES -->
  <link rel="stylesheet" type="text/css" href="../src/plugins/src/table/datatable/datatables.css">

  <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/table/datatable/dt-global_style.css">
  <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/table/datatable/custom_dt_miscellaneous.css">

  <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/table/datatable/dt-global_style.css">
  <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/table/datatable/custom_dt_miscellaneous.css">

  <!-- END PAGE LEVEL STYLES -->


  <!--  BEGIN invoce STYLE FILE  -->
  <link href="../src/assets/css/light/apps/invoice-list.css" rel="stylesheet" type="text/css" />

  <link href="../src/assets/css/dark/apps/invoice-list.css" rel="stylesheet" type="text/css" />
  <!--  END CUSTOM STYLE FILE  -->

      <!-- BEGIN PAGE user STYLES -->
    <link href="../src/assets/css/light/components/modal.css" rel="stylesheet" type="text/css">
    <link href="../src/assets/css/light/apps/contacts.css" rel="stylesheet" type="text/css" />

    <link href="../src/assets/css/dark/components/modal.css" rel="stylesheet" type="text/css">
    <link href="../src/assets/css/dark/apps/contacts.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->  
<!-- invoice -->
      <link href="../src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
    <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

    <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
    <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="../src/assets/css/light/apps/invoice-add.css" rel="stylesheet" type="text/css" />

    <link href="../src/plugins/css/dark/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
    <link href="../src/plugins/css/dark/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="../src/assets/css/dark/apps/invoice-add.css" rel="stylesheet" type="text/css" />

</head>

<body class="layout-boxed">
  <!-- BEGIN LOADER -->
  <div id="load_screen">
    <div class="loader">
      <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
      </div>
    </div>
  </div>
  <!--  END LOADER -->