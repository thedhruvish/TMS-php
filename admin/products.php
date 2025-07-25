<?php 
$pageTitle = "Products";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Handle delete action
if (isset($_GET['delete_id'])) {
    try {
        $deleteId = (int)$_GET['delete_id'];
        $result = $DB->delete("products", "id", $deleteId);
        if ($result) {
            $_SESSION['message'] = "Product deleted successfully";
        } else {
            $_SESSION['message'] = "Error deleting product";
        }
        header("Location: products.php");
        exit();
    } catch (Exception $e) {
        $error = "Error deleting product: " . $e->getMessage();
    }
}


// Database connection and data fetching
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
    $filteredProducts = array_filter($filteredProducts, function($product) use ($searchTerm) {
        return stripos($product['name'], $searchTerm) !== false || 
               stripos($product['description'], $searchTerm) !== false;
    });
}

if (!empty($categoryFilter)) {
    $filteredProducts = array_filter($filteredProducts, function($product) use ($categoryFilter) {
        return strcasecmp($product['category'], $categoryFilter) === 0;
    });
}

// Sort products
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
?>

<!-- Search and Filter UI -->
<div class="row mb-4 align-items-center justify-content-between">
  <div class="col-lg-6 d-flex align-items-center">
    <form method="get" class="d-flex flex-grow-1 gap-2">
      <input type="text" name="search" class="form-control" style="max-width: 300px;"
             placeholder="Search products..." value="<?= htmlspecialchars($searchTerm) ?>">
      <button type="submit" class="btn btn-primary px-3">Search</button>
    </form>
  </div>

  <div class="col-lg-6 text-lg-end text-start mt-3 mt-lg-0">
    <form id="filterForm" method="get" class="d-inline-block w-100">
      <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
      <input type="hidden" name="category" id="categoryInput" value="<?= htmlspecialchars($categoryFilter) ?>">
      <input type="hidden" name="sort" id="sortInput" value="<?= htmlspecialchars($sortBy) ?>">

      <div class="d-flex justify-content-lg-end align-items-center gap-2 flex-wrap">
        <!-- Category Dropdown -->
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $categoryFilter ?: 'All Categories' ?>
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="selectCategory('')">All Categories</a></li>
            <?php foreach ($categories as $cat): if (!empty($cat)): ?>
              <li><a class="dropdown-item" href="#" onclick="selectCategory('<?= htmlspecialchars($cat) ?>')"><?= htmlspecialchars($cat) ?></a></li>
            <?php endif; endforeach; ?>
          </ul>
        </div>

        <!-- Sort Dropdown -->
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php
              echo match($sortBy) {
                'price_low' => 'Price: Low to High',
                'price_high' => 'Price: High to Low',
                default => 'Newest'
              };
            ?>
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="selectSort('newest')">Newest</a></li>
            <li><a class="dropdown-item" href="#" onclick="selectSort('price_low')">Price: Low to High</a></li>
            <li><a class="dropdown-item" href="#" onclick="selectSort('price_high')">Price: High to Low</a></li>
          </ul>
        </div>

        <!-- Share Selected -->
        <button type="button" class="btn btn-secondary" onclick="shareSelectedProducts()">Share Selected</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts for Dropdown Selection -->
<script>
  function selectCategory(value) {
    document.getElementById('categoryInput').value = value;
    document.getElementById('filterForm').submit();
  }

  function selectSort(value) {
    document.getElementById('sortInput').value = value;
    document.getElementById('filterForm').submit();
  }

  function shareSelectedProducts() {
    const selected = Array.from(document.querySelectorAll('.product-check:checked'))
                          .map(cb => cb.value);
    if (selected.length === 0) {
      alert("No products selected.");
      return;
    }
    const shareLink = "https://yourdomain.com/share?products=" + selected.join(',');
    navigator.clipboard.writeText(shareLink)
      .then(() => alert("Link copied: " + shareLink))
      .catch(() => alert("Failed to copy link."));
  }
</script>

<!-- Messages -->
<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success alert-dismissible fade show">
  <?= $_SESSION['message'] ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php unset($_SESSION['message']); endif; ?>

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
          
          <!-- Category Badge -->
          <?php if(!empty($product['category'])): ?>
            <span class="badge bg-primary position-absolute top-0 end-0 m-2 z-2">
              <?= htmlspecialchars($product['category']) ?>
            </span>
          <?php endif; ?>

          <!-- Product Image -->
          <img src="<?= !empty($product['image']) ? htmlspecialchars($product['image']) : '../images/placeholder.jpg' ?>" 
               class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>"
               style="height: 180px; object-fit: cover;">

          <!-- Product Info -->
          <div class="card-body pb-2">
            <h6 class="card-title mb-2"><?= htmlspecialchars($product['name']) ?></h6>
            <div class="d-flex align-items-center mb-2">
              <?php if(!empty($product['sale_price']) && $product['sale_price'] > 0 && $product['sale_price'] < $product['regular_price']): ?>
                <span class="text-danger text-decoration-line-through me-2">
                  $<?= number_format($product['regular_price'], 2) ?>
                </span>
                <span class="text-success fw-bold">
                  $<?= number_format($product['sale_price'], 2) ?>
                </span>
              <?php else: ?>
                <span class="text-success fw-bold">
                  $<?= number_format($product['regular_price'], 2) ?>
                </span>
              <?php endif; ?>
            </div>
            
            <!-- Edit and Delete Buttons -->
           <div class="d-flex justify-content-between mt-2">
              <a href="products-add.php?u_id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <button onclick="confirmDelete(<?= $product['id'] ?>)" class="btn btn-sm btn-outline-danger">Delete</button>
            </div>
          </div>

          <!-- Stock Badge -->
          <div class="card-footer bg-transparent p-2">
            <span class="badge <?= $product['in_stock'] ? 'bg-success' : 'bg-danger' ?>">
              <?= $product['in_stock'] ? 'IN STOCK' : 'OUT OF STOCK' ?>
            </span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this product?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a id="deleteConfirmBtn" href="#" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmDelete(productId) {
    const deleteBtn = document.getElementById('deleteConfirmBtn');
    deleteBtn.href = `products.php?delete_id=${productId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
  }
</script>

<?php include_once('./include/footer-admin.php'); ?>