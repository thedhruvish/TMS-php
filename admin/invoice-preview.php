<?php
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';


// Check if ID is set
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("<h3>Invalid Invoice ID</h3>");
}

$invoiceId = intval($_GET['id']);

// Fetch Invoice Details
$invoiceRes = $DB->custom_query("
    SELECT i.*, 
           c.first_name, c.last_name, c.email AS customer_email, c.phone, c.address, c.city, c.state, c.zip, c.country
    FROM invoices i
    LEFT JOIN customer c ON i.customer_id = c.id
    WHERE i.id = {$invoiceId}
");

if (mysqli_num_rows($invoiceRes) === 0) {
  die("<h3>Invoice not found</h3>");
}

$invoice = mysqli_fetch_assoc($invoiceRes);

// Fetch Invoice Items
$itemRes = $DB->custom_query("
    SELECT ii.*, p.name AS product_name ,p.sale_price AS product_price
    FROM invoice_items ii
    LEFT JOIN products p ON ii.product_id = p.id
    WHERE ii.invoice_id = {$invoiceId}
");
$items = mysqli_fetch_all($itemRes, MYSQLI_ASSOC);

?>
<!--  BEGIN CUSTOM STYLE FILE  -->
<link href="../src/assets/css/light/apps/invoice-preview.css" rel="stylesheet" type="text/css" />
<link href="../src/assets/css/dark/apps/invoice-preview.css" rel="stylesheet" type="text/css" />
<!--  END CUSTOM STYLE FILE  -->

<div class="row invoice layout-top-spacing layout-spacing">
  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

    <div class="doc-container">
      <div class="row">
        <div class="col-xl-9">
          <div class="invoice-container">
            <div class="invoice-inbox">
              <div id="ct" class="">
                <div class="invoice-00001">
                  <div class="content-section">

                    <!-- Invoice Header -->
                    <div class="inv--head-section inv--detail-section">
                      <div class="row">
                        <div class="col-sm-6 col-12 mr-auto">
                          <div class="d-flex">
                            <img class="company-logo" src="../src/assets/img/cork-logo.png" alt="company">
                            <h3 class="in-heading align-self-center">Cork Inc.</h3>
                          </div>
                          <p class="inv-street-addr mt-3">XYZ Delta Street</p>
                          <p class="inv-email-address">info@company.com</p>
                          <p class="inv-email-address">(120) 456 789</p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                          <p class="inv-list-number mt-sm-3 pb-sm-2 mt-4">
                            <span class="inv-title">Invoice : </span>
                            <span class="inv-number">#<?php echo htmlspecialchars($invoice['id']); ?></span>
                          </p>
                          <p class="inv-created-date mt-sm-5 mt-3">
                            <span class="inv-title">Invoice Date : </span>
                            <span class="inv-date"><?php echo date("d M Y", strtotime($invoice['invoice_date'])); ?></span>
                          </p>
                          <p class="inv-due-date">
                            <span class="inv-title">Due Date : </span>
                            <span class="inv-date"><?php echo date("d M Y", strtotime($invoice['due_date'])); ?></span>
                          </p>
                        </div>
                      </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="inv--detail-section inv--customer-detail-section">
                      <div class="row">
                        <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4 align-self-center">
                          <p class="inv-to">Invoice To</p>
                        </div>
                        <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4">
                          <p class="inv-customer-name"><?php echo htmlspecialchars($invoice['first_name'] . ' ' . $invoice['last_name']); ?></p>
                          <p class="inv-street-addr"><?php echo htmlspecialchars($invoice['address']); ?>, <?php echo htmlspecialchars($invoice['city']); ?></p>
                          <p class="inv-email-address"><?php echo htmlspecialchars($invoice['customer_email']); ?></p>
                          <p class="inv-email-address"><?php echo htmlspecialchars($invoice['phone']); ?></p>
                        </div>
                      </div>
                    </div>

                    <!-- Items Table -->
                    <div class="inv--product-table-section">
                      <div class="table-responsive">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">S.No</th>
                              <th scope="col">Items</th>
                              <th class="text-end" scope="col">Qty</th>
                              <th class="text-end" scope="col">Price</th>
                              <th class="text-end" scope="col">Amount</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($items as $index => $item) {  ?>
                              <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td class="text-end"><?php echo $item['quantity']; ?></td>
                                <td class="text-end">$<?php echo $item['product_price']; ?></td>
                                <td class="text-end">$<?php echo number_format($item['amount'], 2); ?></td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <!-- Totals -->
                    <div class="inv--total-amounts">
                      <div class="row mt-4">
                        <div class="col-sm-5 col-12 order-sm-0 order-1"></div>
                        <div class="col-sm-7 col-12 order-sm-1 order-0">
                          <div class="text-sm-end">
                            <div class="row">
                              <div class="col-sm-8 col-7">
                                <p>Sub Total :</p>
                              </div>
                              <div class="col-sm-4 col-5">
                                <p>$<?php echo number_format($invoice['subtotal'], 2); ?></p>
                              </div>

                              <div class="col-sm-8 col-7">
                                <p>Tax :</p>
                              </div>
                              <div class="col-sm-4 col-5">
                                <p>$<?php echo number_format($invoice['tax'], 2); ?></p>
                              </div>

                              <div class="col-sm-8 col-7">
                                <p>Discount :</p>
                              </div>
                              <div class="col-sm-4 col-5">
                                <p>$<?php echo number_format($invoice['discount'], 2); ?></p>
                              </div>

                              <div class="col-sm-8 col-7 grand-total-title mt-3">
                                <h4>Grand Total : </h4>
                              </div>
                              <div class="col-sm-4 col-5 grand-total-amount mt-3">
                                <h4>$<?php echo number_format($invoice['total'], 2); ?></h4>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Notes -->
                    <?php if (!empty($invoice['notes'])) { ?>
                      <div class="inv--note">
                        <div class="row mt-4">
                          <div class="col-sm-12 col-12">
                            <p>Note: <?php echo nl2br(htmlspecialchars($invoice['notes'])); ?></p>
                          </div>
                        </div>
                      </div>
                    <?php } ?>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar Buttons -->
        <div class="col-xl-3">
          <div class="invoice-actions-btn">
            <div class="invoice-action-btn">
              <div class="row">

                <div class="col-xl-12 col-md-3 col-sm-6">
                  <a href="javascript:void(0);" class="btn btn-secondary btn-print  action-print">Print</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- row -->
    </div>
  </div>
</div>

<script src="../src/assets/js/apps/invoice-preview.js"></script>
<?php include './include/footer-admin.php'; ?>