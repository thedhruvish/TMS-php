<?php
$pageTitle = "Invoice add";
require_once './include/header-staff.php';
require_once './include/sidebar-staff.php';
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
        // Decode images
        if (!empty($product['images'])) {
            $product['images'] = json_decode($product['images'], true);
        } else {
            $product['images'] = ['../images/placeholder.jpg'];
        }

        // Skip stock calculation for disabled products
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

        // Add only if in stock
        if ($product['in_stock'] == 1) {
            $filteredProducts[] = $product;
        }
    }
    unset($product);

    // Replace products with filtered array
    $products = $filteredProducts;
}

$customersRes = $DB->read("customer");
/* ---------- POST handler (insert / update) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['customer_id'] == '') {
        echo "<script>alert('Please select a customer!'); window.history.back();</script>";
        exit();
    }
    $invoice_data = [
        'invoice_label'   => $_POST['invoice_label']   ?? '',
        'customer_id'     => $_POST['customer_id']     ?? '',
        'invoice_date'    => $_POST['invoice_date']    ?? '',
        'due_date'        => $_POST['due_date']        ?: null,
        'account_number'  => $_POST['account_number']  ?: null,
        'bank_name'       => $_POST['bank_name']       ?: null,
        'swift_code'      => $_POST['swift_code']      ?: null,
        'notes'           => $_POST['notes']           ?: null,
        'subtotal'        => (float)($_POST['subtotal'] ?? 0),
        'discount'        => (float)($_POST['discount'] ?? 0),
        'total'           => (float)($_POST['total']    ?? 0),
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

            // ---------- check stock before insert ----------
            $stockRes = $DB->read("stock", ['where' => ['product_id' => ['=' => $product_id]]]);
            if ($stockRes && mysqli_num_rows($stockRes) > 0) {
                $stock = mysqli_fetch_assoc($stockRes);
                $sold  = $stock['sold_stock'] ?? 0;
                $dead  = $stock['dead_stock'] ?? 0;
                $available = $stock['current_stock'] - $sold - $dead;

                if ($qty > $available) {
                    // ❌ Not enough stock → prevent save & show alert
                    echo "<script>alert('Not enough stock for {$row['product_name']}! Available: {$available}, Requested: {$qty}'); window.history.back();</script>";
                    exit;
                }

                // After inserting this qty
                $remaining = $available - $qty;

                if ($remaining < 100) {
                    send_message_TG(
                        "Low Stock Alert\nProduct Name: {$row['product_name']}\nCurrent Stock: {$stock['current_stock']}\nPending Stock: {$remaining}"
                    );
                }
            }

            // ---------- insert item ----------
            $DB->create('invoice_items', [
                'invoice_id',
                'product_id',
                'rate',
                'quantity',
                'amount'
            ], [
                $invoice_id,
                $product_id,
                (float)($row['rate']  ?? 0),
                $qty,
                (float)($row['amount'] ?? 0)
            ]);

            // ---------- update sold stock ----------
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
            <div class="row">
                <div class="col-xl-9">
                    <form method="post" autocomplete="off">
                        <!-- hidden id for update -->
                        <?php if ($edit_mode || $view_mode) { ?>
                            <input type="hidden" name="invoice_id" value="<?php echo $invoice['id'] ?? '' ?>">
                        <?php } ?>

                        <div class="invoice-content">
                            <div class="invoice-detail-body">

                                <!-- Invoice label -->
                                <div class="invoice-detail-title">
                                    <div class="invoice-title">
                                        <input type="text"
                                            name="invoice_label"
                                            class="form-control"
                                            placeholder="Invoice Label"
                                            value="<?php echo $invoice['invoice_label'] ?? 'Invoice' ?>"
                                            <?php echo $view_mode ? 'readonly' : '' ?>>
                                    </div>
                                </div>

                                <!-- Client section -->
                                <div class="invoice-detail-header">
                                    <div class="row justify-content-between">
                                        <div class="col-xl-5 invoice-address-client">
                                            <h4>Bill To:-</h4>
                                            <div class="invoice-address-client-fields">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label-sm">Email</label>
                                                    <div class="col-sm-9">
                                                        <select name="customer_id" class="form-select" <?php echo $view_mode ? 'disabled' : '' ?> require>
                                                            <option value="">Choose customer…</option>
                                                            <?php while ($row = mysqli_fetch_assoc($customersRes)): ?>
                                                                <option value="<?php echo $row['id'] ?>"
                                                                    <?php echo isset($invoice['customer_id']) && $invoice['customer_id'] == $row['id'] ? 'selected' : '' ?>>
                                                                    <?php echo $row['email'] ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dates -->
                                <div class="invoice-detail-terms">
                                    <div class="row justify-content-between">
                                        <div class="col-md-3">
                                            <label>Invoice Date</label>
                                            <input type="date" name="invoice_date" class="form-control form-control-sm"
                                                value="<?php echo $invoice['invoice_date'] ?? date('Y-m-d') ?>" <?php echo $view_mode ? 'readonly' : '' ?>>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Due Date</label>
                                            <input type="date" name="due_date" class="form-control form-control-sm"
                                                value="<?php echo $invoice['due_date'] ?? date('Y-m-d', strtotime('+15 days')) ?>"
                                                <?php echo $view_mode ? 'readonly' : '' ?>>
                                        </div>
                                    </div>
                                </div>

                                <!-- Items -->
                                <div class="invoice-detail-items">
                                    <div class="table-responsive">
                                        <table class="table item-table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Title</th>
                                                    <th>Rate</th>
                                                    <th>Qty</th>
                                                    <th class="text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="item-rows">
                                                <?php
                                                $rows = empty($items) ? [[]] : $items;
                                                foreach ($rows as $idx => $it) { ?>
                                                    <tr>
                                                        <td class="delete-item-row">
                                                            <?php if (!$view_mode) { ?>
                                                                <a href="javascript:void(0)" class="text-danger delete-item" title="Delete">✕</a>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="description">
                                                            <select name="items[<?php echo $idx ?>][product_id]" class="form-select" <?php echo $view_mode ? 'disabled' : '' ?>>
                                                                <option value="">Choose product…</option>
                                                                <?php
                                                                foreach ($products as $product) {  ?>
                                                                    <option value="<?php echo $product['id'] ?>">
                                                                        <?php echo $product['name']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td class="rate">
                                                            <input type="number" step="0.01" name="items[<?php echo $idx ?>][rate]"
                                                                class="form-control rate-input"
                                                                style="min-width:120px"
                                                                value="<?php echo $it['rate'] ?? '' ?>"
                                                                <?php echo $view_mode ? 'readonly' : '' ?>>
                                                        </td>
                                                        <td class="qty">
                                                            <input type="number" name="items[<?php echo $idx ?>][quantity]" class="form-control  qty-input" placeholder="0" style="min-width:120px"
                                                                value="<?php echo $it['quantity'] ?? '' ?>" <?php echo $view_mode ? 'readonly' : '' ?>>
                                                        </td>
                                                        <td class="text-right amount">
                                                            $<span class="item-amount"><?php echo number_format(($it['rate'] ?? 0) * ($it['quantity'] ?? 0), 2) ?></span>
                                                            <input type="hidden" name="items[<?php echo $idx ?>][amount]" class="amount-input" value="<?php echo ($it['rate'] ?? 0) * ($it['quantity'] ?? 0) ?>">
                                                        </td>
                                                    </tr>
                                                <?php }; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php if (!$view_mode) { ?>
                                        <button type="button" class="btn btn-dark btn-sm additem">Add Item</button>
                                    <?php } ?>
                                </div>

                                <!-- Totals -->
                                <div class="invoice-detail-total">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label-sm">Account #</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="account_number" class="form-control form-control-sm" placeholder="Bank Account Number"
                                                        value="<?php echo $invoice['account_number'] ?? '' ?>" <?php echo $view_mode ? 'readonly' : '' ?>>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label-sm">Bank Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="bank_name" class="form-control form-control-sm" placeholder="Insert Bank Name"
                                                        value="<?php echo $invoice['bank_name'] ?? '' ?>" <?php echo $view_mode ? 'readonly' : '' ?>>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label-sm">SWIFT code</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="swift_code" class="form-control form-control-sm" placeholder="Insert Code"
                                                        value="<?php echo $invoice['swift_code'] ?? '' ?>" <?php echo $view_mode ? 'readonly' : '' ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="totals-row">
                                                <div class="invoice-totals-row">
                                                    <div class="invoice-summary-label">Subtotal</div>
                                                    <div class="invoice-summary-value">
                                                        $<span id="subtotal-display"><?php echo number_format($invoice['subtotal'] ?? 0, 2) ?></span>
                                                        <input type="hidden" name="subtotal" id="subtotal-input" value="<?php echo $invoice['subtotal'] ?? 0 ?>">
                                                    </div>
                                                </div>
                                                <div class="invoice-totals-row">
                                                    <div class="invoice-summary-label">Total</div>
                                                    <div class="invoice-summary-value">
                                                        $<span id="total-display"><?php echo number_format($invoice['total'] ?? 0, 2) ?></span>
                                                        <input type="hidden" name="total" id="total-input" value="<?php echo $invoice['total'] ?? 0 ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="invoice-detail-note">
                                    <label>Notes</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder='Notes - For example, "Thank you for doing business with us"'
                                        <?php echo $view_mode ? 'readonly' : '' ?>><?php echo $invoice['notes'] ?? '' ?></textarea>
                                </div>

                                <!-- Save / Preview buttons -->
                                <div class="invoice-actions-btn mt-4">
                                    <div class="row">
                                        <?php if (!$view_mode) { ?>
                                            <div class="col-md-4 mb-2">
                                                <button type="submit" class="btn btn-success w-100">Save Invoice</button>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-4 mb-2">
                                            <a href="./invoice.php" class="btn btn-outline-dark w-100">Back</a>
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

<script>
    /* map id → price */
    const productPriceMap = {
        <?php foreach ($products as $p):
            $price = !empty($p['regular_price']) ? $p['regular_price'] : $p['sale_price']; ?>
            <?php echo $p['id']; ?>: <?php echo (float)$price; ?>,
        <?php endforeach; ?>

    }

    document.addEventListener('change', e => {
        if (!e.target.matches('select[name$="[product_id]"]')) return;
        const row = e.target.closest('tr');
        const price = productPriceMap[e.target.value] || 0;
        const rateInput = row.querySelector('.rate-input');
        if (rateInput) {
            rateInput.value = price.toFixed(2);
            rateInput.dispatchEvent(new Event('input'));
        }
    });
