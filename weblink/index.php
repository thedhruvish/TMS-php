<?php
require_once "../Database.php";

if (!isset($_GET['id'])) {
  echo "No id found";
  exit();
}

$shareId = $_GET['id'];

$query_result = $DB->read("weblink", ["where" => ["id" => ["=" => $shareId]]]);

$result = mysqli_fetch_assoc($query_result);

$product_ids_str = "(" . $result['productIds'] . ")";
$product_result = $DB->custom_query("SELECT *
FROM products
WHERE id IN $product_ids_str;
");
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  <title>Products</title>

  <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico" />
  <link href="../layouts/vertical-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
  <link href="../layouts/vertical-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
  <script src="../layouts/vertical-light-menu/loader.js"></script>

  <!-- STYLES -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
  <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="../layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
  <link href="../layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
</head>

<body class="container pt-5">
  <!-- BEGIN LOADER -->
  <div id="load_screen">
    <div class="loader">
      <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
      </div>
    </div>
  </div>
  <?php
  // Database connection and data fetching
  $products = [];
  $error = null;

  try {
    $res = $DB->custom_query("SELECT *
    FROM products
    WHERE id IN $product_ids_str;
    ");
    if ($res === false) {
      throw new Exception("Query failed");
    }

    if (mysqli_num_rows($res) > 0) {
      $products = mysqli_fetch_all($res, MYSQLI_ASSOC);

      // Decode product images
      foreach ($products as &$product) {
        if (!empty($product['images'])) {
          $product['images'] = json_decode($product['images'], true);
        } else {
          $product['images'] = ['../images/placeholder.jpg'];
        }
      }
      unset($product);
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }

  $filteredProducts = $products;
  ?>

  <!-- Messages -->
  <?php if ($error) { ?>
    <div class="alert alert-danger">
      Database Error: <?php echo $error; ?>
    </div>
  <?php } ?>

  <!-- Product Grid -->
  <div class="row">
    <?php if (empty($filteredProducts)) { ?>
      <div class="col-12">
        <div class="alert alert-info">
          No products found.
        </div>
      </div>
    <?php } else { ?>
      <?php foreach ($filteredProducts as $product) { ?>
        <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-6 m-4">
          <div class="card h-100 position-relative overflow-hidden shadow-sm" style="transition: all 0.3s ease;">

            <!-- Category Badge -->
            <?php if (!empty($product['category'])): ?>
              <span class="badge bg-primary position-absolute top-0 end-0 m-2 z-2">
                <?= htmlspecialchars($product['category']) ?>
              </span>
            <?php endif; ?>

            <!-- Product Image Carousel -->
            <div id="carouselExampleIndicators<?php echo $product['id']; ?>" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
              <?php if (count($product['images']) > 1) { ?>
                <ol class="carousel-indicators mb-1">
                  <?php foreach ($product['images'] as $k => $image) { ?>
                    <li data-bs-target="#carouselExampleIndicators<?php echo $product['id']; ?>"
                      data-bs-slide-to="<?php echo $k; ?>"
                      class="<?php echo $k == 0 ? 'active' : '' ?>"
                      style="width: 8px; height: 8px; border-radius: 50%; margin: 0 3px;"></li>
                  <?php } ?>
                </ol>
              <?php } ?>

              <!-- carousel-inner -->
              <div class="carousel-inner">
                <?php foreach ($product['images'] as $k => $image) { ?>
                  <div class="carousel-item <?php echo $k == 0 ? 'active' : '' ?>">
                    <div class="ratio ratio-4x3">
                      <img class="img-fluid object-fit-cover w-100"
                        src="../images/products/<?php echo $image ?>"
                        alt="<?php echo $product['name']; ?>">
                    </div>
                  </div>
                <?php } ?>
              </div>

              <?php if (count($product['images']) > 1) { ?>
                <a class="carousel-control-prev" href="#carouselExampleIndicators<?php echo $product['id']; ?>" role="button" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators<?php echo $product['id']; ?>" role="button" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </a>
              <?php } ?>
            </div>

            <!-- Product Info -->
            <div class="card-body d-flex flex-column">
              <!-- Product Name (just text now) -->
              <h6 class="card-title mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 48px;">
                <?php echo htmlspecialchars($product['name']); ?>
              </h6>

              <!-- Price -->
              <div class="d-flex align-items-center mb-2">
                <?php if (!empty($product['sale_price']) && $product['sale_price'] > 0 && $product['sale_price'] < $product['regular_price']) { ?>
                  <span class="text-danger text-decoration-line-through me-2 small">
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

              <!-- Product Description -->
              <?php if (!empty($product['description'])) { ?>
                <p class="text-muted small mb-2" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; min-height: 60px;">
                  <?php echo htmlspecialchars($product['description']); ?>
                </p>
              <?php } ?>
            </div>
          </div>
        </div>
      <?php } ?>
    <?php } ?>
  </div>

  <div class="footer-wrapper">
    <div class="footer-section f-section-1">
      <p class="">Copyright © <span class="dynamic-year">2025</span> All rights reserved.</p>
    </div>
    <div class="footer-section f-section-2">
      <p class="">Coded with TMS</p>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
  <script src="../src/plugins/src/waves/waves.min.js"></script>
  <script src="../layouts/vertical-light-menu/app.js"></script>
</body>

</html>