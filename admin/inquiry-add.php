<?php
$pageTitle = "Category Create";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$editData = null;
$viewMode = false;

// View mode for id=1
if (isset($_GET['id'])) {
  $res = $DB->read("inquiry", ['where' => ['id' => ['=' => $_GET['id']]]]);
  $editData = mysqli_fetch_assoc($res);
  $viewMode = true;
}
// Edit mode for u_id
elseif (isset($_GET['u_id'])) {
  $res = $DB->read("inquiry", ['where' => ['id' => ['=' => $_GET['u_id']]]]);
  $editData = mysqli_fetch_assoc($res);
}

// Handle form submission (only for add or edit, not view)
if (!$viewMode && isset($_POST['submit'])) {
  $columns = ['name', 'email', 'phone', 'message', 'status'];
  $status = isset($_POST['status']) ? '1' : '0';
  $values = [$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['message'], $status];

  if (isset($_GET['u_id'])) {
    // Update
    $result = $DB->update("inquiry", $columns, $values, 'id', $_GET['u_id']);
    if ($result) {
      header("Location:inquiry.php");
      exit;
    } else {
      echo "<div class='alert alert-danger'>Failed to update inquiry.</div>";
    }
  } else {
    // Create
    $result = $DB->create('inquiry', $columns, $values);
    if ($result) {
      header("Location:inquiry.php");
      exit;
    } else {
      echo "<div class='alert alert-danger'>Failed to submit inquiry.</div>";
    }
  }
}
?>

<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">
        <?php
        if ($viewMode)
          echo "View Inquiry";
        elseif (isset($_GET['u_id']))
          echo "Edit Inquiry";
        else
          echo "Add Inquiry";
        ?>
      </h5>
    </div>

    <div class="card-body">
      <form method="POST" action="">
        <!-- Row 1 -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Name *</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $editData['name'] ?? ''; ?>"
              <?php echo $viewMode ? 'disabled' : 'required'; ?>>
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">Email *</label>
            <input type="email" class="form-control" id="email" name="email"
              value="<?php echo $editData['email'] ?? ''; ?>" <?php echo $viewMode ? 'disabled' : 'required'; ?>>
          </div>
        </div>

        <!-- Row 2 -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="phone" class="form-label">Phone *</label>
            <input type="text" class="form-control" id="phone" name="phone"
              value="<?php echo $editData['phone'] ?? ''; ?>" <?php echo $viewMode ? 'disabled' : 'required'; ?>>
          </div>
          <div class="col-md-6">
            <label for="status" class="form-label">Status *</label>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="status" name="status" value="1" <?php
              // Checked if editData status is '1'
              if (($editData['status'] ?? '') === '1')
                echo 'checked';
              // Disable if view mode
              if ($viewMode)
                echo ' disabled';
              ?>>
              <label class="form-check-label" for="status">
                <?php echo (($editData['status'] ?? '') === '1') ? 'Active' : 'Inactive'; ?>
              </label>
            </div>
          </div>

        </div>

        <!-- Row 3 -->
        <div class="mb-3">
          <label for="message" class="form-label">Message *</label>
          <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter your message" <?php echo $viewMode ? 'disabled' : 'required'; ?>><?php echo $editData['message'] ?? ''; ?></textarea>
        </div>

        <?php if (!$viewMode): ?>
          <button type="submit" name="submit" class="btn btn-success w-100">Submit</button>
        <?php else: ?>
          <a href="inquiry.php" class="btn btn-secondary w-100">Back</a>
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>

<?php include_once './include/footer-admin.php'; ?>