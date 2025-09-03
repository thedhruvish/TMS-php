<?php
$pageTitle = isset($_GET['u_id']) ? "Edit Payment" : "Add Payment";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// --- 1. Fetch Data for Form Population ---

// Fetch all customers for the dropdown
$customer_data = $DB->read("customer");

// Fetch all invoices along with their total paid amounts to calculate pending amounts
$invoices_with_payments_res = $DB->custom_query("
    SELECT 
        i.id, 
        i.customer_id, 
        i.total,
        COALESCE(SUM(p.amount_paid), 0) as paid_amount
    FROM invoices i
    LEFT JOIN payments p ON i.id = p.invoice_id
    GROUP BY i.id
");

// --- 2. Prepare Data for JavaScript ---

// Create a map of customer details (ID => {full_name, phone})
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

// Create a map of invoice details for JS (ID => {customer_id, total, paid_amount})
$invoiceDataForJs = [];
if ($invoices_with_payments_res) {
    while ($row = mysqli_fetch_assoc($invoices_with_payments_res)) {
        // For edit mode, we need to exclude the current payment's amount from the 'paid_amount'
        $paid_amount_for_calc = (float)$row['paid_amount'];
        if (isset($_GET['u_id'])) {
             $payment_being_edited = mysqli_fetch_assoc($DB->read("payments", ['where' => ['id' => ['=' => $_GET['u_id']]]]));
             if ($payment_being_edited && $payment_being_edited['invoice_id'] == $row['id']) {
                $paid_amount_for_calc -= (float)$payment_being_edited['amount_paid'];
             }
        }
        $invoiceDataForJs[$row['id']] = [
            'id' => $row['id'],
            'customer_id' => $row['customer_id'],
            'total' => (float)$row['total'],
            'paid_amount' => $paid_amount_for_calc
        ];
    }
}


// --- 3. Initialize Variables & Handle Edit Mode ---
$invoice_id = $payment_date = $amount_paid = $payment_method = $reference_number = $notes = "";
$selected_customer_id = '';
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

        // Fetch associated invoice to pre-select the customer
        $invoice_res = $DB->read("invoices", ['where' => ['id' => ['=' => $invoice_id]]]);
        if ($invoice_res && mysqli_num_rows($invoice_res) > 0) {
            $selected_customer_id = mysqli_fetch_assoc($invoice_res)['customer_id'];
        }
    }
}

