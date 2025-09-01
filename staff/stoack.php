<?php
$pageTitle = "Stock Management";
require_once './include/header-staff.php';
require_once './include/sidebar-staff.php';

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
        
        // Check if product is out of stock
        $isOutOfStock = ($pendingStock <= 0);
        
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
            'last_updated' => $stock['last_updated'],
            'is_out_of_stock' => $isOutOfStock
        );
    }
}
?>

<div class="row pt-4">

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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stockData as $stock) { 
                            // Determine row class based on conditions
                            $rowClass = '';
                            if ($stock['is_out_of_stock']) {
                                $rowClass = 'table-danger'; // Out of stock - highest priority
                            } elseif (!$stock['product_exists']) {
                                $rowClass = 'table-danger'; // Product deleted
                            } elseif ($stock['product_disabled']) {
                                $rowClass = 'table-warning'; // Product disabled
                            }
                        ?>
                            <tr class="<?php echo $rowClass ?>">
                                <td>
                                    <?php echo $stock['product_name']; ?>
                                    <?php if ($stock['is_out_of_stock']) { ?>
                                        <span class="badge bg-danger">(Out of Stock)</span>
                                    <?php } elseif (!$stock['product_exists']) { ?>
                                        <span class="badge bg-danger">(Product Deleted)</span>
                                    <?php } elseif ($stock['product_disabled']) { ?>
                                        <span class="badge bg-warning text-dark">(Product Discontinued)</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $stock['current_stock'] ?></td>
                                <td><?php echo $stock['sold_stock'] ?></td>
                                <td><?php echo $stock['dead_stock'] ?></td>
                                <td><?php echo $stock['pending_stock'] ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($stock['last_updated'])) ?></td>
                            </tr>
                        <?php } ?>

                        <?php if (empty($stockData)) { ?>
                            <tr>
                                <td colspan="6" class="text-center">No stock records found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once './include/footer-staff.php'; ?>