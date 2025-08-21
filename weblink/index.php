<?php

// Database connection and data fetching
require_once "../Database.php";

$products = [];
$error = null;

try {
  $res = $DB->read("products");
  if ($res === false) {
    throw new Exception("Query failed");
  }

  if (mysqli_num_rows($res) > 0) {
    $products = mysqli_fetch_all($res, MYSQLI_ASSOC);
  }
} catch (Exception $e) {
  $error = $e->getMessage();
}

// Handle filters
$searchTerm = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$sortBy = $_GET['sort'] ?? 'newest';

// Get available categories
$categories = array_unique(array_column($products, 'category'));

// Filter products
$filteredProducts = $products;

if (!empty($searchTerm)) {
  $filteredProducts = array_filter($filteredProducts, function ($product) use ($searchTerm) {
    return stripos($product['name'], $searchTerm) !== false ||
      stripos($product['description'], $searchTerm) !== false;
  });
}

if (!empty($categoryFilter)) {
  $filteredProducts = array_filter($filteredProducts, function ($product) use ($categoryFilter) {
    return strcasecmp($product['category'], $categoryFilter) === 0;
  });
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  <title>Admin </title>

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


  <!-- BEGIN PAGE user STYLES -->
  <link href="../src/assets/css/light/components/modal.css" rel="stylesheet" type="text/css">
  <!-- END PAGE LEVEL STYLES -->


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

  <div class="container mt-4">
    <div class="row">
      <?php if (empty($filteredProducts)) { ?>
        <div class="col-12">
          <div class="alert alert-info text-center">
            No products found.
            <?php if (!empty($searchTerm) || !empty($categoryFilter)) { ?>
              <a href="products.php" class="alert-link">Clear filters</a>
            <?php } ?>
          </div>
        </div>
      <?php } else { ?>
        <?php foreach ($filteredProducts as $product) { ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4">
            <div class="card h-100 border shadow-sm position-relative">

              <!-- Checkbox -->
              <div class="position-absolute top-0 start-0 m-2">
                <input type="checkbox" class="form-check-input product-check" value="<?php echo $product['id'] ?>">
              </div>

              <!-- Category Badge -->
              <?php if (!empty($product['category'])) { ?>
                <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                  <?php echo $product['category']; ?>
                </span>
              <?php } ?>

              <!-- Product Image -->
              <img src="<?php echo !empty($product['image']) ? $product['image'] : '../images/placeholder.jpg' ?>"
                class="card-img-top" alt="<?php echo $product['name']; ?>"
                style="height: 180px; object-fit: cover;">

              <!-- Product Info -->
              <div class="card-body">
                <h6 class="card-title"><?php echo $product['name']; ?></h6>
                <div>
                  <?php if (!empty($product['sale_price']) && $product['sale_price'] > 0 && $product['sale_price'] < $product['regular_price']) { ?>
                    <span class="text-muted text-decoration-line-through me-2">
                      $<?php echo number_format($product['regular_price'], 2) ?>
                    </span>
                    <span class="text-success fw-bold">
                      $<?php echo number_format($product['sale_price'], 2) ?>
                    </span>
                  <?php } else { ?>
                    <span class="text-success fw-bold">
                      $<?php echo number_format($product['regular_price'], 2) ?>
                    </span>
                  <?php } ?>
                </div>
              </div>

              <!-- Stock Badge -->
              <div class="card-footer bg-transparent border-0">
                <span class="badge <?php echo $product['in_stock'] ? 'bg-success' : 'bg-danger' ?>">
                  <?php echo $product['in_stock'] ? 'IN STOCK' : 'OUT OF STOCK' ?>
                </span>
              </div>
            </div>
          </div>
      <?php }
      } ?>
    </div>
  </div>



  </div>

  </div>
  <div class="footer-wrapper">
    <div class="footer-section f-section-1">
      <p class="">Copyright © <span class="dynamic-year">2025</span> All rights reserved.</p>
    </div>
    <div class="footer-section f-section-2">
      <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
        </svg></p>
    </div>
  </div>
  <!--  END FOOTER  -->
  </div>
  <!--  END CONTENT AREA  -->

  </div>
  <!-- END MAIN CONTAINER -->

  <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
  <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
  <script src="../src/plugins/src/waves/waves.min.js"></script>
  <script src="../layouts/vertical-light-menu/app.js"></script>
  <!-- END GLOBAL MANDATORY SCRIPTS -->


  <script src="../src/plugins/src/global/vendors.min.js"></script>
  <!-- fix navbar profile
  <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>   -->
  <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
  <script src="../src/plugins/src/waves/waves.min.js"></script>

  <!-- user page -->
  <script src="../src/plugins/src/jquery-ui/jquery-ui.min.js"></script>
  <!-- user page -->
  <script src="../src/plugins/src/jquery-ui/jquery-ui.min.js"></script>

</body>

</html>