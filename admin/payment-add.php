<?php
$pageTitle = isset($_GET['u_id']) ? "Edit Payment" : "Add Payment";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$customer_data = $DB->read("customer"); // Fetch all customers
$invoice_data = $DB->read("invoices");  // Fetch all invoices

// Create a map of customer details for JS
$customerMap = [];
if ($customer_data) {
    while ($row = mysqli_fetch_assoc($customer_data)) {
        $customerMap[$row['id']] = [
            'full_name' => trim($row['first_name'] . ' ' . $row['last_name']),
            'phone' => $row['phone']
        ];
    }
    mysqli_data_seek($customer_data, 0); // Reset pointer for HTML loop
}

// Create a map of invoices grouped by customer_id for JS
$invoiceDataForJs = [];
if ($invoice_data) {
    while ($row = mysqli_fetch_assoc($invoice_data)) {
        $invoiceDataForJs[] = [
            'id' => $row['id'],
            'customer_id' => $row['customer_id'],
            'total' => $row['total']
        ];
    }
    mysqli_data_seek($invoice_data, 0); // Reset pointer for initial population
}


// --- Initialize variables ---
$invoice_id = $payment_date = $amount_paid = $payment_method = $reference_number = $notes = "";
$selected_customer_id = '';
$invoice_total = 0;


// --- Check if update mode ---
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

        // Fetch associated invoice to get customer_id and total
        $invoice_res = $DB->read("invoices", ['where' => ['id' => ['=' => $invoice_id]]]);
        if ($invoice_res && mysqli_num_rows($invoice_res) > 0) {
            $invoice_details = mysqli_fetch_assoc($invoice_res);
            $selected_customer_id = $invoice_details['customer_id'];
            $invoice_total = $invoice_details['total'];
        }
    }
}

