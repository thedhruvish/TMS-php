<?php
$pageTitle = "Add / View Customer";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

/* ---------- MODE DETECTION ---------- */
$viewMode = false;
$editMode = false;
$viewData  = null;
$editData  = null;

/* READ-ONLY ?id=xx */
if (isset($_GET['id'])) {
  $viewMode = true;
  $res = $DB->read("customer", ['where' => ['id' => ['=' => (int)$_GET['id']]]]);
  $viewData = mysqli_fetch_assoc($res);
}

if (isset($_GET['u_id'])) {
  $editMode = true;
  $res = $DB->read("customer", ['where' => ['id' => ['=' => (int)$_GET['u_id']]]]);
  $editData = mysqli_fetch_assoc($res);
}

/* ---------- FORM PROCESSING (only in edit mode) ---------- */
if (isset($_POST['submit'])) {
  $fields = [
    'first_name',
    'last_name',
    'email',
    'phone',
    'dob',
    'gender',
    'address',
    'city',
    'state',
    'zip',
    'country',
    'reference_name',
    'notes',
    'profile_image'
  ];

  $data = [];
  foreach ($fields as $f) {
    $data[$f] = $_POST[$f] ?? '';
  }

  /* IMAGE UPLOAD */
  if (!empty($_FILES['profile_image']['name'])) {
    $filename = time() . '_' . basename($_FILES['profile_image']['name']);
    $targetDir  = '../images/profile/';
    $targetFile = $targetDir . $filename;
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
      $data['profile_image'] = $filename;
    }
  } else {
    /* keep existing image if nothing uploaded */
    $data['profile_image'] = $editData['profile_image'] ?? '';
  }
  if (isset($_GET['u_id'])) {
    $DB->update("customer", array_keys($data), array_values($data), 'id', (int)$_GET['u_id']);
  } else {
    $DB->create("customer", array_keys($data), array_values($data));
  }
  header("Location: customer.php");
  exit;
}

/* ---------- SHARED DATA ---------- */
$row = $viewMode ? $viewData : $editData;
?>

<!--  STYLES  -->
<link href="../layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
<link href="../layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../src/plugins/src/tagify/tagify.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/light/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/light/tagify/custom-tagify.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/tagify/custom-tagify.css">
<link rel="stylesheet" href="../src/assets/css/light/apps/blog-create.css">
<link rel="stylesheet" href="../src/assets/css/dark/apps/blog-create.css">
<!--  END STYLES  -->

