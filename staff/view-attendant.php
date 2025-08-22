<?php
$pageTitle = "Attendance – View Only";
require_once './include/header-staff.php';
require_once './include/sidebar-staff.php';

$currentYear  = date('Y');
$currentMonth = date('m');

$selectedYear  = isset($_GET['year'])  ? (int)$_GET['year']  : $currentYear;
$selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : $currentMonth;
$daysInMonth   = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

// get userId if passed
$selectedUserId = $_SESSION['user_id'];

/* ---- users ---- */
$users = [];
$userWhere = [];

if ($selectedUserId) {
  // fetch only that user
  $userWhere['id'] = ["=" => $selectedUserId];
}

$res = $DB->read("users", [
  'where'    => $userWhere,
  'order_by' => 'name ASC'
]);

while ($row = mysqli_fetch_assoc($res)) {
  $users[] = $row;
}

/* ---- attendance ---- */
$attendanceWhere = [
  "YEAR(att_date)"  => ["=" => $selectedYear],
  "MONTH(att_date)" => ["=" => $selectedMonth],

];

if ($selectedUserId) {
  $attendanceWhere["user_id"] = ["=" => $selectedUserId];
}

$res = $DB->read("attendance", ["where" => $attendanceWhere]);

$attendance = [];
while ($row = mysqli_fetch_assoc($res)) {
  $attendance[$row['user_id']][$row['att_date']] = $row['status'];
}
?>


<!-- BREADCRUMB -->
<div class="page-meta">
  <nav class="breadcrumb-style-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Attendance</a></li>
      <li class="breadcrumb-item active">View</li>
    </ol>
  </nav>
</div>

<!-- month / year chooser -->
<div class="row mb-3 align-items-center">
  <div class="col d-flex flex-wrap align-items-center gap-2">
    <!-- left filters -->
    <div class="d-flex gap-2">
      <select class="form-select form-select-sm" id="monthSelect" style="width:120px;">
        <?php for ($m = 1; $m <= 12; $m++) { ?>
          <option value="<?php echo $m ?>" <?php echo $m == $selectedMonth ? 'selected' : '' ?>>
            <?php echo date('F', mktime(0, 0, 0, $m, 1)) ?>
          </option>
        <?php } ?>
      </select>

      <select class="form-select form-select-sm" id="yearSelect" style="width:100px;">
        <?php for ($y = $currentYear - 1; $y <= $currentYear + 1; $y++) { ?>
          <option value="<?php echo $y ?>" <?php echo $y == $selectedYear ? 'selected' : '' ?>><?php echo $y ?></option>
        <?php } ?>
      </select>

      <button class="btn btn-primary btn-sm px-3" id="btnView">Filter</button>
    </div>

  </div>
</div>

<!-- READ-ONLY CALENDAR -->
<div class="row layout-top-spacing">
  <div class="table-responsive">
    <table class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th>Name</th>
          <?php for ($d = 1; $d <= $daysInMonth; $d++) { ?>
            <th><?php echo $d ?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u) { ?>
          <tr>
            <td><?php echo $u['name'] ?></td>
            <?php
            for ($d = 1; $d <= $daysInMonth; $d++) {
              $date   = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $d);
              $status = $attendance[$u['id']][$date] ?? 'A';
              $class  = ($date > date('Y-m-d')) ? 'text-bg-dark' : ($status == 'P' ? 'bg-success' : 'bg-secondary');
            ?>
              <td class="<?php echo $class ?> fw-bold"><?php echo $status ?></td>
            <?php } ?>
          </tr>
        <?php }; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  /* simple redirect when dropdowns change */
  document.getElementById('btnView').addEventListener('click', () => {
    const m = document.getElementById('monthSelect').value;
    const y = document.getElementById('yearSelect').value;
    location.search = `month=${m}&year=${y}`;
  });
</script>

<?php require_once './include/footer-staff.php'; ?>