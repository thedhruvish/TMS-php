<?php $pageTitle = "Invoice";
require_once './include/header-staff.php';
require_once './include/sidebar-staff.php';

$query = "
    SELECT 
        inquiry.*, 
        users.name AS created_by_name 
    FROM inquiry 
    LEFT JOIN users ON inquiry.created_by = users.id
    ORDER BY inquiry.id DESC
";
$result = $DB->custom_query($query);

if (isset($_GET['d_id'])) {
  $delete = $DB->delete("inquiry", "id", $_GET['d_id']);
  if ($delete) {
    header("Location:inquiry.php");
  } else {
    echo "<div class='alert alert-danger'>Failed to delete.</div>";
  }
}

?>


<div class="row">
  <div class="seperator-header layout-top-spacing">
    <h4 class="">Inquiry</h4>
  </div>
  <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
    <div class="statbox widget box box-shadow">
      <div class="widget-content widget-content-area">
        <table id="html5-extension" class="table dt-table-hover" style="width:100%">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Message</th>
              <th>Date</th>
              <th>Status</th>
              <th>Created By</th>
              <!-- <th>Open</th> -->
              <th>Edit</th>
              <!-- <th>Delete</th> -->
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
              <tr>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['phone'] ?></td>
                <td><?php echo $row['message'] ?></td>
                <td><?php echo $row['created_at'] ?></td>
                <td><?php echo date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                  <?php echo ($row['status'] == '1') ? '<span class="badge badge-success">Done</span>' : '<span class="badge badge-warning">Pending</span>'; ?>
                </td>
                <td><?php echo $row['created_by_name'] ?? 'N/A'; ?></td>
                <!-- <td><a class="dropdown-item" href="inquiry-add.php?id=<?php echo $row['id'] ?>"> <button type="button" class="btn btn-primary btn-sm">Open</button></a></td> -->
                <td><a class="dropdown-item" href="inquiry-add.php?u_id=<?php echo $row['id'] ?>"> <button type="button"
                      class="btn btn-secondary btn-sm">Edit</button></a></td>
                <!-- <td><a href="inquiry.php?d_id=<?php echo $row['id'] ?>">
                    <button type="button" class="btn btn-dark btn-sm">Delete</button>
                  </a>
                </td> -->
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?php require_once './include/footer-staff.php'; ?>