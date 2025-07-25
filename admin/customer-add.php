<?php

$pageTitle = "add customer";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$editData = null;
if (isset($_GET['u_id'])) {
  $res = $DB->read("customer", ['where' => ['id' => ['=' => $_GET['u_id']]]]);
  $editData = mysqli_fetch_assoc($res);
}

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
  foreach ($fields as $field) {
    $data[$field] = $_POST[$field] ?? '';
  }

  if (!empty($_FILES['profile_image']['name'])) {
    $filename = time() . '_' . basename($_FILES['profile_image']['name']);
    $targetDirectory = '../images/profile';
    $targetFile = $targetDirectory . $filename;

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
      $data['profile_image'] = $filename;
    }
  } else if ($editData != null) {
    $data['profile_image'] = $editData['profile_image'];
  }

  if (isset($_GET['u_id'])) {
    $DB->update("customer", array_keys($data), array_values($data), 'id', $_GET['u_id']);
    header("Location: customer.php");
  } else {
    // Insert new
    $DB->create("customer", array_keys($data), array_values($data));
    header("Location: customer.php");
  }
}

?>


<link href="../layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
<link href="../layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />


<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link rel="stylesheet" type="text/css" href="../src/plugins/src/tagify/tagify.css">


<link rel="stylesheet" type="text/css" href="../src/plugins/css/light/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/light/tagify/custom-tagify.css">

<link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/tagify/custom-tagify.css">
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

<!--  BEGIN CUSTOM STYLE FILE  -->
<link rel="stylesheet" href="../src/assets/css/light/apps/blog-create.css">
<link rel="stylesheet" href="../src/assets/css/dark/apps/blog-create.css">
<!--  END CUSTOM STYLE FILE  -->


<div class="container my-5">
  <form method="post" action="" enctype="multipart/form-data">
    <div class="row g-4">

      <!-- Main Section -->
      <div class="col-xxl-9">
        <div class="p-4 border rounded bg-light">
          <h5 class="mb-3">Personal Information</h5>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="first-name" class="form-label">First Name</label>
              <input name="first_name" value="<?= $editData['first_name'] ?? '' ?>" type="text" class="form-control" id="first-name" placeholder="e.g., John">
            </div>
            <div class="col-md-6">
              <label for="last-name" class="form-label">Last Name</label>
              <input name="last_name" value="<?= $editData['last_name'] ?? '' ?>" type="text" class="form-control" id="last-name" placeholder="e.g., Doe">
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input name="email" value="<?= $editData['email'] ?? '' ?>" type="email" class="form-control" id="email" placeholder="e.g., john@example.com">
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label">Phone</label>
              <input name="phone" value="<?= $editData['phone'] ?? '' ?>" type="tel" class="form-control" id="phone" placeholder="e.g., +91 9876543210">
            </div>

            <div class="col-md-6">
              <label for="dob" class="form-label">Date of Birth</label>
              <input name="dob" value="<?= $editData['dob'] ?? '' ?>" type="date" class="form-control" id="dob">
            </div>

            <div class="col-md-6">
              <label for="gender" class="form-label">Gender</label>
              <select class="form-select" id="gender" name="gender">
                <option disabled <?= !isset($editData['gender']) ? 'selected' : '' ?>>Choose gender</option>
                <option <?= (isset($editData['gender']) && $editData['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                <option <?= (isset($editData['gender']) && $editData['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                <option <?= (isset($editData['gender']) && $editData['gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Address Section -->
        <div class="p-4 border rounded bg-light mt-4">
          <h5 class="mb-3">Address Information</h5>

          <div class="mb-3">
            <label for="address" class="form-label">Street Address</label>
            <textarea name="address" class="form-control" id="address" rows="2"><?= $editData['address'] ?? '' ?></textarea>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label for="city" class="form-label">City</label>
              <input name="city" value="<?= $editData['city'] ?? '' ?>" type="text" class="form-control" id="city">
            </div>
            <div class="col-md-4">
              <label for="state" class="form-label">State</label>
              <input name="state" value="<?= $editData['state'] ?? '' ?>" type="text" class="form-control" id="state">
            </div>
            <div class="col-md-4">
              <label for="zip" class="form-label">Zip Code</label>
              <input name="zip" value="<?= $editData['zip'] ?? '' ?>" type="text" class="form-control" id="zip">
            </div>
            <div class="col-md-6">
              <label for="country" class="form-label">Country</label>
              <select class="form-select" id="country" name="country">
                <option disabled <?= !isset($editData['country']) ? 'selected' : '' ?>>Select country</option>
                <option <?= (isset($editData['country']) && $editData['country'] == 'India') ? 'selected' : '' ?>>India</option>
                <option <?= (isset($editData['country']) && $editData['country'] == 'USA') ? 'selected' : '' ?>>USA</option>
                <option <?= (isset($editData['country']) && $editData['country'] == 'UK') ? 'selected' : '' ?>>UK</option>
                <option <?= (isset($editData['country']) && $editData['country'] == 'Australia') ? 'selected' : '' ?>>Australia</option>
                <option <?= (isset($editData['country']) && $editData['country'] == 'Other') ? 'selected' : '' ?>>Other</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Other Details -->
        <div class="p-4 border rounded bg-light mt-4">
          <h5 class="mb-3">Other Details</h5>

          <div class="mb-3">
            <label for="reference" class="form-label">Reference Name</label>
            <input name="reference_name" value="<?= $editData['reference_name'] ?? '' ?>" type="text" class="form-control" id="reference" placeholder="e.g., referred by someone?">
          </div>

          <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control" id="notes" rows="3" placeholder="Any special notes or instructions"><?= $editData['notes'] ?? '' ?></textarea>
          </div>

          <div class="mb-3">
            <label for="profile-image" class="form-label">Profile Image</label>
            <input name="profile_image" type="file" value="<?= $editData['profile_image'] ?? '' ?>" class="form-control" type="text" id="profile-image" placeholder="image name or path">
          </div>

        </div>
      </div>

      <!-- Button Section -->
      <div class="col-xxl-3">
        <div class="p-4 border rounded bg-light h-100 d-flex flex-column justify-content-between">
          <div class="mb-3"></div>
          <button type="submit" name="submit" class="btn btn-success w-100">Submit</button>
        </div>
      </div>

    </div>
  </form>
</div>



<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../src/plugins/src/editors/quill/quill.js"></script>
<script src="../src/plugins/src/filepond/filepond.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
<script src="../src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>

<script src="../src/plugins/src/tagify/tagify.min.js"></script>


<!-- END PAGE LEVEL SCRIPTS -->

<script src="../src/assets/js/apps/blog-create.js"></script>


<?php include_once './include/footer-admin.php'; ?>