<?php
$pageTitle = "Invoice add";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$invoice = [];
$items   = [];
$edit_mode = false;
$view_mode = false;

/* ---------- POST handler (insert / update) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_data = [
        'invoice_label'   => $_POST['invoice_label']   ?? '',
        'client_name'     => $_POST['client_name']     ?? '',
        'client_email'    => $_POST['client_email']    ?? '',
        'client_address'  => $_POST['client_address']  ?? '',
        'client_phone'    => $_POST['client_phone']    ?? '',
        'invoice_number'  => $_POST['invoice_number']  ?? '',
        'invoice_date'    => $_POST['invoice_date']    ?? '',
        'due_date'        => $_POST['due_date']        ?: null,
        'account_number'  => $_POST['account_number']  ?: null,
        'bank_name'       => $_POST['bank_name']       ?: null,
        'swift_code'      => $_POST['swift_code']      ?: null,
        'notes'           => $_POST['notes']           ?: null,
        'subtotal'        => (float)($_POST['subtotal'] ?? 0),
        'discount'        => (float)($_POST['discount'] ?? 0),
        'tax'             => (float)($_POST['tax']      ?? 0),
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
            $DB->create('invoice_items', [
                'invoice_id',
                'description',
                'additional_details',
                'rate',
                'quantity',
                'amount',
                'taxable'
            ], [
                $invoice_id,
                $row['description']        ?? '',
                $row['additional_details'] ?: null,
                (float)($row['rate']  ?? 0),
                (int)($row['quantity'] ?? 0),
                (float)($row['amount'] ?? 0),
                isset($row['taxable']) ? 1 : 0
            ]);
        }
    }

    header("Location: invoice-add.php?u_id=$invoice_id");
    exit;
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
                        <?php if ($edit_mode || $view_mode): ?>
                            <input type="hidden" name="invoice_id" value="<?= htmlspecialchars($invoice['id'] ?? '') ?>">
                        <?php endif; ?>

                        <div class="invoice-content">
                            <div class="invoice-detail-body">

                                <!-- Invoice label -->
                                <div class="invoice-detail-title">
                                    <div class="invoice-title">
                                        <input type="text"
                                            name="invoice_label"
                                            class="form-control"
                                            placeholder="Invoice Label"
                                            value="<?= htmlspecialchars($invoice['invoice_label'] ?? 'Invoice') ?>"
                                            <?= $view_mode ? 'readonly' : '' ?>>
                                    </div>
                                </div>

                                <!-- Client section -->
                                <div class="invoice-detail-header">
                                    <div class="row justify-content-between">
                                        <div class="col-xl-5 invoice-address-client">
                                            <h4>Bill To:-</h4>
                                            <div class="invoice-address-client-fields">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label-sm">Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="client_name" class="form-control form-control-sm" placeholder="Client Name"
                                                            value="<?= htmlspecialchars($invoice['client_name'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label-sm">Email</label>
                                                    <div class="col-sm-9">
                                                        <input type="email" name="client_email" class="form-control form-control-sm" placeholder="name@company.com"
                                                            value="<?= htmlspecialchars($invoice['client_email'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label-sm">Address</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="client_address" class="form-control form-control-sm" placeholder="XYZ Street"
                                                            value="<?= htmlspecialchars($invoice['client_address'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label-sm">Phone</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="client_phone" class="form-control form-control-sm" placeholder="(123) 456 789"
                                                            value="<?= htmlspecialchars($invoice['client_phone'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
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
                                            <label>Invoice Number</label>
                                            <input type="text" name="invoice_number" class="form-control form-control-sm" placeholder="#0001"
                                                value="<?= htmlspecialchars($invoice['invoice_number'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Invoice Date</label>
                                            <input type="date" name="invoice_date" class="form-control form-control-sm"
                                                value="<?= htmlspecialchars($invoice['invoice_date'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Due Date</label>
                                            <input type="date" name="due_date" class="form-control form-control-sm"
                                                value="<?= htmlspecialchars($invoice['due_date'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
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
                                                    <th>Description</th>
                                                    <th>Rate</th>
                                                    <th>Qty</th>
                                                    <th class="text-right">Amount</th>
                                                    <th class="text-center">Tax</th>
                                                </tr>
                                            </thead>
                                            <tbody id="item-rows">
                                                <?php
                                                $rows = empty($items) ? [[]] : $items;
                                                foreach ($rows as $idx => $it): ?>
                                                    <tr>
                                                        <td class="delete-item-row">
                                                            <?php if (!$view_mode): ?>
                                                                <a href="javascript:void(0)" class="text-danger delete-item" title="Delete">✕</a>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="description">
                                                            <input type="text" name="items[<?= $idx ?>][description]" class="form-control form-control-sm" placeholder="Item Description"
                                                                value="<?= htmlspecialchars($it['description'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                                            <textarea name="items[<?= $idx ?>][additional_details]" class="form-control" placeholder="Additional Details" <?= $view_mode ? 'readonly' : '' ?>><?= htmlspecialchars($it['additional_details'] ?? '') ?></textarea>
                                                        </td>
                                                        <td class="rate">
                                                            <input type="number" step="0.01" name="items[<?= $idx ?>][rate]" class="form-control form-control-sm" placeholder="0.00"
                                                                value="<?= htmlspecialchars($it['rate'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                                        </td>
                                                        <td class="qty">
                                                            <input type="number" name="items[<?= $idx ?>][quantity]" class="form-control form-control-sm" placeholder="0"
                                                                value="<?= htmlspecialchars($it['quantity'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                                        </td>
                                                        <td class="text-right amount">
                                                            $<span class="item-amount"><?= number_format($it['amount'] ?? 0, 2) ?></span>
                                                            <input type="hidden" name="items[<?= $idx ?>][amount]" value="<?= $it['amount'] ?? 0 ?>">
                                                        </td>
                                                        <td class="text-center tax">
                                                            <input type="checkbox" name="items[<?= $idx ?>][taxable]" <?= (!empty($it['taxable']) ? 'checked' : '') ?> <?= $view_mode ? 'disabled' : '' ?>>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php if (!$view_mode): ?>
                                        <button type="button" class="btn btn-dark btn-sm additem">Add Item</button>
                                    <?php endif; ?>
                                </div>

                                <!-- Totals -->
                                <div class="invoice-detail-total">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label-sm">Account #</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="account_number" class="form-control form-control-sm" placeholder="Bank Account Number"
                                                        value="<?= htmlspecialchars($invoice['account_number'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label-sm">Bank Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="bank_name" class="form-control form-control-sm" placeholder="Insert Bank Name"
                                                        value="<?= htmlspecialchars($invoice['bank_name'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label-sm">SWIFT code</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="swift_code" class="form-control form-control-sm" placeholder="Insert Code"
                                                        value="<?= htmlspecialchars($invoice['swift_code'] ?? '') ?>" <?= $view_mode ? 'readonly' : '' ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="totals-row">
                                                <div class="invoice-totals-row">
                                                    <div class="invoice-summary-label">Subtotal</div>
                                                    <div class="invoice-summary-value">
                                                        $<span id="subtotal-display"><?= number_format($invoice['subtotal'] ?? 0, 2) ?></span>
                                                        <input type="hidden" name="subtotal" id="subtotal-input" value="<?= $invoice['subtotal'] ?? 0 ?>">
                                                    </div>
                                                </div>
                                                <div class="invoice-totals-row">
                                                    <div class="invoice-summary-label">Discount</div>
                                                    <div class="invoice-summary-value">
                                                        $<span id="discount-display"><?= number_format($invoice['discount'] ?? 0, 2) ?></span>
                                                        <input type="hidden" name="discount" id="discount-input" value="<?= $invoice['discount'] ?? 0 ?>">
                                                    </div>
                                                </div>
                                                <div class="invoice-totals-row">
                                                    <div class="invoice-summary-label">Tax</div>
                                                    <div class="invoice-summary-value">
                                                        <span id="tax-display"><?= number_format($invoice['tax'] ?? 0, 2) ?></span>%
                                                        <input type="hidden" name="tax" id="tax-input" value="<?= $invoice['tax'] ?? 0 ?>">
                                                    </div>
                                                </div>
                                                <div class="invoice-totals-row">
                                                    <div class="invoice-summary-label">Total</div>
                                                    <div class="invoice-summary-value">
                                                        $<span id="total-display"><?= number_format($invoice['total'] ?? 0, 2) ?></span>
                                                        <input type="hidden" name="total" id="total-input" value="<?= $invoice['total'] ?? 0 ?>">
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
                                        <?= $view_mode ? 'readonly' : '' ?>><?= htmlspecialchars($invoice['notes'] ?? '') ?></textarea>
                                </div>

                                <!-- Save / Preview buttons -->
                                <div class="invoice-actions-btn mt-4">
                                    <div class="row">
                                        <?php if (!$view_mode): ?>
                                            <div class="col-md-4 mb-2">
                                                <button type="submit" class="btn btn-success w-100">Save Invoice</button>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-md-4 mb-2">
                                            <a href="./app-invoice-preview.html" class="btn btn-secondary w-100">Preview</a>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <a href="javascript:history.back()" class="btn btn-outline-dark w-100">Back</a>
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

<!-- Optional JS for dynamic rows & calculations -->
<script>
    /* Simple dynamic row adder (keep for edit mode) */
    <?php if (!$view_mode): ?>
        document.querySelector('.additem')?.addEventListener('click', () => {
            const tbody = document.getElementById('item-rows');
            const idx = tbody.rows.length;
            const tr = document.createElement('tr');
            tr.innerHTML = `
        <td><a href="javascript:void(0)" class="text-danger delete-item">✕</a></td>
        <td><input type="text" name="items[${idx}][description]" class="form-control form-control-sm" placeholder="Item Description">
            <textarea name="items[${idx}][additional_details]" class="form-control" placeholder="Additional Details"></textarea></td>
        <td><input type="number" step="0.01" name="items[${idx}][rate]" class="form-control form-control-sm"></td>
        <td><input type="number" name="items[${idx}][quantity]" class="form-control form-control-sm"></td>
        <td class="text-right">$<span class="item-amount">0.00</span><input type="hidden" name="items[${idx}][amount]"></td>
        <td><input type="checkbox" name="items[${idx}][taxable]"></td>`;
            tbody.appendChild(tr);
        });
    <?php endif; ?>
</script>

<?php include './include/footer-admin.php'; ?>