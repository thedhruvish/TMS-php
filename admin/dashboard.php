<?php

require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

/* ==========================================================================
   1. METRICS CALCULATION
   ========================================================================== */
$totalCustomers   = 0;
$totalProducts    = 0;
$totalInvoices    = 0;
$thisWeekSales    = 0;
$todayAttendance  = 0;
$todaysInquiries  = 0;
$todaySales       = 0;
$outstandingPayments = 0;


// --- Existing Metrics ---
$totalCustomers  = (int) mysqli_fetch_assoc($DB->custom_query("SELECT COUNT(*) AS c FROM customer"))['c'];
$totalProducts   = (int) mysqli_fetch_assoc($DB->custom_query("SELECT COUNT(*) AS p FROM products WHERE disabled = 0"))['p'];
$totalInvoices   = (int) mysqli_fetch_assoc($DB->custom_query("SELECT COUNT(*) AS i FROM invoices"))['i'];
$thisWeekSales   = (float) mysqli_fetch_assoc($DB->custom_query(
  "SELECT COALESCE(SUM(total),0) AS w FROM invoices 
         WHERE YEARWEEK(invoice_date, 1) = YEARWEEK(CURDATE(), 1)"
))['w'];
$todayAttendance = (int) mysqli_fetch_assoc($DB->custom_query(
  "SELECT COUNT(*) AS a FROM attendance 
         WHERE att_date = CURDATE() AND status = 'P'"
))['a'];

// --- Today's Inquiries ---
$todaysInquiries = (int) mysqli_fetch_assoc($DB->custom_query(
  "SELECT COUNT(*) AS cnt FROM inquiry WHERE DATE(created_at) = CURDATE()"
))['cnt'];


// --- New Metrics ---
// Today's Sales
$todaySales = (float) mysqli_fetch_assoc($DB->custom_query(
  "SELECT COALESCE(SUM(total),0) AS s FROM invoices WHERE invoice_date = CURDATE()"
))['s'];

// Outstanding Payments (Total of all invoices minus total amount paid)
$totalInvoiceAmount = (float) mysqli_fetch_assoc($DB->custom_query("SELECT COALESCE(SUM(total), 0) FROM invoices"))['COALESCE(SUM(total), 0)'];
$totalPaidAmount = (float) mysqli_fetch_assoc($DB->custom_query("SELECT COALESCE(SUM(amount_paid), 0) FROM payments"))['COALESCE(SUM(amount_paid), 0)'];
$outstandingPayments = $totalInvoiceAmount - $totalPaidAmount;


/* ==========================================================================
   2. WIDGETS DATA
   ========================================================================== */

// --- Recent Invoices ---
$recentInvoices = [];
$res = $DB->custom_query(
  "SELECT i.id, i.invoice_date, i.total, 
            CONCAT(c.first_name,' ',c.last_name) AS customer
     FROM invoices i
     LEFT JOIN customer c ON c.id = i.customer_id
     ORDER BY i.id DESC LIMIT 5"
);
while ($r = mysqli_fetch_assoc($res)) {
  $recentInvoices[] = $r;
}

// --- Top 5 Selling Products ---
$topProducts = [];
$res_top_products = $DB->custom_query(
  "SELECT p.name, SUM(ii.quantity) as total_sold
   FROM invoice_items ii
   JOIN products p ON p.id = ii.product_id
   WHERE p.disabled = 0
   GROUP BY ii.product_id
   ORDER BY total_sold DESC LIMIT 5"
);
if ($res_top_products) {
  while ($r = mysqli_fetch_assoc($res_top_products)) {
    $topProducts[] = $r;
  }
}

