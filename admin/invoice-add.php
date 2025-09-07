<?php
$pageTitle = "Invoice add";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$invoice = [];
$items   = [];
$edit_mode = false;
$view_mode = false;

/* ---------- fetch products for the drop-down ---------- */
$productsRes = $DB->read("products", ['where' => ['show_publicly' => ['=' => 1]]]);
if (mysqli_num_rows($productsRes) > 0) {
    $products = mysqli_fetch_all($productsRes, MYSQLI_ASSOC);

    $filteredProducts = []; // store only in-stock products

    foreach ($products as &$product) {
        if (!empty($product['images'])) {
            $product['images'] = json_decode($product['images'], true);
        } else {
            $product['images'] = ['../images/placeholder.jpg'];
        }

        if ($product['disabled']) {
            continue;
        }

        $stockRes = $DB->read("stock", [
            'where' => ['product_id' => ['=' => $product['id']]]
        ]);

        if ($stockRes && mysqli_num_rows($stockRes) > 0) {
            $stock = mysqli_fetch_assoc($stockRes);
            $sold = $stock['sold_stock'] ?? 0;
            $dead = $stock['dead_stock'] ?? 0;
            $product['in_stock'] = ($stock['current_stock'] - $sold - $dead) > 0 ? 1 : 0;
            $product['stock'] = $stock['current_stock'] - $sold - $dead;
        } else {
            $product['in_stock'] = 0;
        }

        if ($product['in_stock'] == 1) {
            $filteredProducts[] = $product;
        }
    }
    unset($product);

    $products = $filteredProducts;
}

$productMap = [];
foreach ($products as $p) {
    $productMap[$p['id']] = $p;
}

/* ---------- fetch customers for drop-down and details view ---------- */
$customersRes = $DB->read("customer");
$customers = mysqli_fetch_all($customersRes, MYSQLI_ASSOC);
mysqli_data_seek($customersRes, 0); // Reset pointer for the while loop in HTML form

$customerMap = [];
foreach ($customers as $c) {
    $customerMap[$c['id']] = [
        'full_name' => trim($c['first_name'] . ' ' . $c['last_name']),
        'phone'     => $c['phone']
    ];
}

/* ---------- POST handler (insert / update) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['customer_id'] == '') {
        echo "<script>alert('Please select a customer!'); window.history.back();</script>";
        exit();
    }
    $invoice_data = [
        // 'invoice_label' removed to match new schema
        'customer_id'     => $_POST['customer_id']     ?? '',
        'invoice_date'    => $_POST['invoice_date']    ?? '',
        'due_date'        => $_POST['due_date']        ?: null,
        'account_number'  => $_POST['account_number']  ?: null,
        'bank_name'       => $_POST['bank_name']       ?: null,
        'swift_code'      => $_POST['swift_code']      ?: null,
        'notes'           => $_POST['notes']           ?: null,
        'subtotal'        => (float)($_POST['subtotal'] ?? 0),
        'discount'        => (float)($_POST['discount'] ?? 0),
        'tax'             => (float)($_POST['tax']      ?? 0), // Added tax field
        'total'           => (float)($_POST['total']    ?? 0),
        'created_by'      => $_SESSION['user_id']
    ];

    if (!empty($_POST['invoice_id'])) {                 // UPDATE
        $invoice_id = (int)$_POST['invoice_id'];
        $DB->update('invoices', array_keys($invoice_data), array_values($invoice_data), 'id', $invoice_id);
        $DB->delete('invoice_items', 'invoice_id', $invoice_id);
    } else {                                            // CREATE
        $DB->create('invoices', array_keys($invoice_data), array_values($invoice_data));
        $invoice_id = $DB->conn->insert_id;
    }

    // Save items
    if (!empty($_POST['items'])) {
        foreach ($_POST['items'] as $row) {
            $product_id = (int)($row['product_id'] ?? 0);
            $qty        = (int)($row['quantity']   ?? 0);

            $stockRes = $DB->read("stock", ['where' => ['product_id' => ['=' => $product_id]]]);
            if ($stockRes && mysqli_num_rows($stockRes) > 0) {
                $stock = mysqli_fetch_assoc($stockRes);
                $sold  = $stock['sold_stock'] ?? 0;
                $dead  = $stock['dead_stock'] ?? 0;
                $available = $stock['current_stock'] - $sold - $dead;
                $product_name = $productMap[$product_id]['name'] ?? 'Unknown Product';

                if ($qty > $available) {
                    echo "<script>alert('Not enough stock for {$product_name}! Available: {$available}, Requested: {$qty}'); window.history.back();</script>";
                    exit;
                }
                $remaining = $available - $qty;
                if ($remaining < 100) {
                    send_message_TG(
                        "Low Stock Alert\nProduct Name: {$product_name}\nCurrent Stock: {$stock['current_stock']}\nPending Stock: {$remaining}"
                    );
                }
            }

            $DB->create('invoice_items', [
                'invoice_id', 'product_id', 'rate', 'quantity', 'amount'
            ], [
                $invoice_id, $product_id, (float)($row['rate'] ?? 0), $qty, (float)($row['amount'] ?? 0)
            ]);

            $newSold = $sold + $qty;
            $DB->update("stock", ["sold_stock"], [$newSold], "product_id", $product_id);
        }
    }
    header("Location: invoice.php");
    exit;
}

/** delete */
if (isset($_GET['d_id'])) {
    $d_id = $_GET['d_id'];
    $DB->delete('invoice_items', 'invoice_id', $d_id);
    $DB->delete("invoices", "id", $d_id);
    header("Location: invoice.php");
}

