<?php $pageTitle = "category create";
require_once './include/header-staff.php';
require_once './include/sidebar-staff.php';

$editData = null;
if (isset($_GET['u_id'])) {
  $res = $DB->read("inquiry", ['where' => ['id' => ['=' => $_GET['u_id']]]]);
  $editData = mysqli_fetch_assoc($res);
}

if (isset($_POST['submit'])) {
  $columns = ['name', 'email', 'phone', 'message', 'status'];
  $values = [$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['message'], $_POST['status'] ?? 'new'];

  if (isset($_GET['u_id'])) {
    $result = $DB->update("inquiry", $columns, $values, 'id', $_GET['u_id']);
    if ($result) {
      header("Location:inquiry.php");
    } else {
      echo "<div class='alert alert-danger'>Failed to update inquiry.</div>";
    }
  } else {
    $result = $DB->create('inquiry', $columns, $values);
    header("Location: customer.php");
    if ($result) {
      header("Location:inquiry.php");
    } else {
      echo "<div class='alert alert-danger'>Failed to submit inquiry.</div>";
    }
  }
}

?>

<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><?php echo isset($_GET['u_id']) ? 'Edit' : 'Add' ?> Inquiry</h5>
    </div>

    <div class="card-body">
      <form method="POST" action="">

        <!-- Row 1 -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name"
              value="<?php echo $editData['name'] ?? ''; ?>" required>
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
              value="<?php echo $editData['email'] ?? ''; ?>" required>
          </div>
        </div>

        <!-- Row 2 -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone"
              value="<?php echo $editData['phone'] ?? ''; ?>">
          </div>

          <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
              <?php
              $opts = ['new', 'in_progress', 'resolved', 'closed'];
              foreach ($opts as $opt) {
                $sel = ($editData['status'] ?? 'new') === $opt ? 'selected' : '';
                echo "<option $sel value=\"$opt\">" . ucwords(str_replace('_', ' ', $opt)) . "</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <!-- Row 3 -->
        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" name="message" rows="4"
            placeholder="Enter your message"><?php echo
                                              $editData['message'] ?? '';
                                              ?></textarea>
        </div>

        <button type="submit" name="submit" class="btn btn-success w-100">
          Submit
        </button>
      </form>
    </div>
  </div>
</div>




<?php include_once './include/footer-staff.php'; ?>