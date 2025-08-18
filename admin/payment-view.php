<?php
$pageTitle = "Payment Details";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: payment.php");
  exit();
}

$payment_id = $_GET['id'];

$query = "
    SELECT 
        payments.*, 
        invoices.*, 
        invoices.notes AS invoice_notes,
        CONCAT(customer.first_name,' ',customer.last_name) AS client_name,
        customer.email AS client_email,
        customer.phone AS client_phone

    FROM payments
    LEFT JOIN invoices ON payments.invoice_id = invoices.id
    LEFT JOIN customer ON invoices.customer_id = customer.id
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

<div class="container d-flex align-items-center justify-content-center pt-5" style="min-height: 100vh;">
  <div class="card shadow-lg w-100">
    <div class="card-body">
      <h3 class="text-center mb-4">Payment Details</h3>

      <h5 class="mb-3">Payment Information</h5>
      <table class="table table-bordered">
        <tr>
          <th>ID</th>
          <td><?php echo $data['id']; ?></td>
        </tr>
        <tr>
          <th>Invoice Number</th>
          <td><?php echo $data['invoice_id']; ?></td>
        </tr>
        <tr>
          <th>Payment Date</th>
          <td><?php echo $data['payment_date']; ?></td>
        </tr>
        <tr>
          <th>Amount Paid</th>
          <td><?php echo $data['amount_paid']; ?></td>
        </tr>
        <tr>
          <th>Payment Method</th>
          <td><?php echo $data['payment_method']; ?></td>
        </tr>
        <tr>
          <th>Reference Number</th>
          <td><?php echo $data['reference_number']; ?></td>
        </tr>
        <tr>
          <th>Notes</th>
          <td><?php echo $data['notes']; ?></td>
        </tr>
        <tr>
          <th>Created At</th>
          <td><?php echo $data['created_at']; ?></td>
        </tr>
      </table>

      <h5 class="mt-4 mb-3">Invoice Information</h5>
      <table class="table table-bordered">
        <tr>
          <th>Invoice Label</th>
          <td><?php echo $data['invoice_label']; ?></td>
        </tr>
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
          <td><?php echo $data['invoice_date']; ?></td>
        </tr>
        <tr>
          <th>Due Date</th>
          <td><?php echo $data['due_date']; ?></td>
        </tr>
        <tr>
          <th>Account Number</th>
          <td><?php echo $data['account_number']; ?></td>
        </tr>
        <tr>
          <th>Bank Name</th>
          <td><?php echo $data['bank_name']; ?></td>
        </tr>
        <tr>
          <th>SWIFT Code</th>
          <td><?php echo $data['swift_code']; ?></td>
        </tr>
        <tr>
          <th>Invoice Notes</th>
          <td><?php echo $data['invoice_notes']; ?></td>
        </tr>
        <tr>
          <th>Subtotal</th>
          <td><?php echo $data['subtotal']; ?></td>
        </tr>
        <tr>
          <th>Discount</th>
          <td><?php echo $data['discount']; ?></td>
        </tr>
        <tr>
          <th>Total</th>
          <td><?php echo $data['total']; ?></td>
        </tr>
      </table>

      <div class="text-center">
        <a href="payment.php" class="btn btn-primary mt-3">Back to Payment List</a>
      </div>
    </div>
  </div>
</div>

<?php require_once './include/footer-admin.php'; ?>