<?php $pageTitle = "Customer";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$customer = $DB->read("customer");

if (isset($_GET['d_id'])) {
  $DB->delete("customer", "id", $_GET['d_id']);
  header("Location: customer.php");
  exit();
}

?>

<div class="row">
  <div class="seperator-header layout-top-spacing">
    <h4 class="">CUSTOMER </h4>
    <a href="customer-add.php" class="btn btn-primary">Add New Customer</a>
  </div>
  <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
    <div class="statbox widget box box-shadow">
      <div class="widget-content widget-content-area">
        <table id="html5-extension" class="table dt-table-hover" style="width:100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Total Amount</th>
              <th>DOB</th>
              <th>Country</th>
              <th>Avatar</th>
              <th>Update</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($customer)) { ?>
              <tr>
                <td>
                  <!-- <a href="customer-add.php?id=<?php echo $row['id'] ?>" class="btn btn-sm"><?php echo $row['id'] ?></a> -->
                  <?php echo $row['id'] ?>
                </td>
                <!-- <td><?php echo $row['first_name'] ?></td>
                <td><?php echo $row['last_name'] ?></td> -->
                <td>
                  <a href="customer-transactions.php?id=<?php echo $row['id']; ?>" title="View transaction history">
                   <?php echo $row['first_name'] ?>
                  </a>
                </td>
                <td>
                    <a href="customer-transactions.php?id=<?php echo $row['id']; ?>" title="View transaction history">
                        <?php echo $row['last_name'] ?>
                    </a>
                </td>
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['phone'] ?></td>
                <td><?php echo $row['total_amount'] ?></td>
                <td><?php echo $row['dob'] ?></td>
                <td><?php echo $row['country'] ?></td>
                <td>
                  <div class="d-flex">
                    <div class="usr-img-frame mr-2 rounded-circle">
                      <img alt="avatar" class="img-fluid rounded-circle" src="<?php echo ($row['profile_image'] == null || $row['profile_image'] == '')
                        ? '../images/profile/avatar.png'
                        : '../images/profile/' . $row['profile_image']; ?>">
                    </div>
                  </div>
                </td>
                <td>
                  <a class="btn btn-primary" href="customer-add.php?u_id=<?php echo $row['id'] ?>">Update</a>
                </td>
                <td>
                  <a class="btn btn-danger" href="customer.php?d_id=<?php echo $row['id'] ?>">Delete</a>
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