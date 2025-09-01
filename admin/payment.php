<?php
$pageTitle = "Payment";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Get all payment methods from the database for the filter
$paymentMethods = ['Cash', 'Credit Card', 'Bank Transfer', 'UPI', 'Cheque'];

// Handle filter parameter
$currentFilter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Base query with joins to invoices, users, and customer tables
$base_query = "
    SELECT 
        payments.*, 
        invoices.id as invoice_id,
        users.name AS created_by_name,
        customer.email AS client_email
    FROM payments 
    LEFT JOIN invoices ON payments.invoice_id = invoices.id
    LEFT JOIN users ON payments.created_by = users.id
    LEFT JOIN customer ON invoices.customer_id = customer.id
";

// Build the final query based on the filter
if ($currentFilter === 'all') {
  $select_query = $base_query;
} else {
  // Sanitize the filter input to prevent SQL injection
  $safeFilter = mysqli_real_escape_string($DB->conn, $currentFilter);
  $select_query = $base_query . " WHERE payments.payment_method = '$safeFilter'";
}

$payment_data = $DB->custom_query($select_query);

?>

<div class="row">
  <div class="seperator-header layout-top-spacing d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">PAYMENT</h4>
    <div class="d-flex align-items-center">
      <a href="payment-add.php" class="btn btn-primary me-2">Add New Payment Record</a>
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
          data-bs-toggle="dropdown" aria-expanded="false">
          <?php echo $currentFilter === 'all' ? 'All Methods' : $currentFilter; ?>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item <?php echo $currentFilter === 'all' ? 'active' : ''; ?>"
              href="payment.php?filter=all">All Methods</a></li>
          <?php foreach ($paymentMethods as $method) { ?>
            <li><a class="dropdown-item <?php echo $currentFilter === $method ? 'active' : ''; ?>"
                href="payment.php?filter=<?php echo urlencode($method); ?>"><?php echo $method; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
    <div class="statbox widget box box-shadow">
      <div class="widget-content widget-content-area">
        <table id="html5-extension" class="table dt-table-hover" style="width:100%">
          <thead>
            <tr>
              <th>Invoice Number</th>
              <th>Client Email</th>
              <th>Payment Date</th>
              <th>Amount Paid</th>
              <th>Payment Method</th>
              <th>Reference Number</th>
              <th>Notes</th>
              <th>Created At</th>
              <th>Created By</th>
              <th>Open</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($payment_data) {
              while ($row = mysqli_fetch_assoc($payment_data)) { ?>
                <tr>
                  <td>
                    <a href="./invoice-preview.php?id=<?php echo $row['invoice_id']; ?>" class="btn btn-sm" target="_blank"
                      rel="noopener noreferrer"><?php echo $row['invoice_id']; ?></a>
                  </td>
                  <td><?php echo $row['client_email']; ?></td>
                  <td><?php echo date('d M Y', strtotime($row['payment_date'])); ?></td>
                  <td>$<?php echo number_format($row['amount_paid'], 2); ?></td>
                  <td><?php echo $row['payment_method']; ?></td>
                  <td><?php echo $row['reference_number'] ?: 'N/A'; ?></td>
                  <td><?php echo $row['notes'] ?: 'N/A'; ?></td>
                  <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                  <td><?php echo trim($row['created_by_name']) ?: 'N/A'; ?></td>
                  <td>
                    <a href="payment-view.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Open</a>
                  </td>
                  <td>
                    <a href="payment-add.php?u_id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                  </td>
                </tr>
              <?php }
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once './include/footer-admin.php'; ?>