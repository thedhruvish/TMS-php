<?php 
$pageTitle = "Products";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. First verify we can connect to database
try {
    $testConnection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$testConnection) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }
    mysqli_close($testConnection);
} catch (Exception $e) {
    die('<div class="alert alert-danger m-3">'.$e->getMessage().'</div>');
}

// 2. Get products from database
$products = [];
$error = null;

try {
    $res = $DB->read("products");
    if ($res === false) {
        throw new Exception("Query failed");
    }
    
    if (mysqli_num_rows($res) > 0) {
        $products = mysqli_fetch_all($res, MYSQLI_ASSOC);
        
        // Debug output - view in browser's developer tools
        echo "<!-- DEBUG: Products Data \n";
        print_r($products);
        echo "\n-->";
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Handle filters
$searchTerm = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$sortBy = $_GET['sort'] ?? 'newest';

// Filter and sort logic
$filteredProducts = $products; // Start with all products

if (!empty($searchTerm)) {
    $filteredProducts = array_filter($filteredProducts, function($product) use ($searchTerm) {
        return stripos($product['name'], $searchTerm) !== false || 
               stripos($product['description'], $searchTerm) !== false ||
               stripos($product['product_code'], $searchTerm) !== false;
    });
}

if (!empty($categoryFilter)) {
    $filteredProducts = array_filter($filteredProducts, function($product) use ($categoryFilter) {
        return strcasecmp($product['category'], $categoryFilter) === 0;
    });
}

// Sorting
usort($filteredProducts, function($a, $b) use ($sortBy) {
    switch ($sortBy) {
        case 'price_low':
            return $a['regular_price'] <=> $b['regular_price'];
        case 'price_high':
            return $b['regular_price'] <=> $a['regular_price'];
        default: // newest
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    }
});

// Get unique categories
$categories = array_unique(array_column($products, 'category'));
$categories = array_filter($categories); // Remove empty values
sort($categories);
?>

<!-- Search and Filter UI (unchanged from your original) -->
<div class="row mb-4 align-items-center justify-content-between">
  <div class="col-md-6 d-flex align-items-center">
    <form method="get" class="w-100">
      <input type="text" name="search" class="form-control w-100" 
             placeholder="Search products..." value="<?= htmlspecialchars($searchTerm) ?>" style="max-width: 250px;">
    </form>
  </div>

  <div class="col-md-6 text-md-end text-start mt-3 mt-md-0">
    <form method="get" class="d-inline-flex gap-2 flex-wrap justify-content-md-end align-items-center w-100">
      <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
      
      <select name="category" class="form-select w-auto" style="min-width: 160px;" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <?php foreach($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>" <?= $categoryFilter === $cat ? 'selected' : '' ?>>
            <?= htmlspecialchars(ucfirst($cat)) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select name="sort" class="form-select w-auto" style="min-width: 140px;" onchange="this.form.submit()">
        <option value="newest" <?= $sortBy === 'newest' ? 'selected' : '' ?>>Newest</option>
        <option value="price_low" <?= $sortBy === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="price_high" <?= $sortBy === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
      </select>

      <button id="shareSelected" class="btn btn-primary px-4 py-2">Share Selected</button>
    </form>
  </div>
</div>

<!-- Error Display -->
<?php if ($error): ?>
<div class="alert alert-danger">
  Database Error: <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<!-- Product Grid -->
<div class="row">
  <?php if(empty($filteredProducts)): ?>
    <div class="col-12">
      <div class="alert alert-info">
        No products found. 
        <?php if(!empty($searchTerm) || !empty($categoryFilter)): ?>
          <a href="products.php" class="alert-link">Clear filters</a>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>
    <?php foreach($filteredProducts as $product): ?>
      <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
        <div class="card style-6 h-100 position-relative overflow-hidden">
          
          <!-- Checkbox -->
          <div class="position-absolute top-0 start-0 m-2 z-2">
            <input type="checkbox" class="form-check-input product-check" value="<?= $product['id'] ?>">
          </div>
          
          <!-- Status Badge -->
          <span class="badge <?= $product['in_stock'] ? 'badge-primary' : 'badge-danger' ?> position-absolute top-0 end-0 m-2 z-2">
            <?= $product['in_stock'] ? 'IN STOCK' : 'OUT OF STOCK' ?>
          </span>

          <!-- Product Image -->
          <img src="<?= !empty($product['image']) ? htmlspecialchars($product['image']) : '../images/placeholder.jpg' ?>" 
               class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>"
               style="height: 180px; object-fit: cover;">

          <div class="card-footer">
            <div class="row">
              <div class="col-12 mb-2 text-truncate">
                <b><?= htmlspecialchars($product['name']) ?></b>
              </div>
              <div class="col-6">
                <span class="badge bg-primary"><?= htmlspecialchars($product['category']) ?></span>
              </div>
              <div class="col-6 text-end">
                <?php if($product['sale_price'] && $product['sale_price'] < $product['regular_price']): ?>
                  <p class="text-danger mb-0">
                    <del>$<?= number_format($product['regular_price'], 2) ?></del> 
                    <span class="text-success fw-bold">$<?= number_format($product['sale_price'], 2) ?></span>
                  </p>
                <?php else: ?>
                  <p class="text-success fw-bold mb-0">$<?= number_format($product['regular_price'], 2) ?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php include_once('./include/footer-admin.php'); ?>