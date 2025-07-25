<?php
$pageTitle = "Add Stock";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $initialStock = isset($_POST['initial_stock']) ? (int)$_POST['initial_stock'] : 0;

    if ($productId > 0 && $initialStock >= 0) {
        // Check if stock record already exists for this product
        $existingStock = $DB->read('stock', [
            'where' => ['product_id' => ['=' => $productId]]
        ]);

        if ($existingStock && mysqli_num_rows($existingStock) > 0) {
            // Update existing stock
            $stockData = mysqli_fetch_assoc($existingStock);
            $newStock = $stockData['current_stock'] + $initialStock;

            $DB->update(
                'stock',
                ['current_stock'],
                [$newStock],
                'product_id',
                $productId
            );

            $_SESSION['message'] = "Stock updated successfully!";
        } else {
            // Create new stock record
            $DB->create(
                'stock',
                ['product_id', 'current_stock'],
                [$productId, $initialStock]
            );

            $_SESSION['message'] = "Stock added successfully!";
        }

        header("Location: stoack-add.php?success=1");
        exit;
    } else {
        $error = "Invalid product or stock quantity";
    }
}

// Get all products for dropdown
$products = [];
$productsRes = $DB->read("products", ['order_by' => 'name ASC']);
if ($productsRes && mysqli_num_rows($productsRes) > 0) {
    $products = mysqli_fetch_all($productsRes, MYSQLI_ASSOC);
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
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label>Product</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">Select a product</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>">
                                    <?= $product['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label>Initial Stock</label>
                        <input type="number" class="form-control" name="initial_stock"
                            placeholder="Enter initial stock quantity"
                            min="0" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <button type="submit" class="btn btn-success w-100">SUBMIT</button>
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