<?php
$pageTitle = isset($_GET['u_id']) ? "Edit Payment" : "Add Payment";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$invoice_data = $DB->read("invoices");

// Initialize variables
$invoice_id = $payment_date = $amount_paid = $payment_method = $reference_number = $notes = "";

// Check if update mode
$edit_mode = false;
if (isset($_GET['u_id']) && !empty($_GET['u_id'])) {
    $edit_mode = true;
    $u_id = $_GET['u_id'];
    $payment_res = $DB->read("payments", ['where' => ['id' => ['=' => $u_id]]]);
    if ($payment_res && mysqli_num_rows($payment_res) > 0) {
        $payment_data = mysqli_fetch_assoc($payment_res);
        $invoice_id = $payment_data['invoice_id'];
        $payment_date = $payment_data['payment_date'];
        $amount_paid = $payment_data['amount_paid'];
        $payment_method = $payment_data['payment_method'];
        $reference_number = $payment_data['reference_number'];
        $notes = $payment_data['notes'];
    }
}

if (isset($_POST['submit'])) {
    $invoice_id = $_POST['invoice_id'] ?? '';
    $payment_date = $_POST['payment_date'] ?? '';
    $amount_paid = $_POST['amount_paid'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $reference_number = $_POST['reference_number'] ?? '';
    $notes = $_POST['notes'] ?? '';

    $columns = ['invoice_id', 'payment_date', 'amount_paid', 'payment_method', 'reference_number', 'notes'];
    $values = [$invoice_id, $payment_date, $amount_paid, $payment_method, $reference_number, $notes];

    if ($edit_mode) {
        // Update operation
        $updated = $DB->update('payments', $columns, $values, 'id', $u_id);
        if ($updated) {
            send_message_TG("Payment Updated\nInvoice ID: $invoice_id\nPayment Date: $payment_date\nAmount Paid: $amount_paid\nPayment Method: $payment_method\nReference Number: $reference_number\nNotes: $notes");
            header("Location:payment.php");
        } else {
            echo "Error: Unable to update payment.";
        }
    } else {
        // Insert operation
        $inserted = $DB->create('payments', $columns, $values);
        if ($inserted) {
            send_message_TG("New Payment Added\nInvoice ID: $invoice_id\nPayment Date: $payment_date\nAmount Paid: $amount_paid\nPayment Method: $payment_method\nReference Number: $reference_number\nNotes: $notes");
            header("Location:payment.php");
        } else {
            echo "Error: Unable to insert payment.";
        }
    }
}
?>

<div class="account-settings-container layout-top-spacing">
    <div class="account-content">
        <div class="row mb-3">
            <div class="col-md-12">
                <h2><?php echo $edit_mode ? "Edit Payment" : "Add Payment"; ?></h2>
            </div>
        </div>

        <div class="tab-content" id="animateLineContent-4">
            <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                        <form class="section general-info" action="" method="POST">
                            <div class="info">
                                <h6 class="">Payment Information</h6>
                                <div class="row">
                                    <div class="col-lg-11 mx-auto">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="invoice_id">Invoice ID</label>
                                                    <select class="form-select mb-3" name="invoice_id" id="invoice_id" required>
                                                        <option required value="">Select Invoice ID</option>
                                                        <?php while ($row = mysqli_fetch_assoc($invoice_data)) { ?>
                                                            <option value="<?php echo $row['id'] ?>" <?php echo ($row['id'] == $invoice_id) ? 'selected' : ''; ?>>
                                                                <?php echo $row['id'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_date">Payment Date</label>
                                                    <input type="date" class="form-control mb-3" name="payment_date" id="payment_date" value="<?php echo $payment_date ?: date('Y-m-d'); ?>" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="amount_paid">Amount Paid</label>
                                                    <input type="number" step="0.01" class="form-control mb-3" name="amount_paid" id="amount_paid" value="<?php echo $amount_paid; ?>" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_method">Payment Method</label>
                                                    <select class="form-select mb-3" name="payment_method" id="payment_method" required>
                                                        <option value="">Select Method</option>
                                                        <option value="Cash" <?php echo ($payment_method == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                                                        <option value="Credit Card" <?php echo ($payment_method == 'Credit Card') ? 'selected' : ''; ?>>Credit Card</option>
                                                        <option value="Bank Transfer" <?php echo ($payment_method == 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                                                        <option value="UPI" <?php echo ($payment_method == 'UPI') ? 'selected' : ''; ?>>UPI</option>
                                                        <option value="Cheque" <?php echo ($payment_method == 'Cheque') ? 'selected' : ''; ?>>Cheque</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="reference_number">Reference Number</label>
                                                    <input type="text" class="form-control mb-3" name="reference_number" id="reference_number" value="<?php echo $reference_number; ?>" placeholder="Enter Reference Number">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="notes">Notes</label>
                                                    <textarea class="form-control mb-3" name="notes" id="notes" rows="3" placeholder="Additional Notes..."><?php echo $notes; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-1">
                                                <div class="form-group text-end">
                                                    <button type="submit" name="submit" class="btn btn-primary"><?php echo $edit_mode ? "Update Payment" : "Save Payment"; ?></button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once './include/footer-admin.php'; ?>