</script>

<script>
    /* recalc amount = rate * qty */
    function recalcAmount(row) {
        const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        const amount = (rate * qty).toFixed(2);

        row.querySelector('.item-amount').textContent = amount;
        row.querySelector('.amount-input').value = amount;
    }

    function bindCalc(row) {
        ['input', 'change'].forEach(evt => ['.rate-input', '.qty-input'].forEach(sel =>
            row.querySelector(sel)?.addEventListener(evt, () => recalcAmount(row))
        ));
    }

    document.querySelectorAll('#item-rows tr').forEach(bindCalc);

    document.addEventListener('click', e => {
        if (e.target && e.target.classList.contains('additem')) {
            setTimeout(() => {
                const newRow = document.querySelector('#item-rows tr:last-child');
                bindCalc(newRow);
                recalcAmount(newRow);
            }, 0);
        }
    });

    document.addEventListener('click', e => {
        if (e.target.matches('.delete-item')) {
            e.preventDefault();
            const tr = e.target.closest('tr');
            if (tr) tr.remove();
        }
    });
</script>

<script>
    /* Recalculate totals without tax */
    function recalcTotals() {
        let subtotal = 0;
        document.querySelectorAll('#item-rows tr').forEach(row => {
            subtotal += parseFloat(row.querySelector('.amount-input')?.value || 0);
        });

        const discount = 0;
        const total = subtotal - discount;

        document.getElementById('subtotal-display').textContent = subtotal.toFixed(2);
        document.getElementById('subtotal-input').value = subtotal.toFixed(2);


        // document.getElementById('discount-display').textContent = discount.toFixed(2);

        document.getElementById('total-display').textContent = total.toFixed(2);
        document.getElementById('total-input').value = total.toFixed(2);
    }


    document.addEventListener('input', e => {
        if (e.target.matches('.rate-input, .qty-input, #discount-input')) {
            if (e.target.matches('.rate-input, .qty-input')) {
                const row = e.target.closest('tr');
                recalcAmount(row);
            }
            recalcTotals();
        }
    });

    document.addEventListener('DOMContentLoaded', recalcTotals);
</script>

<script>
    <?php if (!$view_mode) { ?>
        document.querySelector('.additem')?.addEventListener('click', () => {
            const tbody = document.getElementById('item-rows');
            const idx = tbody.rows.length;
            const tr = document.createElement('tr');

            tr.innerHTML = `
            <td><a href="javascript:void(0)" class="text-danger delete-item">✕</a></td>
            <td class="description">
                <select name="items[${idx}][product_id]" class="form-select">
                    <option value="">Choose product…</option>
                    <?php foreach ($products as $p) { ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${idx}][rate]"
                    class="form-control rate-input"
                    style="min-width:120px"
                    value="">
            </td>
            <td>
                <input type="number" name="items[${idx}][quantity]"
                    class="form-control qty-input"
                    placeholder="0"
                    style="min-width:120px"
                    value="">
            </td>
            <td class="text-right amount">
                $<span class="item-amount">0.00</span>
                <input type="hidden" name="items[${idx}][amount]" class="amount-input" value="0">
            </td>
        `;

            tbody.appendChild(tr);
            bindCalc(tr);
            recalcAmount(tr);
            recalcTotals();
        });
    <?php } ?>
</script>




<?php include './include/footer-staff.php'; ?>