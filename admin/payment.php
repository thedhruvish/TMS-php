<?php $pageTitle = "Payment";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';


$select_query = "SELECT payments.*, invoices.id as invoice_id FROM payments LEFT JOIN invoices ON payments.invoice_id = invoices.id";
$payment_data = $DB->custom_query($select_query);

?>


<div class="row">
  <div class="seperator-header layout-top-spacing">
    <h4 class="">PAYMENT</h4>
  </div>
  <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
    <div class="statbox widget box box-shadow">
      <div class="widget-content widget-content-area">
        <table id="html5-extension" class="table dt-table-hover" style="width:100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Invoice Number</th>
              <th>Payment Date</th>
              <th>Amount Paid</th>
              <th>Payment Method</th>
              <th>Reference Number</th>
              <th>Notes</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <?php while ($row = mysqli_fetch_assoc($payment_data)) { ?>
              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['invoice_id']; ?></td>
                <td><?php echo $row['payment_date']; ?></td>
                <td><?php echo $row['amount_paid']; ?></td>
                <td><?php echo $row['payment_method']; ?></td>
                <td><?php echo $row['reference_number']; ?></td>
                <td><?php echo $row['notes']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                  <div class="btn-group">
                    <a href="payment-view.php?id=<?php echo $row['id']; ?>">
                      <button type="button" class="btn btn-dark btn-sm">Open</button>
                    </a>
                    <!-- <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="feather feather-chevron-down" viewBox="0 0 24 24">
            <polyline points="6 9 12 15 18 9" stroke="currentColor" stroke-width="2" fill="none"/>
          </svg>
        </button> -->
                    <!-- <div class="dropdown-menu">
          <a class="dropdown-item" href="#">Copy</a>
          <a class="dropdown-item" href="#">Delete</a>
        </div> -->
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>

        </table>
      </div>
    </div>
  </div>

</div>

<?php require_once './include/footer-admin.php'; ?>