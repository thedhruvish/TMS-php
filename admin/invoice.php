<?php $pageTitle = "Invoice";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';
$invoices = null;
if (isset($_GET['search'])) {
  $search = $_GET['search'];

  $invoices = $DB->read('invoices', [
    'or_where' => [
      'client_email' => ['LIKE' => "%$search%"],
      'client_name' => ['LIKE' => "%$search%"],
      'total'        => ['LIKE' => "%$search%"],
    ],
    'order' => 'id DESC'
  ]);
} else {
  $invoices = $DB->read('invoices', ['order' => 'id DESC']);
}
?>

<div class="row">
  <div class="seperator-header layout-top-spacing d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Invoice</h4>
    <a href="invoice-add.php" class="btn btn-primary">Add Invoice</a>
  </div>

  <!-- Search and Filter Section -->
  <div class="row mb-4 align-items-center justify-content-between">
    <div class="col-lg-6 d-flex align-items-center">
      <form method="get" class="d-flex flex-grow-1 gap-2">
        <input type="text" name="search" class="form-control" style="max-width: 300px;"
          placeholder="Search Email..." value="<?= @$searchTerm ?>">
        <button type="submit" class="btn btn-primary px-3">Search</button>
        <?php if (!empty($searchTerm) || !empty($filterCategory)): ?>
          <a href="cetegory.php" class="btn btn-outline-secondary">Clear</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">
    <div class="widget-content widget-content-area br-8">
      <table class="table dt-table-hover" style="width:100%">
        <thead>
          <tr>
            <th class="checkbox-column"> Record no. </th>
            <th>Invoice Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($invoices)) { ?>
            <tr>
              <td class="checkbox-column"> <?php echo $row['id']; ?></td>
              <td><a href="./invoice-add.php"><span class="inv-number"><?php echo $row['id']; ?></span></a></td>
              <td>
                <div class="d-flex">

                  <p class="align-self-center mb-0 user-name"><?php echo $row['client_name']; ?></p>
                </div>
              </td>
              <td><?php echo $row['client_email']; ?></td>
              <td><span class="inv-amount">$<?php echo $row['total']; ?></span></td>
              <td><span class="inv-date"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                  </svg> <?php echo $row['created_at']; ?> </span></td>
              <td>
                <a class="badge badge-light-primary text-start me-2 action-edit" href="./invoice-add.php?u_id=<?php echo $row['id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
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