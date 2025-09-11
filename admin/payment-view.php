<?php

require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: payment.php");
  exit();
}

$payment_id = $_GET['id'];

$query = "
    SELECT 
        payments.id AS payment_id,
        payments.notes AS payment_notes,
        payments.created_at AS payment_created_at,
        payments.*, 
        invoices.*, 
        invoices.notes AS invoice_notes,
        CONCAT(customer.first_name,' ',customer.last_name) AS client_name,
        customer.email AS client_email,
        customer.phone AS client_phone,
        users.name AS created_by_name
    FROM payments
    LEFT JOIN invoices ON payments.invoice_id = invoices.id
    LEFT JOIN customer ON invoices.customer_id = customer.id
    LEFT JOIN users ON payments.created_by = users.id
    WHERE payments.id = $payment_id
";

$result = $DB->custom_query($query);

if (mysqli_num_rows($result) == 0) {
  echo "<div class='alert alert-danger m-3'>Payment not found.</div>";
  require_once './include/footer-admin.php';
  exit();
}

$data = mysqli_fetch_assoc($result);
?>

<div class="container d-flex align-items-center justify-content-center pt-5 pb-5" style="min-height: 100vh;">
  <div class="card shadow-lg w-100">
    <div class="card-body">
      <h3 class="text-center mb-4">Payment Details</h3>

      <h5 class="mb-3">Payment Information</h5>
      <table class="table table-bordered table-striped">
        <tr>
          <th style="width: 30%;">Payment ID</th>
          <td><?php echo $data['payment_id']; ?></td>
        </tr>
        <tr>
          <th>Invoice Number</th>
          <td><?php echo $data['invoice_id']; ?></td>
        </tr>
        <tr>
          <th>Payment Date</th>
          <td><?php echo date('F j, Y', strtotime($data['payment_date'])); ?></td>
        </tr>
        <tr>
          <th>Amount Paid</th>
          <td>₹<?php echo number_format($data['amount_paid'], 2); ?></td>
        </tr>
        <tr>
          <th>Payment Method</th>
          <td><?php echo $data['payment_method']; ?></td>
        </tr>
        <tr>
          <th>Reference Number</th>
          <td><?php echo $data['reference_number'] ?: 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Payment Notes</th>
          <td><?php echo $data['payment_notes'] ?: 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Created By</th>
          <td><?php echo $data['created_by_name'] ?? 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Created At</th>
          <td><?php echo date('F j, Y, g:i a', strtotime($data['payment_created_at'])); ?></td>
        </tr>
      </table>

      <h5 class="mt-4 mb-3">Invoice Information</h5>
      <table class="table table-bordered table-striped">

        <tr>
          <th>Client Name</th>
          <td><?php echo $data['client_name']; ?></td>
        </tr>
        <tr>
          <th>Client Email</th>
          <td><?php echo $data['client_email']; ?></td>
        </tr>
        <tr>
          <th>Client Phone</th>
          <td><?php echo $data['client_phone']; ?></td>
        </tr>
        <tr>
          <th>Invoice Date</th>
          <td><?php echo date('F j, Y', strtotime($data['invoice_date'])); ?></td>
        </tr>
        <tr>
          <th>Due Date</th>
          <td><?php echo date('F j, Y', strtotime($data['due_date'])); ?></td>
        </tr>
        <tr>
          <th>Subtotal</th>
          <td>₹<?php echo number_format($data['subtotal'], 2); ?></td>
        </tr>
        <tr>
          <th>Discount</th>
          <td>₹<?php echo number_format($data['discount'], 2); ?></td>
        </tr>
        <tr>
          <th>Total</th>
          <td class="fw-bold">₹<?php echo number_format($data['total'], 2); ?></td>
        </tr>
      </table>

      <div class="text-center">
        <a href="payment.php" class="btn btn-primary mt-3">Back to Payment List</a>
      </div>
    </div>
  </div>
</div>

<?php require_once './include/footer-admin.php'; ?>