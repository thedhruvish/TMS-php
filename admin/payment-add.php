<?php $pageTitle = "Add Payment";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$invoice_data = $DB->read("invoices");

if (isset($_POST['submit'])) {
    $invoice_id = $_POST['invoice_id'] ?? '';
    $payment_date = $_POST['payment_date'] ?? '';
    $amount_paid = $_POST['amount_paid'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $reference_number = $_POST['reference_number'] ?? '';
    $notes = $_POST['notes'] ?? '';
    // $created_at = date('Y-m-d H:i:s'); 

    $columns = ['invoice_id', 'payment_date', 'amount_paid', 'payment_method', 'reference_number', 'notes'];
    $values = [$invoice_id, $payment_date, $amount_paid, $payment_method, $reference_number, $notes];

    $inserted = $DB->create('payments', $columns, $values);

    if ($inserted) {
        header("Location:payment.php");
    } else {
        echo "Error: Unable to insert payment.";
    }
}

?>

<div class="account-settings-container layout-top-spacing">
    <div class="account-content">
        <div class="row mb-3">
            <div class="col-md-12">
                <h2>Add Payment</h2>
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

                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="invoice_id"></label>
                                                    <input type="text" class="form-control mb-3" name="invoice_id" id="invoice_id" placeholder="Enter Invoice ID" required>
                                                </div>
                                            </div> -->

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_method">Invoice ID</label>
                                                    <select class="form-select mb-3" name="invoice_id" id="invoice_id" required>
                                                        <option value="">Select Invoice ID</option>
                                                        <?php while ($row = mysqli_fetch_assoc($invoice_data)) { ?>
                                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['invoice_number'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_date">Payment Date</label>
                                                    <input type="date" class="form-control mb-3" name="payment_date" id="payment_date" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="amount_paid">Amount Paid</label>
                                                    <input type="number" step="0.01" class="form-control mb-3" name="amount_paid" id="amount_paid" placeholder="Enter Amount" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_method">Payment Method</label>
                                                    <select class="form-select mb-3" name="payment_method" id="payment_method" required>
                                                        <option value="">Select Method</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="Credit Card">Credit Card</option>
                                                        <option value="Bank Transfer">Bank Transfer</option>
                                                        <option value="UPI">UPI</option>
                                                        <option value="Cheque">Cheque</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="reference_number">Reference Number</label>
                                                    <input type="text" class="form-control mb-3" name="reference_number" id="reference_number" placeholder="Enter Reference Number">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="notes">Notes</label>
                                                    <textarea class="form-control mb-3" name="notes" id="notes" rows="3" placeholder="Additional Notes..."></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-1">
                                                <div class="form-group text-end">
                                                    <button type="submit" name="submit" class="btn btn-primary">Save Payment</button>
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

<?php include('./include/footer-admin.php'); ?>