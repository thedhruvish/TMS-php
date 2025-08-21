<?php
$pageTitle = "Dashboard";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

/* ---------- 1. Real counts ---------- */
$totalCustomers   = 0;
$totalProducts    = 0;
$totalInvoices    = 0;
$thisWeekSales    = 0;
$todayAttendance  = 0;
$pendingInquiries = 0;

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
$pendingInquiries = (int) mysqli_fetch_assoc($DB->custom_query(
  "SELECT COUNT(*) AS cnt FROM inquiry WHERE status='new'"
))['cnt'];

/* ---------- 2. Recent invoices mini-list ---------- */
$recentInvoices = [];
$res = $DB->custom_query(
  "SELECT i.id, i.invoice_date, i.total, 
            CONCAT(c.first_name,' ',c.last_name) AS customer
     FROM invoices i
     LEFT JOIN customer c ON c.id = i.customer_id
     ORDER BY i.id DESC LIMIT 5"
);
while ($r = mysqli_fetch_assoc($res)) $recentInvoices[] = $r;

/* ---------- 3. Safe echo ---------- */
function e($string)
{
  return $string;
}
?>

<!--  BEGIN BREADCRUMBS  -->
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
<!--  END BREADCRUMBS  -->

<div class="row layout-top-spacing justify-content-center">

  <!-- =======  Row #1 – Key Metrics  ======= -->
  <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
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

  <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
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

  <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
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

  <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
    <div class="widget widget-card-four">
      <div class="widget-content">
        <div class="w-header">
          <h6 class="value">Weekly Sales</h6>
        </div>
        <div class="w-content">
          <p class="value fs-4 fw-bold">$ <?php echo number_format($thisWeekSales, 2) ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- =======  Row #2 – Mini-lists  ======= -->
  <div class="col-xl-6 col-lg-12 col-12 layout-spacing">
    <div class="widget widget-four">
      <div class="widget-heading">
        <h5>Recent Invoices</h5>
      </div>
      <div class="widget-content">
        <?php foreach ($recentInvoices as $inv) { ?>
          <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
            <span>#<?php echo e($inv['id']) ?> – <?php echo e($inv['customer']) ?></span>
            <strong>$ <?php echo number_format($inv['total'], 2) ?></strong>
          </div>
        <?php }; ?>
      </div>
    </div>
  </div>

  <div class="col-xl-6 col-lg-12 col-12 layout-spacing">
    <div class="widget widget-four">
      <div class="widget-heading">
        <h5>Today Overview</h5>
      </div>
      <div class="widget-content">
        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
          <span>Staff Present</span>
          <strong><?php echo number_format($todayAttendance) ?></strong>
        </div>
        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
          <span>New Inquiries</span>
          <strong><?php echo number_format($pendingInquiries) ?></strong>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include './include/footer-admin.php'; ?>