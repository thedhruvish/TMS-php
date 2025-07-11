<?php $pageTitle = "add customer";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';
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
  <form>
    <div class="row g-4">

      <!-- Main Section -->
      <div class="col-xxl-9">
        <div class="p-4 border rounded bg-light">
          <h5 class="mb-3">Personal Information</h5>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="first-name" class="form-label">First Name</label>
              <input type="text" class="form-control" id="first-name" placeholder="e.g., John">
            </div>
            <div class="col-md-6">
              <label for="last-name" class="form-label">Last Name</label>
              <input type="text" class="form-control" id="last-name" placeholder="e.g., Doe">
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" placeholder="e.g., john@example.com">
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label">Phone</label>
              <input type="tel" class="form-control" id="phone" placeholder="e.g., +91 9876543210">
            </div>

            <div class="col-md-6">
              <label for="dob" class="form-label">Date of Birth</label>
              <input type="date" class="form-control" id="dob">
            </div>

            <div class="col-md-6">
              <label for="gender" class="form-label">Gender</label>
              <select class="form-select" id="gender">
                <option selected disabled>Choose gender</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Address Section -->
        <div class="p-4 border rounded bg-light mt-4">
          <h5 class="mb-3">Address Information</h5>

          <div class="mb-3">
            <label for="address" class="form-label">Street Address</label>
            <textarea class="form-control" id="address" rows="2" placeholder="House number, Street, Area..."></textarea>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label for="city" class="form-label">City</label>
              <input type="text" class="form-control" id="city">
            </div>
            <div class="col-md-4">
              <label for="state" class="form-label">State</label>
              <input type="text" class="form-control" id="state">
            </div>
            <div class="col-md-4">
              <label for="zip" class="form-label">Zip Code</label>
              <input type="text" class="form-control" id="zip">
            </div>
            <div class="col-md-6">
              <label for="country" class="form-label">Country</label>
              <select class="form-select" id="country">
                <option selected disabled>Select country</option>
                <option>India</option>
                <option>USA</option>
                <option>UK</option>
                <option>Australia</option>
                <option>Other</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Other Details -->
        <div class="p-4 border rounded bg-light mt-4">
          <h5 class="mb-3">Other Details</h5>

          <div class="mb-3">
            <label for="reference" class="form-label">Reference Name</label>
            <input type="text" class="form-control" id="reference" placeholder="e.g., referred by someone?">
          </div>

          <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" rows="3" placeholder="Any special notes or instructions"></textarea>
          </div>

          <div class="mb-3">
            <label for="profile-image" class="form-label">Profile Image</label>
            <input class="form-control" type="file" id="profile-image">
          </div>
        </div>
      </div>

      <!-- Button Section -->
      <div class="col-xxl-3">
        <div class="p-4 border rounded bg-light h-100 d-flex flex-column justify-content-between">
          <div class="mb-3">
            <!-- Optional extra content or preview -->
          </div>
          <button type="submit" class="btn btn-success w-100">Submit</button>
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