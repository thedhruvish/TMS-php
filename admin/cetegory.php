<?php $pageTitle = "Category";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';
?>
<div class="row">
  <div class="seperator-header layout-top-spacing">
    <h4 class="">Category </h4>
  </div>
  <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
    <div class="statbox widget box box-shadow">
      <div class="widget-content widget-content-area">
        <table id="html5-extension" class="table dt-table-hover" style="width:100%">
          <thead>
            <tr>
              <th>Category</th>
              <th>Description</th>
              <th>Image</th>
              <th>Total Product</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Tiger Nixon</td>
              <td>System Architect1</td>
              <td>
                <div class="d-flex">
                  <div class="usr-img-frame mr-2 rounded-circle">
                    <img alt="avatar" class="img-fluid rounded-circle" src="../src/assets/img/boy.png">
                  </div>
                </div>
              </td>
              <td>System Architect</td>
              <td>Edinburgh</td>
              <td>5421</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<?php include('./include/footer-admin.php'); ?>