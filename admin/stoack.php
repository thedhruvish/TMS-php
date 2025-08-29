<?php
$pageTitle = "Stock Management";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Get all stock records with product information
$stockData = [];
$result = $DB->read("stock", array(
    'order_by' => 'COALESCE(product_id, 999999) ASC' // Puts NULL product_ids last
));

if ($result && mysqli_num_rows($result) > 0) {
    while ($stock = mysqli_fetch_assoc($result)) {
        $productExists = false;
        $productDisabled = false;
        $productName = $stock['product_name'] ?? 'Deleted Product';

        // Only check products table if product_id exists
        if (!empty($stock['product_id'])) {
            $productRes = $DB->read("products", array(
                'where' => array('id' => array('=' => $stock['product_id']))
            ));

            if ($productRes && mysqli_num_rows($productRes) > 0) {
                $product = mysqli_fetch_assoc($productRes);
                $productExists = true;
                $productDisabled = $product['disabled'] ?? false;

                // Update product name if different from stored name
                if (($stock['product_name'] ?? '') !== $product['name']) {
                    $DB->update(
                        'stock',
                        array('product_name'),  // columns array
                        array($product['name']),  // values array
                        'id',  // where column
                        $stock['id']  // where value
                    );
                    $productName = $product['name'];
                } else {
                    $productName = $stock['product_name'];
                }
            }
        }

        // Calculate pending stock (current - sold - dead)
        $sold = $stock['sold_stock'] ?? 0;
        $dead = $stock['dead_stock'] ?? 0;
        $pendingStock = $stock['current_stock'] - $sold - $dead;
        if ($pendingStock < 100) {
            // send_message_TG("Low Stock Alert\nProduct Name: $productName\nCurrent Stock: $stock[current_stock]\nPending Stock: $pendingStock");
        }
        $stockData[] = array(
            'id' => $stock['id'],
            'product_id' => $stock['product_id'],
            'product_name' => $productName,
            'product_exists' => $productExists,
            'product_disabled' => $productDisabled,
            'current_stock' => $stock['current_stock'],
            'sold_stock' => $stock['sold_stock'] ?? 0,
            'dead_stock' => $stock['dead_stock'] ?? 0,
            'pending_stock' => $pendingStock > 0 ? $pendingStock : 0,
            'last_updated' => $stock['last_updated']
        );
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
    <div class="seperator-header layout-top-spacing mb-4">
        <h4 class="mb-0">Stock Management</h4>
        <a href="stoack-add.php" class="btn btn-primary">Add New Stock</a>
    </div>

    <?php if (isset($_SESSION['message'])) { ?>
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php } ?>

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Initial Stock</th>
                            <th>Sold Stock</th>
                            <th>Dead Stock</th>
                            <th>Pending Stock</th>
                            <th>Last Updated</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stockData as $stock) { 
                            $isOutOfStock = $stock['pending_stock'] <= 0;
                            $rowClass = '';
                            if ($isOutOfStock) {
                                $rowClass = 'table-danger';
                            } elseif (!$stock['product_exists']) {
                                $rowClass = 'table-danger';
                            } elseif ($stock['product_disabled']) {
                                $rowClass = 'table-warning';
                            }
                        ?>
                            <tr class="<?php echo $rowClass ?>">
                                <td>
                                    <?php echo $stock['product_name']; ?>
                                    <?php if (!$stock['product_exists']) { ?>
                                        <span class="badge bg-danger">(Product Deleted)</span>
                                    <?php } elseif ($stock['product_disabled']) { ?>
                                        <span class="badge bg-warning text-dark">(Product Discontinued)</span>
                                    <?php } ?>
                                    <?php if ($isOutOfStock) { ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $stock['current_stock'] ?></td>
                                <td><?php echo $stock['sold_stock'] ?></td>
                                <td><?php echo $stock['dead_stock'] ?></td>
                                <td>
                                    <?php echo $stock['pending_stock'] ?>
                                    <?php if ($stock['pending_stock'] < 10 && $stock['pending_stock'] > 0) { ?>
                                        <span class="badge bg-warning text-dark ms-1">Low Stock</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($stock['last_updated'])) ?></td>
                                <td>
                                    <?php if ($isOutOfStock) { ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php } elseif (!$stock['product_exists']) { ?>
                                        <span class="badge bg-danger">Product Deleted</span>
                                    <?php } elseif ($stock['product_disabled']) { ?>
                                        <span class="badge bg-warning text-dark">Discontinued</span>
                                    <?php } else { ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="stoack-edit.php?id=<?php echo $stock['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                </td>
                                <td>
                                    <button onclick="confirmDelete(<?php echo $stock['id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if (empty($stockData)) { ?>
                            <tr>
                                <td colspan="9" class="text-center">No stock records found</td>
                            </tr>
                        <?php } ?>
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

<?php require_once './include/footer-admin.php'; ?>