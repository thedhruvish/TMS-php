<?php $pageTitle = "Invoice";

require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$invoices = null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "
    SELECT
        i.*,
        CONCAT(c.first_name,' ',c.last_name) AS client_name,
        c.email AS client_email,
        SUM(p.amount_paid) AS paid_amount
    FROM invoices i
    JOIN customer c ON c.id = i.customer_id
    LEFT JOIN payments p ON p.invoice_id = i.id
";

if ($search !== '') {
  $sql .= " WHERE (
                c.email      LIKE '%$search%' OR
                c.first_name LIKE '%$search%' OR
                c.last_name  LIKE '%$search%' OR
                i.total      LIKE '%$search%'
            )";
}

$sql .= "
    GROUP BY i.id, c.first_name, c.last_name, c.email
    ORDER BY i.id DESC
";

$invoices = $DB->custom_query($sql);

?>

<div class="row">
  <div class="seperator-header layout-top-spacing">
    <h4 class="">Invoice</h4>
    <a href="invoice-add.php" class="btn btn-primary">Add New Invoice</a>
  </div>



  <!-- Search and Filter Section -->
  <div class="row mb-4 align-items-center justify-content-between">
    <div class="col-lg-6 d-flex align-items-center">
      <form method="get" class="d-flex flex-grow-1 gap-2">
        <input type="text" name="search" class="form-control" style="max-width: 300px;"
          placeholder="Search Email..." value="<?php echo @$search ?>">
        <button type="submit" class="btn btn-primary px-3">Search</button>
        <?php if (!empty($search)) { ?>
          <a href="invoice.php" class="btn btn-outline-secondary">Clear</a>
        <?php } ?>
      </form>
    </div>
  </div>

  <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">
    <div class="widget-content widget-content-area br-8">
      <table class="table dt-table-hover" style="width:100%">
        <thead>
          <tr>
            <th>Invoice Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Total Amount</th>
            <th>Paid Amount</th>
            <th>Panding Amount</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($invoices)) { ?>
            <tr>

              <td><a href="./invoice-add.php"><span class="inv-number"><?php echo $row['id']; ?></span></a></td>
              <td>
                <div class="d-flex">
                  <p class="align-self-center mb-0 user-name"><?php echo $row['client_name']; ?></p>
                </div>
              </td>
              <td><?php echo $row['client_email']; ?></td>
              <td><span class="inv-amount">$<?php echo $row['total']; ?></span></td>
              <td><span class="inv-amount">$<?php echo $row['paid_amount']; ?></span></td>
              <td><span class="inv-amount">$<?php echo $row['total'] - $row['paid_amount']; ?></span></td>
              <td>
                <span class="inv-date"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                  </svg> <?php echo $row['created_at']; ?> </span>
              </td>
              <td>
                <a class="badge badge-light-primary text-start action-view" href="./invoice-preview.php?id=<?php echo $row['id']; ?>" target="_blank" rel="noopener noreferrer">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                  </svg>
                </a>
                <a class="badge badge-light-primary text-start action-edit" href="./invoice-add.php?u_id=<?php echo $row['id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                  </svg></a>
                <a class="badge badge-light-danger text-start action-delete" href="./invoice-add.php?d_id=<?php echo $row['id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg></a>

              </td>

            </tr>
          <?php } ?>

        </tbody>
      </table>
    </div>
  </div>
</div>


<?php include_once './include/footer-admin.php'; ?>