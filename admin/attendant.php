<?php
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';


$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
$selectedYear  = isset($_GET['year'])  ? $_GET['year']  : date('Y');
$today = date('Y-m-d');

$result = $DB->read("users");
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

$attendance = [];
$res = $DB->read("attendance", [
    "where" => [
        "YEAR(att_date)" => ["=" => $selectedYear],
        "MONTH(att_date)" => ["=" => $selectedMonth]
    ]
]);
while ($row = mysqli_fetch_assoc($res)) {
    $attendance[$row['user_id']][$row['att_date']] = $row['status'];
}


if (isset($_POST['save'])) {
    $today = date('Y-m-d');
    $att   = $_POST['att'] ?? [];

    foreach ($att as $userId => $status) {
        $status = ($status === 'P') ? 'P' : 'A';

        $result = $DB->read("attendance", ["where" => ["user_id" => ["=" => $userId], "att_date" => ["=" => $today]]]);
        if (mysqli_num_rows($result) > 0) {
            $id = mysqli_fetch_assoc($result)['id'];
            $DB->update("attendance", ["status"], [$status], "id", $id);
        } else {
            $DB->create("attendance", ['user_id', 'att_date', 'status'], [$userId, $today, $status]);
        }
    }
    header("Location: attendant.php");
    ob_flush();
    exit;
}

?>
<!-- BREADCRUMB -->
<div class="page-meta">
    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Datatables</a></li>
            <li class="breadcrumb-item active" aria-current="page">Striped</li>
        </ol>
    </nav>
</div>

<!--  BEGIN DROPDOWNS -->
<div class="row mb-3">
    <div class="col d-flex justify-content-end gap-2">
        <a href="./view-attendant.php">
            <button class="btn btn-primary px-4 py-2">View Attendant</button>
    </div>
    </a>
</div>
</div>


<?php if ($users && $selectedMonth == date('m') && $selectedYear == date('Y')): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-toggle-on me-2"></i> Mark Attendance – <?php echo date('d M Y'); ?>
        </div>

        <form method="post" class="m-0">
            <input type="hidden" name="date" value="<?php echo $today; ?>">

            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">User</th>
                            <th scope="col" class="text-center">Status (P / A)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($users as $u):
                            $st = $attendance[$u['id']][$today] ?? 'A';
                        ?>

                            <tr>
                                <td><?php echo htmlspecialchars($u['name']); ?></td>

                                <td class="text-center">
                                    <!-- hidden input = absent when switch is off -->
                                    <input type="hidden" name="att[<?php echo $u['id']; ?>]" value="A">

                                    <div class="switch form-switch-custom switch-inline form-switch-primary">
                                        <input
                                            class="switch-input"
                                            type="checkbox"
                                            role="switch"
                                            name="att[<?php echo $u['id']; ?>]"
                                            value="P"
                                            <?php echo $st === 'P' ? 'checked' : ''; ?>>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success" name="save">
                    <i class="bi bi-check-lg me-1"></i> Save Attendance
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>


<?php include('./include/footer-admin.php'); ?>
<!--  END DROPDOWNS -->