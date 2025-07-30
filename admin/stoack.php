<?php
$pageTitle = "Stock Management";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Get all stock records with product information
$stockData = [];
$result = $DB->read("stock", array(
    'order_by' => 'product_id ASC'
));

if ($result && mysqli_num_rows($result) > 0) {
    while ($stock = mysqli_fetch_assoc($result)) {
        // Get product name for each stock record
        $productRes = $DB->read("products", array(
            'where' => array('id' => array('=' => $stock['product_id']))
        ));

        if ($productRes && mysqli_num_rows($productRes) > 0) {
            $product = mysqli_fetch_assoc($productRes);
            $stock['product_name'] = $product['name'];

            // Calculate pending stock (current - sold - dead)
            $sold = $stock['sold_stock'] ?? 0;
            $dead = $stock['dead_stock'] ?? 0;
            $stock['pending_stock'] = $stock['current_stock'] - $sold - $dead;

            $stockData[] = $stock;
        }
    }
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];
    $DB->delete('stock', 'id', $deleteId);
    $_SESSION['message'] = "Stock record deleted successfully";
    header("Location: stoack.php");
    exit;
}
?>

<div class="row">
    <div class="seperator-header layout-top-spacing">
        <h4 class="">Stock Management</h4>
        <a href="stoack-add.php" class="btn btn-primary">Add New Stock</a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <div class="mb-4">
                    <!-- <a href="stoack-add.php" class="btn btn-primary">Add New Stock</a> -->
                </div>

                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Initial Stock</th>
                            <th>Sold Stock</th>
                            <th>Dead Stock</th>
                            <th>Pending Stock</th>
                            <th>Last Updated</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stockData as $stock): ?>
                            <tr>
                                <td><?= $stock['product_name'] ?></td>
                                <td><?= $stock['current_stock'] ?></td>
                                <td><?= $stock['sold_stock'] ?? '0' ?></td>
                                <td><?= $stock['dead_stock'] ?? '0' ?></td>
                                <td><?= max(0, $stock['pending_stock']) ?></td>
                                <td><?= date('M d, Y H:i', strtotime($stock['last_updated'])) ?></td>
                                <td>
                                    <a href="stoack-edit.php?id=<?= $stock['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                </td>
                                <td>
                                    <button onclick="confirmDelete(<?= $stock['id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($stockData)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No stock records found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this stock record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a id="deleteConfirmBtn" href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(stockId) {
        const deleteBtn = document.getElementById('deleteConfirmBtn');
        deleteBtn.href = `stoack.php?delete_id=${stockId}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>

<?php include('./include/footer-admin.php'); ?>