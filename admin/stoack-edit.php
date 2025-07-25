<?php 
$pageTitle = "Edit Stock";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Initialize variables
$stock = [
    'id' => '',
    'product_id' => '',
    'current_stock' => '',
    'sold_stock' => '0',
    'dead_stock' => '0',
    'product_name' => ''
];

// Get product list for dropdown
$products = [];
$productsRes = $DB->read("products");
if ($productsRes && mysqli_num_rows($productsRes) > 0) {
    $products = mysqli_fetch_all($productsRes, MYSQLI_ASSOC);
}

// Check if we're editing an existing stock record
if (isset($_GET['id'])) {
    $stockId = (int)$_GET['id'];
    $stockRes = $DB->read("stock", [
        'where' => ['id' => ['=' => $stockId]]
    ]);
    
    if ($stockRes && mysqli_num_rows($stockRes) > 0) {
        $stock = mysqli_fetch_assoc($stockRes);
        
        // Get product name
        $productRes = $DB->read("products", [
            'where' => ['id' => ['=' => $stock['product_id']]]
        ]);
        if ($productRes && mysqli_num_rows($productRes) > 0) {
            $product = mysqli_fetch_assoc($productRes);
            $stock['product_name'] = $product['name'];
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stockId = (int)$_POST['stock_id'];
    $currentStock = (int)$_POST['current_stock'];
    $soldStock = (int)$_POST['sold_stock'];
    $deadStock = (int)$_POST['dead_stock'];
    
    // Update stock record
    $DB->update('stock', 
        ['current_stock', 'sold_stock', 'dead_stock'],
        [$currentStock, $soldStock, $deadStock],
        'id',
        $stockId
    );
    
    $_SESSION['message'] = "Stock updated successfully";
    header("Location: stoack.php");
    exit;
}
?>

<link href="../layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
<link href="../layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link rel="stylesheet" type="text/css" href="../src/plugins/src/tagify/tagify.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/light/tagify/custom-tagify.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/tagify/custom-tagify.css">
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

<!--  BEGIN CUSTOM STYLE FILE  -->
<link rel="stylesheet" href="../src/assets/css/light/apps/blog-create.css">
<link rel="stylesheet" href="../src/assets/css/dark/apps/blog-create.css">
<!--  END CUSTOM STYLE FILE  -->

<div class="row mb-4 layout-spacing layout-top-spacing">
    <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="widget-content widget-content-area blog-create-section">
            <form method="POST">
                <input type="hidden" name="stock_id" value="<?= $stock['id'] ?>">
                
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label>Product</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($stock['product_name']) ?>" readonly>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label>Initial Stock</label>
                        <input type="number" class="form-control" name="current_stock" 
                               value="<?= $stock['current_stock'] ?>" required min="0">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label>Sold Stock</label>
                        <input type="number" class="form-control" name="sold_stock" 
                               value="<?= $stock['sold_stock'] ?>" min="0">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label>Dead Stock</label>
                        <input type="number" class="form-control" name="dead_stock" 
                               value="<?= $stock['dead_stock'] ?>" min="0">
                    </div>
                </div>

                <div class="row mb-4">
                    <button type="submit" class="btn btn-success w-100">UPDATE STOCK</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../src/plugins/src/editors/quill/quill.js"></script>
<script src="../src/plugins/src/filepond/filepond.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
<script src="../src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>
<script src="../src/plugins/src/tagify/tagify.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<?php include('./include/footer-admin.php'); ?>