// --- 4. Handle Form Submission ---
if (isset($_POST['submit'])) {
    $invoice_id = $_POST['invoice_id'] ?? '';
    $payment_date = $_POST['payment_date'] ?? '';
    $amount_paid = $_POST['amount_paid'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $reference_number = $_POST['reference_number'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $customer_name = $_POST['customer_name'] ?? '';
    $customer_phone = $_POST['customer_phone'] ?? 'N/A';

    $columns = ['invoice_id', 'payment_date', 'amount_paid', 'payment_method', 'reference_number', 'notes', 'created_by'];
    $values = [$invoice_id, $payment_date, $amount_paid, $payment_method, $reference_number, $notes, $_SESSION['user_id']];

    if ($edit_mode) {
        $updated = $DB->update('payments', $columns, $values, 'id', $u_id);
        if ($updated) {
            send_message_TG("Payment Updated\nCustomer: $customer_name : Phone No ($customer_phone)\nInvoice ID: $invoice_id\nAmount: $amount_paid");
            header("Location:payment.php");
        } else {
            echo "Error: Unable to update payment.";
        }
    } else {
        $inserted = $DB->create('payments', $columns, $values);
        if ($inserted) {
            send_message_TG("New Payment Added\nCustomer: $customer_name : Phone No ($customer_phone)\nInvoice ID: $invoice_id\nAmount: $amount_paid");
            header("Location:payment.php");
        } else {
            echo "Error: Unable to insert payment.";
        }
    }
    exit();
}
?>

<div class="account-settings-container layout-top-spacing">
    <div class="account-content">
        <div class="row mb-3">
            <div class="col-md-12">
                <h2><?php echo $edit_mode ? "Edit Payment" : "Add Payment"; ?></h2>
            </div>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                        <form class="section general-info" id="paymentForm" action="" method="POST">
                            <div class="info">
                                <h6 class="">Payment Information</h6>
                                <div class="row">
                                    <div class="col-lg-11 mx-auto">
                                        <div class="row">
                                            <!-- Customer Selection -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_id">Select Customer</label>
                                                    <input type="hidden" name="customer_name" id="customer_name_hidden">
                                                    <input type="hidden" name="customer_phone" id="customer_phone_hidden">
                                                    <select class="form-select mb-3" id="customer_id" required>
                                                        <option value="">Select Customer Email</option>
                                                        <?php if ($customer_data) {
                                                            mysqli_data_seek($customer_data, 0);
                                                            while ($row = mysqli_fetch_assoc($customer_data)) { ?>
                                                                <option value="<?php echo $row['id'] ?>" <?php echo ($row['id'] == $selected_customer_id) ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($row['email']); ?>
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Customer Details Display -->
                                            <div class="col-md-6">
                                                <div id="customer-details" class="mt-4 p-2 border rounded" style="display: none;">
                                                    <p class="mb-1"><strong>Name:</strong> <span id="customer-name"></span></p>
                                                    <p class="mb-0"><strong>Phone:</strong> <span id="customer-phone"></span></p>
                                                </div>
                                            </div>

                                            <!-- Invoice Selection -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="invoice_id">Invoice ID</label>
                                                    <select class="form-select mb-3" name="invoice_id" id="invoice_id" required>
                                                        <option value="">Select a customer first</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Invoice Details Display -->
                                            <div class="col-md-6">
                                                <div id="invoice-details" class="mt-4 p-2 border rounded bg-light" style="display: none;">
                                                    <p class="mb-1"><strong>Total Bill:</strong> $<span id="invoice-total"></span></p>
                                                    <p class="mb-0 text-danger"><strong>Pending Amount:</strong> $<span id="invoice-pending"></span></p>
                                                </div>
                                            </div>

                                            <!-- Payment Date -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_date">Payment Date</label>
                                                    <input type="date" class="form-control mb-3" name="payment_date" id="payment_date" value="<?php echo $payment_date ?: date('Y-m-d'); ?>" required>
                                                </div>
                                            </div>

                                            <!-- Amount Paid -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="amount_paid">Amount Paid</label>
                                                    <input type="number" step="0.01" class="form-control mb-3" name="amount_paid" id="amount_paid" value="<?php echo $amount_paid; ?>" placeholder="Enter amount" required>
                                                    <div id="amount-error" class="text-danger" style="display: none;">Amount cannot be greater than the pending amount.</div>
                                                </div>
                                            </div>

                                            <!-- Payment Method -->
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

                                            <!-- Reference Number -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="reference_number">Reference Number</label>
                                                    <input type="text" class="form-control mb-3" name="reference_number" id="reference_number" value="<?php echo htmlspecialchars($reference_number); ?>" placeholder="E.g., Transaction ID">
                                                </div>
                                            </div>

                                            <!-- Notes -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="notes">Notes</label>
                                                    <textarea class="form-control mb-3" name="notes" id="notes" rows="3" placeholder="Additional Notes..."><?php echo htmlspecialchars($notes); ?></textarea>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Data from PHP ---
        const customerMap = <?php echo json_encode($customerMap); ?>;
        const allInvoices = <?php echo json_encode($invoiceDataForJs); ?>;
        const isEditMode = <?php echo json_encode($edit_mode); ?>;
        const selectedInvoiceIdOnLoad = <?php echo json_encode($invoice_id); ?>;

        // --- DOM Elements ---
        const customerSelect = document.getElementById('customer_id');
        const invoiceSelect = document.getElementById('invoice_id');
        const amountInput = document.getElementById('amount_paid');
        const amountError = document.getElementById('amount-error');
        const paymentForm = document.getElementById('paymentForm');

        const customerDetailsDiv = document.getElementById('customer-details');
        const customerNameSpan = document.getElementById('customer-name');
        const customerPhoneSpan = document.getElementById('customer-phone');
        const customerNameHidden = document.getElementById('customer_name_hidden');
        const customerPhoneHidden = document.getElementById('customer_phone_hidden');
        
        const invoiceDetailsDiv = document.getElementById('invoice-details');
        const invoiceTotalSpan = document.getElementById('invoice-total');
        const invoicePendingSpan = document.getElementById('invoice-pending');

        let maxPayableAmount = 0;

        // --- Event Listeners ---
        customerSelect.addEventListener('change', handleCustomerChange);
        invoiceSelect.addEventListener('change', handleInvoiceChange);
        paymentForm.addEventListener('submit', handleFormSubmit);

        // --- Functions ---
        function handleCustomerChange() {
            const customerId = customerSelect.value;
            displayCustomerDetails(customerId);
            populateInvoicesForCustomer(customerId);
            
            // Reset downstream fields
            invoiceDetailsDiv.style.display = 'none';
            amountInput.value = '';
            amountInput.removeAttribute('max');
            amountError.style.display = 'none';
        }

        function handleInvoiceChange() {
            const invoiceId = invoiceSelect.value;
            displayInvoiceDetails(invoiceId);
        }

        function handleFormSubmit(event) {
            const enteredAmount = parseFloat(amountInput.value);
            if (enteredAmount > maxPayableAmount) {
                event.preventDefault(); // Stop form submission
                amountError.style.display = 'block';
            } else {
                amountError.style.display = 'none';
            }
        }
        
        function displayCustomerDetails(customerId) {
            if (customerId && customerMap[customerId]) {
                const customer = customerMap[customerId];
                customerNameSpan.textContent = customer.full_name;
                customerPhoneSpan.textContent = customer.phone;
                customerNameHidden.value = customer.full_name;
                customerPhoneHidden.value = customer.phone;
                customerDetailsDiv.style.display = 'block';
            } else {
                customerDetailsDiv.style.display = 'none';
            }
        }
        
        function populateInvoicesForCustomer(customerId) {
            invoiceSelect.innerHTML = '<option value="">Select Invoice ID</option>'; // Reset
            if (!customerId) {
                invoiceSelect.innerHTML = '<option value="">Select a customer first</option>';
                return;
            }

            const customerInvoices = Object.values(allInvoices).filter(inv => inv.customer_id == customerId);
            
            if (customerInvoices.length > 0) {
                customerInvoices.forEach(inv => {
                    const remaining = inv.total - inv.paid_amount;
                    if (remaining > 0.009 || inv.id == selectedInvoiceIdOnLoad) { // Show if pending or if it's the one being edited
                        const option = document.createElement('option');
                        option.value = inv.id;
                        option.textContent = `${inv.id} (Pending: $${remaining.toFixed(2)})`;
                        invoiceSelect.appendChild(option);
                    }
                });
            } else {
                invoiceSelect.innerHTML = '<option value="">No pending invoices for this customer</option>';
            }
        }

        function displayInvoiceDetails(invoiceId) {
            if (!invoiceId || !allInvoices[invoiceId]) {
                invoiceDetailsDiv.style.display = 'none';
                amountInput.value = '';
                amountInput.removeAttribute('max');
                maxPayableAmount = 0;
                return;
            }

            const invoice = allInvoices[invoiceId];
            const pendingAmount = invoice.total - invoice.paid_amount;
            
            invoiceTotalSpan.textContent = invoice.total.toFixed(2);
            invoicePendingSpan.textContent = pendingAmount.toFixed(2);
            invoiceDetailsDiv.style.display = 'block';

            // Set max payable amount for validation
            maxPayableAmount = parseFloat(pendingAmount.toFixed(2));
            amountInput.max = maxPayableAmount;
            
            // Clear amount input, do not pre-fill
            if (!isEditMode) {
               amountInput.value = '';
            }
        }

        // --- Initial Load Logic ---
        function initializeForm() {
            const initialCustomerId = customerSelect.value;
            if (initialCustomerId) {
                displayCustomerDetails(initialCustomerId);
                populateInvoicesForCustomer(initialCustomerId);
                if (isEditMode && selectedInvoiceIdOnLoad) {
                    invoiceSelect.value = selectedInvoiceIdOnLoad;
                    displayInvoiceDetails(selectedInvoiceIdOnLoad);
                }
            }
        }

        initializeForm();
    });
</script>

<?php require_once './include/footer-admin.php'; ?>
