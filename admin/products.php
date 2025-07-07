<?php $pageTitle = "Products";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';
?>

<!-- 🔍 Toolbar -->
<div class="row mb-4 align-items-center justify-content-between">
  <!-- Left: Search -->
  <div class="col-md-6 d-flex align-items-center">
    <input type="text" class="form-control w-100" placeholder="Search products..." style="max-width: 250px;">
  </div>

  <!-- Right: Categories, Sort By, Share -->
  <div class="col-md-6 text-md-end text-start mt-3 mt-md-0">
    <div class="d-inline-flex gap-2 flex-wrap justify-content-md-end align-items-center w-100">
      <select class="form-select w-auto" style="min-width: 160px;">
        <option selected>All Categories</option>
        <option>Shoes</option>
        <option>Electronics</option>
        <option>Accessories</option>
      </select>

      <select class="form-select w-auto" style="min-width: 140px;">
        <option selected>Sort By</option>
        <option>Newest</option>
        <option>Price: Low to High</option>
        <option>Price: High to Low</option>
      </select>

      <button id="shareSelected" class="btn btn-primary px-4 py-2">Share Selected</button>
    </div>
  </div>
</div>

<!-- 🛒 Product Cards -->
<div class="row">

  <!-- Product Card 1 -->
  <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card style-6 h-100 position-relative overflow-hidden">

      <!-- Checkbox in image corner -->
      <div class="position-absolute top-0 start-0 m-2 z-2">
        <input type="checkbox" class="form-check-input product-check" value="1">
      </div>

      <!-- NEW badge -->
      <span class="badge badge-primary position-absolute top-0 end-0 m-2 z-2">NEW</span>

      <img src="../src/assets/img/product-3.jpg" class="card-img-top" alt="Nike Green Shoes">

      <div class="card-footer">
        <div class="row">
          <div class="col-12 mb-2 text-truncate">
            <b>Nike Green Shoes</b>
          </div>
          <div class="col-6">
            <div class="badge--group">
              <span class="badge badge-primary badge-dot"></span>
              <span class="badge badge-danger badge-dot"></span>
              <span class="badge badge-info badge-dot"></span>
            </div>
          </div>
          <div class="col-6 text-end">
            <p class="text-success fw-bold mb-0">$150.00</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Product Card 2 -->
  <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card style-6 h-100 position-relative overflow-hidden">

      <div class="position-absolute top-0 start-0 m-2 z-2">
        <input type="checkbox" class="form-check-input product-check" value="2">
      </div>

      <span class="badge badge-danger position-absolute top-0 end-0 m-2 z-2">SALE</span>

      <img src="../src/assets/img/product-10.jpg" class="card-img-top" alt="Camera">

      <div class="card-footer">
        <div class="row">
          <div class="col-12 mb-2 text-truncate">
            <b>Camera</b>
          </div>
          <div class="col-6">
            <div class="badge--group">
              <span class="badge badge-warning badge-dot"></span>
              <span class="badge badge-success badge-dot"></span>
              <span class="badge badge-danger badge-dot"></span>
            </div>
          </div>
          <div class="col-6 text-end">
            <p class="text-danger mb-0"><del>$21.00</del> <span class="text-success fw-bold">$11.00</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ➕ Add more cards here by duplicating -->

</div>


<?php include_once('./include/footer-admin.php'); ?>