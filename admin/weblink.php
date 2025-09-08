<?php
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

if (isset($_GET['d_id'])) {
    $DB->delete("weblink", "id", $_GET['d_id']);
    header("Location: weblink.php");
}
// get all the weblink and join query to show the username
$result_web_link = mysqli_query(
    $DB->conn,
    "SELECT w.*, u.name AS creator_name
     FROM weblink w
     JOIN users u ON w.createby = u.id
     ORDER BY w.id DESC"
);

?>

<div class="row">
    <div class="seperator-header layout-top-spacing">
        <h4 class="">WEBLINK</h4>
    </div>
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>CreateBy</th>
                            <th>CreateAt</th>
                            <th>Total Product</th>
                            <th>Open</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_web_link)) { ?>
                            <tr>
                                <td><?php echo $row['creator_name']; ?></td>
                                <td><?php echo $row['createat']; ?></td>
                                <td><?php echo sizeof(explode(',', $row['productIds'])); ?></td>
                                <td><a href="../weblink/index.php?id=<?php echo $row['id']; ?>" target="_blank"
                                        class="btn btn-primary">Open</a></td>
                                <td><a href="weblink.php?d_id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
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