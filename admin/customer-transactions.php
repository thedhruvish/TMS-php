<?php
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Get customer ID from URL
$customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get customer details
$customer = $DB->read("customer", ['where' => ['id' => ['=' => $customer_id]]]);
if (!$customer || mysqli_num_rows($customer) === 0) {
    echo "<div class='alert alert-danger'>Customer not found</div>";
    require_once './include/footer-admin.php';
    exit();
}
$customer_data = mysqli_fetch_assoc($customer);

// Initialize variables
$total_invoiced = 0;
$total_paid = 0;

// Get all invoices for this customer
$invoices_sql = "
    SELECT i.*, SUM(p.amount_paid) as paid_amount
    FROM invoices i 
    LEFT JOIN payments p ON p.invoice_id = i.id 
    WHERE i.customer_id = $customer_id 
    GROUP BY i.id 
    ORDER BY i.created_at DESC
";
$invoices = $DB->custom_query($invoices_sql);

// Get all payments for this customer
$payments_sql = "
    SELECT p.*, i.id as invoice_number
    FROM payments p 
    JOIN invoices i ON p.invoice_id = i.id 
    WHERE i.customer_id = $customer_id 
    ORDER BY p.payment_date DESC
";
$payments = $DB->custom_query($payments_sql);

// Get product order details for this customer - SIMPLIFIED FIX
$products_sql = "
    SELECT 
        i.id as invoice_id,
        i.created_at as order_date,
        ii.product_id,
        p.name as product_name,
        ii.rate as unit_price,
        ii.quantity,
        ii.amount as total_amount
    FROM invoices i
    JOIN invoice_items ii ON i.id = ii.invoice_id
    LEFT JOIN products p ON ii.product_id = p.id
    WHERE i.customer_id = $customer_id
    ORDER BY i.created_at DESC, ii.id ASC
";
$product_orders = $DB->custom_query($products_sql);

// Calculate totals from invoices
if ($invoices && mysqli_num_rows($invoices) > 0) {
    while ($invoice = mysqli_fetch_assoc($invoices)) {
        $total_invoiced += floatval($invoice['total']);
        $total_paid += floatval($invoice['paid_amount'] ?: 0);
    }
    // Reset pointer to beginning for later use
    mysqli_data_seek($invoices, 0);
}

?>

<div class="row">
    <div class="seperator-header layout-top-spacing mb-4">
        <h4 class="mb-0">Transaction History - <?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?></h4>
        <a href="customer.php" class="btn btn-secondary">Back to Customers</a>
    </div>

    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Customer Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> <?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $customer_data['email']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $customer_data['phone']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Invoiced:</strong> ₹<?php echo number_format($total_invoiced, 2); ?></p>
                        <p><strong>Total Paid:</strong> ₹<?php echo number_format($total_paid, 2); ?></p>
                        <p><strong>Balance Due:</strong> ₹<?php echo number_format($total_invoiced - $total_paid, 2); ?></p>
                        <p><strong>Country:</strong> <?php echo $customer_data['country']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- Product Orders Section -->
    <div class="col-12 mb-4">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Product Order History</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Invoice #</th>
                                <th>Product</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($product_orders && mysqli_num_rows($product_orders) > 0) {
                                while ($order = mysqli_fetch_assoc($product_orders)) {
                            ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                    <td><a href="invoice-add.php?u_id=<?php echo $order['invoice_id']; ?>">#<?php echo $order['invoice_id']; ?></a></td>
                                    <td>
                                        <?php 
                                        // Handle both numeric product IDs and custom product names
                                        if (!empty($order['product_id'])) {
                                            if (is_numeric($order['product_id']) && !empty($order['product_name'])) {
                                                // Numeric ID with product name from products table
                                                echo $order['product_name'];
                                            } elseif (is_numeric($order['product_id']) && empty($order['product_name'])) {
                                                // Numeric ID but product doesn't exist in products table
                                                echo 'Product #' . $order['product_id'];
                                            } else {
                                                // Custom product name stored directly in product_id
                                                echo $order['product_id'];
                                            }
                                        } else {
                                            echo 'Custom Item';
                                        }
                                        ?>
                                    </td>
                                    <td>₹<?php echo number_format($order['unit_price'], 2); ?></td>
                                    <td><?php echo $order['quantity']; ?></td>
                                    <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                </tr>
                            <?php 
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="6" class="text-center">No product orders found</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Section -->
    <div class="col-xl-6 col-lg-12 col-sm-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Invoices</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($invoices && mysqli_num_rows($invoices) > 0) {
                                while ($invoice = mysqli_fetch_assoc($invoices)) {
                                    $paid = floatval($invoice['paid_amount'] ?: 0);
                                    $balance = floatval($invoice['total']) - $paid;
                                    $status = $paid >= floatval($invoice['total']) ? 'Paid' : 'Pending';
                            ?>
                                <tr>
                                    <td><a href="invoice-add.php?u_id=<?php echo $invoice['id']; ?>">#<?php echo $invoice['id']; ?></a></td>
                                    <td><?php echo date('M d, Y', strtotime($invoice['created_at'])); ?></td>
                                    <td>₹<?php echo number_format($invoice['total'], 2); ?></td>
                                    <td>₹<?php echo number_format($paid, 2); ?></td>
                                    <td>₹<?php echo number_format($balance, 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $status === 'Paid' ? 'success' : 'warning'; ?>">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php 
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="6" class="text-center">No invoices found</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Section -->
    <div class="col-xl-6 col-lg-12 col-sm-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Payment History</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice #</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($payments && mysqli_num_rows($payments) > 0) {
                                while ($payment = mysqli_fetch_assoc($payments)) {
                            ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                                    <td><a href="invoice-add.php?u_id=<?php echo $payment['invoice_id']; ?>">#<?php echo $payment['invoice_number']; ?></a></td>
                                    <td>₹<?php echo number_format($payment['amount_paid'], 2); ?></td>
                                    <td><?php echo $payment['payment_method']; ?></td>
                                    <td><?php echo $payment['reference_number']; ?></td>
                                </tr>
                            <?php 
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center">No payments found</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once './include/footer-admin.php'; ?>