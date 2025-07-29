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
    // Get all products with disabled status
    $res = $DB->read("products");
    if ($res === false) {
        throw new Exception("Query failed");
    }

    if (mysqli_num_rows($res) > 0) {
        $products = mysqli_fetch_all($res, MYSQLI_ASSOC);
        
        // Get stock information for each product
        foreach ($products as &$product) {
            // Decode images
            if (!empty($product['images'])) {
                $product['images'] = json_decode($product['images'], true);
            } else {
                $product['images'] = ['../images/placeholder.jpg'];
            }
            
            // Only calculate stock status if product is not disabled
            if (!$product['disabled']) {
                $stockRes = $DB->read("stock", [
                    'where' => ['product_id' => ['=' => $product['id']]]
                ]);

                if ($stockRes && mysqli_num_rows($stockRes) > 0) {
                    $stock = mysqli_fetch_assoc($stockRes);
                    $sold = $stock['sold_stock'] ?? 0;
                    $dead = $stock['dead_stock'] ?? 0;
                    $product['in_stock'] = ($stock['current_stock'] - $sold - $dead) > 0 ? 1 : 0;
                } else {
                    $product['in_stock'] = 0;
                }
            }
        }
        unset($product);
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

<div class="container-fluid">
    <!-- Search and Filter UI -->
    <div class="row mb-4 align-items-center justify-content-between">
        <div class="col-lg-6 d-flex align-items-center">
            <form method="get" class="d-flex flex-grow-1 gap-2">
                <input type="text" name="search" class="form-control" style="max-width: 300px;"
                       placeholder="Search products..." value="<?= htmlspecialchars($searchTerm) ?>">
                <button type="submit" class="btn btn-primary px-3">Search</button>
            </form>
            <a href="products-add.php" class="btn btn-success ms-2">Add New Product</a>
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
                </div>
            </form>
        </div>
    </div>

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
                    <div class="card h-100">
                        <!-- Product Image Carousel -->
                        <div id="carousel-<?= $product['id'] ?>" class="carousel slide" data-bs-ride="carousel" style="height: 180px; overflow: hidden;">
                            <div class="carousel-inner h-100">
                                <?php foreach ($product['images'] as $index => $image): ?>
                                    <div class="carousel-item h-100 <?= $index === 0 ? 'active' : '' ?>">
                                        <img src="<?= htmlspecialchars($image) ?>" 
                                             class="d-block w-100 h-100 object-fit-cover" 
                                             alt="<?= htmlspecialchars($product['name']) ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($product['images']) > 1): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?= $product['id'] ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?= $product['id'] ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Product Info -->
                        <div class="card-body">
                            <h6 class="card-title"><?= htmlspecialchars($product['name']) ?></h6>
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
                            
                            <!-- Stock Badge -->
                            <div class="mb-2">
                                <?php if ($product['disabled']): ?>
                                    <span class="badge bg-secondary">DISABLED</span>
                                <?php else: ?>
                                    <span class="badge <?= $product['in_stock'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $product['in_stock'] ? 'IN STOCK' : 'OUT OF STOCK' ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Edit and Delete Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="products-add.php?u_id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <button onclick="confirmDelete(<?= $product['id'] ?>)" class="btn btn-sm btn-outline-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
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
    function selectCategory(value) {
        document.getElementById('categoryInput').value = value;
        document.getElementById('filterForm').submit();
    }

    function selectSort(value) {
        document.getElementById('sortInput').value = value;
        document.getElementById('filterForm').submit();
    }

    function confirmDelete(productId) {
        const deleteBtn = document.getElementById('deleteConfirmBtn');
        deleteBtn.href = `products.php?delete_id=${productId}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>

<?php include_once('./include/footer-admin.php'); ?>