/* ---------- Determine mode & fetch data ---------- */
if (isset($_GET['id'])) {
    $view_mode = true;
    $result = $DB->read('invoices', ['where' => ['id' => ['=' => (int)$_GET['id']]]]);
    if ($result && $result->num_rows) {
        $invoice = $result->fetch_assoc();
        $items_result = $DB->read('invoice_items', ['where' => ['invoice_id' => ['=' => $invoice['id']]]]);
        while ($row = $items_result->fetch_assoc()) $items[] = $row;
    }
} elseif (isset($_GET['u_id'])) {
    $edit_mode = true;
    $result = $DB->read('invoices', ['where' => ['id' => ['=' => (int)$_GET['u_id']]]]);
    if ($result && $result->num_rows) {
        $invoice = $result->fetch_assoc();
        $items_result = $DB->read('invoice_items', ['where' => ['invoice_id' => ['=' => $invoice['id']]]]);
        while ($row = $items_result->fetch_assoc()) $items[] = $row;
    }
}
?>
<div class="row invoice layout-top-spacing layout-spacing">
  <div class="col-xl-12">
    <div class="doc-container">
      <form method="post" autocomplete="off">
        
        <?php if ($edit_mode || $view_mode) { ?>
          <input type="hidden" name="invoice_id" value="<?php echo $invoice['id'] ?? '' ?>">
        <?php } ?>

        <div class="card shadow-sm mb-4">
            <h4 class="card-header">Invoice</h4>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <h5 class="mb-3">Customer Information</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Customer (Email)</label>
                <select name="customer_id" class="form-select" <?php echo $view_mode ? 'disabled' : '' ?> required>
                  <option value="">Choose customer…</option>
                  <?php while ($row = mysqli_fetch_assoc($customersRes)): ?>
                    <option value="<?php echo $row['id'] ?>"
                      <?php echo isset($invoice['customer_id']) && $invoice['customer_id'] == $row['id'] ? 'selected' : '' ?>>
                      <?php echo $row['email'] ?>
                    </option>
                  <?php endwhile; ?>
                </select>
                <div id="customer-details" class="mt-3 p-3 border rounded " style="display: none;">
                  <p class="mb-1"><strong>Name:</strong> <span id="customer-name"></span></p>
                  <p class="mb-0"><strong>Phone:</strong> <span id="customer-phone"></span></p>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Invoice Date</label>
                <input type="date" name="invoice_date" class="form-control"
                  value="<?php echo $invoice['invoice_date'] ?? date('Y-m-d') ?>"
                  <?php echo $view_mode ? 'readonly' : '' ?> >
              </div>
              <div class="col-md-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control"
                  value="<?php echo $invoice['due_date'] ?? date('Y-m-d', strtotime('+15 days')) ?>"
                  <?php echo $view_mode ? 'readonly' : '' ?>>
              </div>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <h5 class="mb-3">Invoice Items</h5>
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                  <tr>
                    <th style="width:40px;"></th>
                    <th>Product</th>
                    <th class="text-end">Rate ($)</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Amount ($)</th>
                  </tr>
                </thead>
                <tbody id="item-rows">
                  <?php $rows = empty($items) ? [[]] : $items;
                  foreach ($rows as $idx => $it) { ?>
                    <tr>
                      <td class="text-center">
                        <?php if (!$view_mode) { ?>
                            <a href="javascript:void(0)" class="text-danger delete-item">✕</a>
                        <?php } ?>
                      </td>
                      <td>
                        <select name="items[<?php echo $idx ?>][product_id]" class="form-select"
                          <?php echo $view_mode ? 'disabled' : '' ?> required>
                          <option value="">Choose product…</option>
                          <?php foreach ($products as $product) { ?>
                            <option value="<?php echo $product['id'] ?>"
                              <?php echo isset($it['product_id']) && $it['product_id'] == $product['id'] ? 'selected' : '' ?>>
                              <?php echo $product['name']; ?>
                            </option>
                          <?php } ?>
                        </select>
                      </td>
                      <td>
                        <input type="number" step="0.01" name="items[<?php echo $idx ?>][rate]"
                          class="form-control text-end rate-input"
                          value="<?php echo $it['rate'] ?? '' ?>"
                          <?php echo $view_mode ? 'readonly' : '' ?> required>
                      </td>
                      <td>
                        <input type="number" name="items[<?php echo $idx ?>][quantity]"
                          class="form-control text-end qty-input"
                          value="<?php echo $it['quantity'] ?? '' ?>"
                          <?php echo $view_mode ? 'readonly' : '' ?> required>
                      </td>
                      <td class="text-end">
                        $<span class="item-amount">
                          <?php echo number_format(($it['rate'] ?? 0) * ($it['quantity'] ?? 0), 2) ?>
                        </span>
                        <input type="hidden" name="items[<?php echo $idx ?>][amount]" class="amount-input"
                          value="<?php echo ($it['rate'] ?? 0) * ($it['quantity'] ?? 0) ?>">
                      </td>
                    </tr>
                  <?php }; ?>
                </tbody>
              </table>
            </div>
            <?php if (!$view_mode) { ?>
              <button type="button" class="btn btn-sm btn-primary mt-2 additem">
                <i class="bi bi-plus-circle"></i> Add Item
              </button>
            <?php } ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="card shadow-sm mb-4">
              <div class="card-body">
                <h5 class="mb-3">Payment Details</h5>
                <div class="mb-3">
                  <label class="form-label">Account #</label>
                  <input type="text" name="account_number" class="form-control"
                    value="<?php echo $invoice['account_number'] ?? '' ?>"
                    <?php echo $view_mode ? 'readonly' : '' ?>>
                </div>
                <div class="mb-3">
                  <label class="form-label">Bank Name</label>
                  <input type="text" name="bank_name" class="form-control"
                    value="<?php echo $invoice['bank_name'] ?? '' ?>"
                    <?php echo $view_mode ? 'readonly' : '' ?>>
                </div>
                <div class="mb-3">
                  <label class="form-label">SWIFT Code</label>
                  <input type="text" name="swift_code" class="form-control"
                    value="<?php echo $invoice['swift_code'] ?? '' ?>"
                    <?php echo $view_mode ? 'readonly' : '' ?>>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card shadow-sm mb-4">
              <div class="card-body">
                <h5 class="mb-3">Totals</h5>
                <div class="d-flex justify-content-between mb-2">
                  <span>Subtotal</span>
                  <strong>$<span id="subtotal-display"><?php echo number_format($invoice['subtotal'] ?? 0, 2) ?></span></strong>
                  <input type="hidden" name="subtotal" id="subtotal-input" value="<?php echo $invoice['subtotal'] ?? 0 ?>">
                </div>

                <?php
                  // Discount logic
                  $discount_value = ($edit_mode || $view_mode) ? ($invoice['discount'] ?? 0) : 0;
                  $discount_type = ($edit_mode || $view_mode) ? 'flat' : 'percentage';

                  // Tax logic: For existing invoices, use flat amount. For new, default to 10%.
                  $tax_value = ($edit_mode || $view_mode) ? ($invoice['tax'] ?? 0) : 10;
                  $tax_type = ($edit_mode || $view_mode) ? 'flat' : 'percentage';
                ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Discount</span>
                    <div class="d-flex" style="max-width: 220px;">
                        <input type="number" id="discount-value-input" class="form-control form-control-sm text-end me-2" value="<?php echo $discount_value; ?>" min="0" <?php echo $view_mode ? 'readonly' : '' ?>>
                        <select id="discount-type-select" class="form-select form-select-sm" <?php echo $view_mode ? 'disabled' : '' ?>>
                            <option value="percentage" <?php if($discount_type == 'percentage') echo 'selected'; ?>>%</option>
                            <option value="flat" <?php if($discount_type == 'flat') echo 'selected'; ?>>$</option>
                        </select>
                    </div>
                </div>
                 <div class="d-flex justify-content-end mb-2">
                    <strong class="text-danger">-$<span id="discount-display"><?php echo number_format($invoice['discount'] ?? 0, 2) ?></span></strong>
                    <input type="hidden" name="discount" id="discount-input" value="<?php echo $invoice['discount'] ?? 0 ?>">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Tax</span>
                    <div class="d-flex" style="max-width: 220px;">
                        <input type="number" id="tax-value-input" class="form-control form-control-sm text-end me-2" value="<?php echo $tax_value; ?>" min="0" <?php echo $view_mode ? 'readonly' : '' ?>>
                        <select id="tax-type-select" class="form-select form-select-sm" <?php echo $view_mode ? 'disabled' : '' ?>>
                            <option value="percentage" <?php if($tax_type == 'percentage') echo 'selected'; ?>>%</option>
                            <option value="flat" <?php if($tax_type == 'flat') echo 'selected'; ?>>$</option>
                        </select>
                    </div>
                </div>
                 <div class="d-flex justify-content-end mb-2">
                    <strong class="text-info">+$<span id="tax-display"><?php echo number_format($invoice['tax'] ?? 0, 2) ?></span></strong>
                    <input type="hidden" name="tax" id="tax-input" value="<?php echo $invoice['tax'] ?? 0 ?>">
                </div>

                <hr class="my-2">
                
                <div class="d-flex justify-content-between">
                  <span class="h5">Total</span>
                  <strong class="text-success h5">$<span id="total-display"><?php echo number_format($invoice['total'] ?? 0, 2) ?></span></strong>
                  <input type="hidden" name="total" id="total-input" value="<?php echo $invoice['total'] ?? 0 ?>">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <h5 class="mb-3">Notes</h5>
            <textarea name="notes" class="form-control" rows="3"
              placeholder="Thank you for doing business with us"
              <?php echo $view_mode ? 'readonly' : '' ?>><?php echo $invoice['notes'] ?? '' ?></textarea>
          </div>
        </div>

        <div class="row mb-4">
          <?php if (!$view_mode) { ?>
            <div class="col-md-4 mb-2">
              <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-save"></i> Save Invoice
              </button>
            </div>
          <?php } ?>
          <div class="col-md-4 mb-2">
            <a href="./invoice.php" class="btn btn-outline-secondary w-100">
              <i class="bi bi-arrow-left"></i> Back
            </a>
          </div>
          <?php if ($view_mode) { ?>
            <div class="col-md-4 mb-2">
              <button type="button" class="btn btn-primary w-100" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
              </button>
            </div>
          <?php } ?>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
    // --- Data from PHP ---
    const productPriceMap = {
        <?php foreach ($products as $p): 
            $price = !empty($p['sale_price']) ? $p['sale_price'] : $p['regular_price']; ?>
            <?php echo $p['id']; ?>: <?php echo (float)$price; ?>,
        <?php endforeach; ?>
    };
    const customerMap = <?php echo json_encode($customerMap); ?>;

    // --- Event Listeners ---
    document.addEventListener('DOMContentLoaded', () => {
        // Initial setup calls
        setupCustomerDetails();
        recalcTotals();
        
        // Bind event listeners
        document.addEventListener('change', handleFormChange);
        document.addEventListener('input', handleFormInput);
        document.addEventListener('click', handleFormClick);
    });

    // --- Event Handlers ---
    function handleFormChange(e) {
        if (e.target.matches('select[name$="[product_id]"]')) {
            updateProductPrice(e.target);
        }
        if (e.target.matches('select[name="customer_id"]')) {
            updateCustomerDetails();
        }
        if (e.target.matches('.rate-input, .qty-input, #discount-type-select, #tax-type-select')) {
            recalcTotals();
        }
    }
    
    function handleFormInput(e) {
        if (e.target.matches('.rate-input, .qty-input, #discount-value-input, #tax-value-input')) {
            const row = e.target.closest('tr');
            if (row) recalcAmount(row);
            recalcTotals();
        }
    }

    function handleFormClick(e) {
        if (e.target.matches('.additem')) {
            addNewItemRow();
        }
        if (e.target.matches('.delete-item')) {
            e.preventDefault();
            e.target.closest('tr')?.remove();
            recalcTotals(); // Recalculate after removing an item
        }
    }

    // --- Customer Details Logic ---
    function setupCustomerDetails() {
        const customerSelect = document.querySelector('select[name="customer_id"]');
        if (customerSelect.value) {
            updateCustomerDetails();
        }
    }

    function updateCustomerDetails() {
        const customerSelect = document.querySelector('select[name="customer_id"]');
        const detailsDiv = document.getElementById('customer-details');
        const nameSpan = document.getElementById('customer-name');
        const phoneSpan = document.getElementById('customer-phone');
        const selectedId = customerSelect.value;
        
        if (selectedId && customerMap[selectedId]) {
            const customer = customerMap[selectedId];
            nameSpan.textContent = customer.full_name;
            phoneSpan.textContent = customer.phone;
            detailsDiv.style.display = 'block';
        } else {
            detailsDiv.style.display = 'none';
        }
    }

    // --- Calculation Logic ---
    function recalcAmount(row) {
        const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        const amount = (rate * qty);
        row.querySelector('.item-amount').textContent = amount.toFixed(2);
        row.querySelector('.amount-input').value = amount.toFixed(2);
    }
    
    function recalcTotals() {
        let subtotal = 0;
        document.querySelectorAll('#item-rows tr').forEach(row => {
            subtotal += parseFloat(row.querySelector('.amount-input')?.value || 0);
        });

        // --- Discount Calculation ---
        let discountValueInput = document.getElementById('discount-value-input');
        let discountValue = parseFloat(discountValueInput.value) || 0;
        const discountType = document.getElementById('discount-type-select').value;
        let calculatedDiscount = 0;

        if (discountType === 'percentage') {
            if (discountValue > 80) {
                alert('Discount percentage cannot exceed 80%.');
                discountValue = 80;
                discountValueInput.value = 80;
            }
            calculatedDiscount = (subtotal * discountValue) / 100;
        } else {
            calculatedDiscount = discountValue;
        }
        
        if (calculatedDiscount > subtotal) {
            calculatedDiscount = subtotal;
        }

        // --- Tax Calculation ---
        let taxValue = parseFloat(document.getElementById('tax-value-input').value) || 0;
        const taxType = document.getElementById('tax-type-select').value;
        let calculatedTax = 0;

        if (taxType === 'percentage') {
            calculatedTax = (subtotal * taxValue) / 100;
        } else {
            calculatedTax = taxValue;
        }

        // --- Final Total ---
        const total = subtotal - calculatedDiscount + calculatedTax;

        // --- Update DOM ---
        document.getElementById('subtotal-display').textContent = subtotal.toFixed(2);
        document.getElementById('subtotal-input').value = subtotal.toFixed(2);

        document.getElementById('discount-display').textContent = calculatedDiscount.toFixed(2);
        document.getElementById('discount-input').value = calculatedDiscount.toFixed(2);
        
        document.getElementById('tax-display').textContent = calculatedTax.toFixed(2);
        document.getElementById('tax-input').value = calculatedTax.toFixed(2);

        document.getElementById('total-display').textContent = total.toFixed(2);
        document.getElementById('total-input').value = total.toFixed(2);
    }

    function updateProductPrice(selectElement) {
        const row = selectElement.closest('tr');
        const price = productPriceMap[selectElement.value] || 0;
        const rateInput = row.querySelector('.rate-input');
        if (rateInput) {
            rateInput.value = price.toFixed(2);
            // Manually trigger events to update calculations
            recalcAmount(row);
            recalcTotals();
        }
    }

    function bindRowEvents(row) {
        // Events are now handled globally, but you could add row-specific logic here if needed.
    }

    // --- DOM Manipulation ---
    function addNewItemRow() {
        <?php if (!$view_mode) { ?>
            const tbody = document.getElementById('item-rows');
            const idx = tbody.rows.length;
            const tr = document.createElement('tr');

            tr.innerHTML = `
            <td class="text-center"><a href="javascript:void(0)" class="text-danger delete-item">✕</a></td>
            <td>
                <select name="items[${idx}][product_id]" class="form-select" required>
                    <option value="">Choose product…</option>
                    <?php foreach ($products as $p) { ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${idx}][rate]" class="form-control text-end rate-input" value="" required>
            </td>
            <td>
                <input type="number" name="items[${idx}][quantity]" class="form-control text-end qty-input" value="" required>
            </td>
            <td class="text-end">
                $<span class="item-amount">0.00</span>
                <input type="hidden" name="items[${idx}][amount]" class="amount-input" value="0">
            </td>
            `;
            tbody.appendChild(tr);
        <?php } ?>
    }
</script>

<?php include './include/footer-admin.php'; ?>