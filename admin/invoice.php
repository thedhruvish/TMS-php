<?php 
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$invoices = null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Updated SQL query to include a join with the users table
$sql = "
    SELECT
        i.*,
        CONCAT(c.first_name,' ',c.last_name) AS client_name,
        c.email AS client_email,
        SUM(p.amount_paid) AS paid_amount,
        u.name AS created_by_name
    FROM invoices i
    JOIN customer c ON c.id = i.customer_id
    LEFT JOIN payments p ON p.invoice_id = i.id
    LEFT JOIN users u ON i.created_by = u.id
";

if ($search !== '') {
  // Note: This method is vulnerable to SQL injection. Consider using prepared statements.
  $sql .= " WHERE (
                c.email      LIKE '%$search%' OR
                c.first_name LIKE '%$search%' OR
                c.last_name  LIKE '%$search%' OR
                i.total      LIKE '%$search%'
            )";
}

// Added u.name to the GROUP BY clause
$sql .= "
    GROUP BY i.id, c.first_name, c.last_name, c.email, u.name
    ORDER BY i.id DESC
";

$invoices = $DB->custom_query($sql);

?>

<div class="row">
  <div class="seperator-header layout-top-spacing">
    <h4 class="">Invoice</h4>
    <a href="invoice-add.php" class="btn btn-primary">Add New Invoice</a>
  </div>

  <div class="row mb-4 align-items-center justify-content-between">
    <div class="col-lg-8 d-flex align-items-center gap-3">
      <form method="get" class="d-flex flex-grow-1 gap-2">
        <input type="text" name="search" class="form-control" style="max-width: 300px;" placeholder="Search Email..."
          value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary px-3">Search</button>
        <?php if (!empty($search)) { ?>
          <a href="invoice.php" class="btn btn-outline-secondary">Clear</a>
        <?php } ?>
      </form>

      <div class="d-flex align-items-center">
        <label class="me-2 mb-0">Payment Status:</label>
        <select class="form-select form-select-sm" id="paymentStatusFilter" style="width: 120px;">
          <option value="all">All</option>
          <option value="paid">Paid</option>
          <option value="pending">Pending</option>
        </select>
      </div>
    </div>
  </div>

  <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">
    <div class="widget-content widget-content-area br-8">
      <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
        <table class="table dt-table-hover" style="width:100%" id="invoiceTable">
          <thead class="sticky-top" style="background-color: #f8f9fa; z-index: 1;">
            <tr>
              <th>Invoice Id</th>
              <th>Name</th>
              <th>Email</th>
              <th>Total Amount</th>
              <th>Paid Amount</th>
              <th>Pending Amount</th>
              <th>Status</th>
              <th>Created By</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($invoices && mysqli_num_rows($invoices) > 0) {
              while ($row = mysqli_fetch_assoc($invoices)) {
                $paid_amount = $row['paid_amount'] ? floatval($row['paid_amount']) : 0;
                $total_amount = floatval($row['total']);
                $pending_amount = $total_amount - $paid_amount;
                $status = ($paid_amount >= $total_amount) ? 'paid' : 'pending';
                ?>
                <tr class="invoice-row" data-status="<?php echo $status; ?>">
                  <td><a href="./invoice-add.php"><span
                        class="inv-number"><?php echo htmlspecialchars($row['id']); ?></span></a></td>
                  <td>
                    <div class="d-flex">
                      <p class="align-self-center mb-0 user-name"><?php echo htmlspecialchars($row['client_name']); ?></p>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($row['client_email']); ?></td>
                  <td><span class="inv-amount">$<?php echo number_format($total_amount, 2); ?></span></td>
                  <td><span class="inv-amount">$<?php echo number_format($paid_amount, 2); ?></span></td>
                  <td><span class="inv-amount">$<?php echo number_format($pending_amount, 2); ?></span></td>
                  <td>
                    <?php if ($status === 'paid') { ?>
                      <span class="badge bg-success">Paid</span>
                    <?php } else { ?>
                      <span class="badge bg-warning">Pending</span>
                    <?php } ?>
                  </td>
                  <td><?php echo htmlspecialchars($row['created_by_name'] ?? 'N/A'); ?></td>
                  <td>
                    <span class="inv-date">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-calendar">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                      </svg>
                      <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                    </span>
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <a class="btn btn-sm btn-outline-primary" href="./invoice-preview.php?id=<?php echo $row['id']; ?>"
                        target="_blank" rel="noopener noreferrer" title="View">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="feather feather-eye">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                          <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                      </a>
                      <a class="btn btn-sm btn-outline-secondary" href="./invoice-add.php?u_id=<?php echo $row['id']; ?>"
                        title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="feather feather-edit">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                      </a>
                      <a class="btn btn-sm btn-outline-danger" href="./invoice-add.php?d_id=<?php echo $row['id']; ?>"
                        title="Delete" onclick="return confirm('Are you sure you want to delete this invoice?');">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="feather feather-trash-2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          <line x1="10" y1="11" x2="10" y2="17"></line>
                          <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php
              }
            } else {
              ?>
              <tr>
                <td colspan="10" class="text-center py-4">No invoices found</td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
  .table-responsive {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
  }

  .sticky-top {
    position: sticky;
    top: 0;
  }

  .inv-date {
    white-space: nowrap;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const statusFilter = document.getElementById('paymentStatusFilter');
    const invoiceRows = document.querySelectorAll('.invoice-row');

    function filterInvoices() {
      const statusValue = statusFilter.value;

      invoiceRows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');

        if (statusValue === 'all' || rowStatus === statusValue) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    statusFilter.addEventListener('change', filterInvoices);
  });
</script>

<?php include_once './include/footer-admin.php'; ?>