<div class="container my-5">
  <?php if ($viewMode): ?>
    <h3 class="mb-4">Customer Details (Read-Only)</h3>
  <?php elseif ($editMode): ?>
    <h3 class="mb-4">Edit Customer</h3>
  <?php else: ?>
    <h3 class="mb-4">Add New Customer</h3>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="row g-4">

      <!-- MAIN INFO -->
      <div class="col-xxl-9">
        <div class="p-4 border rounded bg-light">
          <h5 class="mb-3">Personal Information</h5>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">First Name</label>
              <input name="first_name" type="text" class="form-control"
                value="<?= htmlspecialchars($row['first_name'] ?? '') ?>"
                <?= $viewMode ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Name</label>
              <input name="last_name" type="text" class="form-control"
                value="<?= htmlspecialchars($row['last_name'] ?? '') ?>"
                <?= $viewMode ? 'readonly' : '' ?>>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control"
                value="<?= htmlspecialchars($row['email'] ?? '') ?>"
                <?= $viewMode ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input name="phone" type="tel" class="form-control"
                value="<?= htmlspecialchars($row['phone'] ?? '') ?>"
                <?= $viewMode ? 'readonly' : '' ?>>
            </div>

            <div class="col-md-6">
              <label class="form-label">Date of Birth</label>
              <input name="dob" type="date" class="form-control"
                value="<?= htmlspecialchars($row['dob'] ?? '') ?>"
                <?= $viewMode ? 'readonly' : '' ?>>
            </div>

            <div class="col-md-6">
              <label class="form-label">Gender</label>
              <select name="gender" class="form-select" <?= $viewMode ? 'disabled' : '' ?>>
                <option disabled <?= !isset($row['gender']) ? 'selected' : '' ?>>Choose gender</option>
                <option value="Male" <?= (isset($row['gender']) && $row['gender'] == 'Male')   ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= (isset($row['gender']) && $row['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= (isset($row['gender']) && $row['gender'] == 'Other')  ? 'selected' : '' ?>>Other</option>
              </select>
            </div>
          </div>
        </div>

        <!-- ADDRESS -->
        <div class="p-4 border rounded bg-light mt-4">
          <h5 class="mb-3">Address Information</h5>
          <div class="mb-3">
            <label class="form-label">Street Address</label>
            <textarea name="address" class="form-control" rows="2"
              <?= $viewMode ? 'readonly' : '' ?>><?= htmlspecialchars($row['address'] ?? '') ?></textarea>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">City</label>
              <input name="city" type="text" class="form-control"
                value="<?= htmlspecialchars($row['city'] ?? '') ?>"
                <?= $viewMode ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
              <label class="form-label">State</label>
              <input name="state" type="text" class="form-control"
                value="<?= htmlspecialchars($row['state'] ?? '') ?>"
                <?= $viewMode ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
              <label class="form-label">Zip Code</label>
              <input name="zip" type="text" class="form-control"
                value="<?= htmlspecialchars($row['zip'] ?? '') ?>"
                <?= $viewMode ? 'readonly' : '' ?>>
            </div>

            <div class="col-md-6">
              <label class="form-label">Country</label>
              <select name="country" class="form-select" <?= $viewMode ? 'disabled' : '' ?>>
                <option disabled <?= !isset($row['country']) ? 'selected' : '' ?>>Select country</option>
                <option value="India" <?= (isset($row['country']) && $row['country'] == 'India')      ? 'selected' : '' ?>>India</option>
                <option value="USA" <?= (isset($row['country']) && $row['country'] == 'USA')        ? 'selected' : '' ?>>USA</option>
                <option value="UK" <?= (isset($row['country']) && $row['country'] == 'UK')         ? 'selected' : '' ?>>UK</option>
                <option value="Australia" <?= (isset($row['country']) && $row['country'] == 'Australia')  ? 'selected' : '' ?>>Australia</option>
                <option value="Other" <?= (isset($row['country']) && $row['country'] == 'Other')      ? 'selected' : '' ?>>Other</option>
              </select>
            </div>
          </div>
        </div>

        <!-- OTHER DETAILS -->
        <div class="p-4 border rounded bg-light mt-4">
          <h5 class="mb-3">Other Details</h5>

          <div class="mb-3">
            <label class="form-label">Reference Name</label>
            <input name="reference_name" type="text" class="form-control"
              value="<?= htmlspecialchars($row['reference_name'] ?? '') ?>"
              <?= $viewMode ? 'readonly' : '' ?>>
          </div>

          <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"
              <?= $viewMode ? 'readonly' : '' ?>><?= htmlspecialchars($row['notes'] ?? '') ?></textarea>
          </div>

          <?php if (!$viewMode): ?>
            <div class="mb-3">
              <label class="form-label">Profile Image</label>
              <input name="profile_image" type="file" class="form-control">
              <?php if (!empty($row['profile_image'])): ?>
                <div class="mt-2">
                  <img src="../images/profile/<?= htmlspecialchars($row['profile_image']) ?>" width="120" class="img-thumbnail">
                  <input type="hidden" name="profile_image_old" value="<?= htmlspecialchars($row['profile_image']) ?>">
                </div>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <?php if (!empty($row['profile_image'])): ?>
              <div class="mb-3">
                <label class="form-label">Profile Image</label><br>
                <img src="../images/profile/<?= htmlspecialchars($row['profile_image']) ?>" width="150" class="img-thumbnail">
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- BUTTONS -->
      <div class="col-xxl-3">
        <div class="p-4 border rounded bg-light h-100 d-flex flex-column justify-content-between">
          <div>
            <?php if ($viewMode): ?>
              <a href="customer.php" class="btn btn-primary w-100">Back to List</a>
            <?php else: ?>
              <button type="submit" name="submit" class="btn btn-success w-100">Submit</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- SCRIPTS -->
<script src="../src/plugins/src/editors/quill/quill.js"></script>
<script src="../src/plugins/src/filepond/filepond.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
<script src="../src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>
<script src="../src/plugins/src/tagify/tagify.min.js"></script>
<script src="../src/assets/js/apps/blog-create.js"></script>

<?php include_once './include/footer-admin.php'; ?>