// --- Pending Payments ---
$pendingPayments = [];
$res_pending_payments = $DB->custom_query("
    SELECT
        i.id,
        i.due_date,
        i.total,
        CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
        COALESCE(SUM(p.amount_paid), 0) AS total_paid
    FROM invoices i
    JOIN customer c ON i.customer_id = c.id
    LEFT JOIN payments p ON i.id = p.invoice_id
    GROUP BY i.id, i.due_date, i.total, customer_name
    HAVING i.total > total_paid
    ORDER BY i.due_date ASC
    LIMIT 5
");
if ($res_pending_payments) {
    while ($r = mysqli_fetch_assoc($res_pending_payments)) {
        $pendingPayments[] = $r;
    }
}


// --- Recent Login Activity ---
$recentLogs = [];
$res_logs = $DB->custom_query(
  "SELECT email, login_time, is_success FROM user_log ORDER BY id DESC LIMIT 5"
);
if ($res_logs) {
  while ($r = mysqli_fetch_assoc($res_logs)) {
    $recentLogs[] = $r;
  }
}

/* ---------- Safe echo function ---------- */
function e($string)
{
  return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

<div class="secondary-nav">
  <div class="breadcrumbs-container" data-page-heading="Analytics">
    <header class="header navbar navbar-expand-sm">
      <a href="javascript:void(0);" class="btn-toggle sidebarCollapse">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="feather feather-menu">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </a>
    </header>
  </div>
</div>
<div class="row layout-top-spacing">

  <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 layout-spacing">
    <div class="widget widget-card-four">
      <div class="widget-content">
        <div class="w-header">
          <h6 class="value">Total Customers</h6>
        </div>
        <div class="w-content">
          <p class="value fs-4 fw-bold"><?php echo number_format($totalCustomers) ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 layout-spacing">
    <div class="widget widget-card-four">
      <div class="widget-content">
        <div class="w-header">
          <h6 class="value">Products</h6>
        </div>
        <div class="w-content">
          <p class="value fs-4 fw-bold"><?php echo number_format($totalProducts) ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 layout-spacing">
    <div class="widget widget-card-four">
      <div class="widget-content">
        <div class="w-header">
          <h6 class="value">Invoices</h6>
        </div>
        <div class="w-content">
          <p class="value fs-4 fw-bold"><?php echo number_format($totalInvoices) ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 layout-spacing">
    <div class="widget widget-card-four">
      <div class="widget-content">
        <div class="w-header">
          <h6 class="value">Today's Sales</h6>
        </div>
        <div class="w-content">
          <p class="value fs-4 fw-bold">₹ <?php echo number_format($todaySales, 2) ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 layout-spacing">
    <div class="widget widget-card-four">
      <div class="widget-content">
        <div class="w-header">
          <h6 class="value">Weekly Sales</h6>
        </div>
        <div class="w-content">
          <p class="value fs-4 fw-bold">₹ <?php echo number_format($thisWeekSales, 2) ?></p>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 layout-spacing">
    <div class="widget widget-card-four">
      <div class="widget-content">
        <div class="w-header">
          <h6 class="value">Outstanding</h6>
        </div>
        <div class="w-content">
          <p class="value fs-4 fw-bold text-warning">₹ <?php echo number_format($outstandingPayments, 2) ?></p>
        </div>
      </div>
    </div>
  </div>


  <div class="col-xl-6 col-lg-12 col-12 layout-spacing">
    <div class="widget widget-four">
      <div class="widget-heading">
        <h5>Recent Invoices</h5>
      </div>
      <div class="widget-content">
        <?php if (empty($recentInvoices)) : ?>
            <p class="text-center">No recent invoices.</p>
        <?php else: ?>
            <?php foreach ($recentInvoices as $inv) : ?>
              <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                <span>#<?php echo e($inv['id']) ?> – <?php echo e($inv['customer']) ?></span>
                <strong>₹ <?php echo number_format($inv['total'], 2) ?></strong>
              </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-xl-6 col-lg-12 col-12 layout-spacing">
    <div class="widget widget-four">
      <div class="widget-heading">
        <h5>Today Overview</h5>
      </div>
      <div class="widget-content">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
          <span>Staff Present</span>
          <strong><?php echo number_format($todayAttendance) ?></strong>
        </div>
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
          <span>New Inquiries</span>
          <strong><?php echo number_format($todaysInquiries) ?></strong>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-6 col-lg-12 col-12 layout-spacing">
    <div class="widget widget-four">
      <div class="widget-heading">
        <h5 class="text-success">Top 5 Selling Products</h5>
      </div>
      <div class="widget-content">
        <?php if (empty($topProducts)) : ?>
            <p class="text-center">No sales data available.</p>
        <?php else: ?>
            <?php foreach ($topProducts as $product) : ?>
              <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                <span><?php echo e($product['name']) ?></span>
                <strong><?php echo number_format($product['total_sold']) ?> Units Sold</strong>
              </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-xl-6 col-lg-12 col-12 layout-spacing">
    <div class="widget widget-four">
      <div class="widget-heading">
        <h5 class="text-warning">Upcoming Payment Deadlines</h5>
      </div>
      <div class="widget-content">
        <?php if (empty($pendingPayments)) : ?>
            <p class="text-center">No pending payments. Great job! 👍</p>
        <?php else: ?>
            <?php foreach ($pendingPayments as $payment) :
                $pendingAmount = $payment['total'] - $payment['total_paid'];
                $dueDate = new DateTime($payment['due_date']);
                $today = new DateTime();
                $isOverdue = $dueDate < $today && $dueDate->format('Y-m-d') != $today->format('Y-m-d');
            ?>
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                    <div>
                        <span><?php echo e($payment['customer_name']); ?></span><br>
                        <small class="text-muted">
                            Due: <?php echo date('M d, Y', strtotime($payment['due_date'])); ?>
                            <?php if ($isOverdue): ?>
                                <span class="badge bg-danger ms-1">Overdue</span>
                            <?php endif; ?>
                        </small>
                    </div>
                    <strong class="text-danger">₹<?php echo number_format($pendingAmount, 2); ?></strong>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <div class="col-xl-12 col-lg-12 col-12 layout-spacing">
    <div class="widget widget-four">
      <div class="widget-heading">
        <h5>Recent Login Activity</h5>
      </div>
      <div class="widget-content">
        <?php if (empty($recentLogs)) : ?>
            <p class="text-center">No recent user activity.</p>
        <?php else: ?>
            <?php foreach ($recentLogs as $log) : ?>
              <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                <div>
                  <span class="fw-bold"><?php echo e($log['email']); ?></span>
                  <small class="text-muted ms-2"><?php echo date('d-M-Y H:i', strtotime($log['login_time'])) ?></small>
                </div>
                <?php if ($log['is_success'] == 1) : ?>
                  <span class="badge bg-light-success text-success">Success</span>
                <?php else: ?>
                  <span class="badge bg-light-danger text-danger">Failed</span>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<?php include './include/footer-admin.php'; ?>