// --- Handle form submission ---
if (isset($_POST['submit'])) {
    $invoice_id = $_POST['invoice_id'] ?? '';
    $payment_date = $_POST['payment_date'] ?? '';
    $amount_paid = $_POST['amount_paid'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $reference_number = $_POST['reference_number'] ?? '';
    $notes = $_POST['notes'] ?? '';

    $columns = ['invoice_id', 'payment_date', 'amount_paid', 'payment_method', 'reference_number', 'notes', 'created_by'];
    $values = [$invoice_id, $payment_date, $amount_paid, $payment_method, $reference_number, $notes, $_SESSION['user_id']];

    if ($edit_mode) {
        $updated = $DB->update('payments', $columns, $values, 'id', $u_id);
        if ($updated) {
            send_message_TG("Payment Updated\nInvoice ID: $invoice_id\nAmount: $amount_paid");
            header("Location:payment.php");
        } else {
            echo "Error: Unable to update payment.";
        }
    } else {
        $inserted = $DB->create('payments', $columns, $values);
        if ($inserted) {
            send_message_TG("New Payment Added\nInvoice ID: $invoice_id\nAmount: $amount_paid");
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
                                                    <label for="customer_id">Select Customer</label>
                                                    <select class="form-select mb-3" id="customer_id" required>
                                                        <option value="">Select Customer Email</option>
                                                        <?php if ($customer_data) {
                                                            mysqli_data_seek($customer_data, 0); // Ensure pointer is at start
                                                            while ($row = mysqli_fetch_assoc($customer_data)) { ?>
                                                                <option value="<?php echo $row['id'] ?>" <?php echo ($row['id'] == $selected_customer_id) ? 'selected' : ''; ?>>
                                                                    <?php echo $row['email'] ?>
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div id="customer-details" class="mt-4 p-2 border rounded"
                                                    style="display: none;">
                                                    <p class="mb-1"><strong>Name:</strong> <span
                                                            id="customer-name"></span></p>
                                                    <p class="mb-0"><strong>Phone:</strong> <span
                                                            id="customer-phone"></span></p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="invoice_id">Invoice ID</label>
                                                    <select class="form-select mb-3" name="invoice_id" id="invoice_id"
                                                        required>
                                                        <option value="">Select a customer first</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_date">Payment Date</label>
                                                    <input type="date" class="form-control mb-3" name="payment_date"
                                                        id="payment_date"
                                                        value="<?php echo $payment_date ?: date('Y-m-d'); ?>" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="amount_paid">Amount Paid</label>
                                                    <input type="number" step="0.01" class="form-control mb-3"
                                                        name="amount_paid" id="amount_paid"
                                                        value="<?php echo $amount_paid; ?>"
                                                        max="<?php echo $invoice_total > 0 ? $invoice_total : ''; ?>"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_method">Payment Method</label>
                                                    <select class="form-select mb-3" name="payment_method"
                                                        id="payment_method" required>
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
                                                    <input type="text" class="form-control mb-3" name="reference_number"
                                                        id="reference_number" value="<?php echo $reference_number; ?>"
                                                        placeholder="Enter Reference Number">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="notes">Notes</label>
                                                    <textarea class="form-control mb-3" name="notes" id="notes" rows="3"
                                                        placeholder="Additional Notes..."><?php echo $notes; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-1">
                                                <div class="form-group text-end">
                                                    <button type="submit" name="submit"
                                                        class="btn btn-primary"><?php echo $edit_mode ? "Update Payment" : "Save Payment"; ?></button>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Data from PHP ---
        const customerMap = <?php echo json_encode($customerMap); ?>;
        const allInvoices = <?php echo json_encode($invoiceDataForJs); ?>;
        const isEditMode = <?php echo json_encode($edit_mode); ?>;
        const selectedInvoiceId = <?php echo json_encode($invoice_id); ?>;

        // --- DOM Elements ---
        const customerSelect = document.getElementById('customer_id');
        const invoiceSelect = document.getElementById('invoice_id');
        const amountInput = document.getElementById('amount_paid');
        const detailsDiv = document.getElementById('customer-details');
        const nameSpan = document.getElementById('customer-name');
        const phoneSpan = document.getElementById('customer-phone');

        // --- Event Listeners ---
        customerSelect.addEventListener('change', handleCustomerChange);
        invoiceSelect.addEventListener('change', handleInvoiceChange);

        // --- Functions ---
        function handleCustomerChange() {
            const customerId = customerSelect.value;
            displayCustomerDetails(customerId);
            populateInvoices(customerId);
            // Reset invoice selection and amount
            invoiceSelect.value = '';
            amountInput.value = '';
            amountInput.removeAttribute('max');
        }

        function handleInvoiceChange() {
            const invoiceId = invoiceSelect.value;
            populateAmount(invoiceId);
        }

        function displayCustomerDetails(customerId) {
            if (customerId && customerMap[customerId]) {
                nameSpan.textContent = customerMap[customerId].full_name;
                phoneSpan.textContent = customerMap[customerId].phone;
                detailsDiv.style.display = 'block';
            } else {
                detailsDiv.style.display = 'none';
            }
        }

        function populateInvoices(customerId) {
            invoiceSelect.innerHTML = '<option value="">Select Invoice ID</option>'; // Reset
            if (!customerId) {
                invoiceSelect.innerHTML = '<option value="">Select a customer first</option>';
                return;
            }

            const customerInvoices = allInvoices.filter(inv => inv.customer_id == customerId);
            if (customerInvoices.length > 0) {
                customerInvoices.forEach(inv => {
                    const option = document.createElement('option');
                    option.value = inv.id;
                    option.textContent = inv.id;
                    invoiceSelect.appendChild(option);
                });
            } else {
                invoiceSelect.innerHTML = '<option value="">No invoices found for this customer</option>';
            }
        }

        function populateAmount(invoiceId) {
            if (!invoiceId) {
                amountInput.value = '';
                amountInput.removeAttribute('max');
                return;
            }
            const selectedInvoice = allInvoices.find(inv => inv.id == invoiceId);
            if (selectedInvoice) {
                amountInput.value = selectedInvoice.total;
                amountInput.max = selectedInvoice.total;
            }
        }

        // --- Initial Load Logic ---
        function initializeForm() {
            const initialCustomerId = customerSelect.value;
            if (initialCustomerId) {
                displayCustomerDetails(initialCustomerId);
                populateInvoices(initialCustomerId);
                // If in edit mode, re-select the correct invoice
                if (isEditMode && selectedInvoiceId) {
                    invoiceSelect.value = selectedInvoiceId;
                }
            }
        }

        initializeForm();
    });
</script>

<?php require_once './include/footer-admin.php'; ?>