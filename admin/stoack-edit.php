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
    $deadStock = (int)$_POST['dead_stock'];
    
    // Get the original sold stock value from database to prevent tampering
    $originalStockRes = $DB->read("stock", [
        'where' => ['id' => ['=' => $stockId]]
    ]);
    $originalStock = mysqli_fetch_assoc($originalStockRes);
    $soldStock = $originalStock['sold_stock'];

    // Update stock record
    $DB->update(
        'stock',
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
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <span>Sold stock is automatically calculated from invoices and cannot be manually edited</span>
                </div>
            </div>
            
            <form method="POST">
                <input type="hidden" name="stock_id" value="<?php echo $stock['id'] ?>">

                <div class="card mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="card-title mb-0">Product Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <label class="form-label fw-bold">Product Name</label>
                                <div class="form-control-plaintext bg-light p-3 rounded border">
                                    <h5 class="mb-0 text-primary"><?php echo htmlspecialchars($stock['product_name']) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="card-title mb-0">Stock Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Current Stock</label>
                                <input type="number" class="form-control form-control-lg" name="current_stock"
                                    value="<?php echo $stock['current_stock'] ?>" required min="0">
                                <small class="form-text text-muted">This should reflect physical inventory counts</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Sold Stock</label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-lg bg-light" name="sold_stock"
                                        value="<?php echo $stock['sold_stock'] ?>" readonly>
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Auto-calculated from invoices</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <label class="form-label fw-bold">Dead Stock (Damaged/Lost/Expired)</label>
                                <input type="number" class="form-control" name="dead_stock"
                                    value="<?php echo $stock['dead_stock'] ?>" min="0">
                                <small class="form-text text-muted">Record items that are no longer sellable</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success btn-lg w-100 py-3">
                            <i class="fas fa-save me-2"></i> UPDATE STOCK
                        </button>
                    </div>
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

<?php require_once './include/footer-admin.php'; ?>