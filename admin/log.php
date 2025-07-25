<?php $pageTitle = "Log";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$reult = $DB->read("user_log");

?>


<div class="row">
    <div class="seperator-header layout-top-spacing">
        <h4 class="">LOG</h4>
    </div>
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>user_id</th>
                            <th>Email</th>
                            <th>Is success</th>
                            <th>login time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($reult)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['is_success']; ?></td>
                                <td><?php echo $row['login_time']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<?php require_once './include/footer-admin.php'; ?>