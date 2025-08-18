<?php
$pageTitle = "Attendant";
require_once './include/header-staff.php';
require_once './include/sidebar-staff.php';
$currentYear = date('Y');
$currentMonth = date('m');
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
        <div class="col-md-3">
            <select class="form-select form-select-sm mb-3" id="monthSelect">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo $m ?>" <?php echo $m == $currentMonth ? 'selected' : '' ?>>
                        <?php echo date('F', mktime(0, 0, 0, $m, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="col-md-3">
            <select class="form-select form-select-sm mb-3" id="yearSelect">
                <?php for ($y = $currentYear - 1; $y <= $currentYear + 1; $y++): ?>
                    <option value="<?php echo $y ?>" <?php echo $y == $currentYear ? 'selected' : '' ?>>
                        <?php echo $y ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
</div>

<!--  END DROPDOWNS -->

<style>
    .table-responsive {
        overflow-x: auto;
    }

    .fixed-first-column th:first-child,
    .fixed-first-column td:first-child {
        position: sticky;
        left: 0;
        z-index: 1;
        background-color: #fff;
    }

    .fixed-first-column td:first-child .d-flex {
        min-width: 200px;
    }
</style>

<div class="row layout-top-spacing">
    <div class="table-responsive">
        <table id="zero-config" class="table table-striped dt-table-hover fixed-first-column" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <!-- JS will inject date columns -->
                </tr>
            </thead>
            <tbody>
                <?php for ($j = 1; $j <= 10; $j++) { ?>
                    <tr>
                        <td>
                            <div class="d-flex">
                                <div class="usr-img-frame me-2 rounded-circle">
                                    <img alt="avatar" class="img-fluid rounded-circle" src="../src/assets/img/boy.png">
                                </div>
                                <p class="align-self-center mb-0 admin-name">Tiger <?php echo $j ?></p>
                            </div>
                        </td>
                        <td>admin<?php echo $j ?>@gmail.com</td>
                        <!-- JS will inject attendance columns -->
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <!-- JS will inject date columns -->
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php require_once './include/footer-staff.php'; ?>

<!-- JavaScript for dynamic columns -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const monthSelect = document.getElementById('monthSelect');
        const yearSelect = document.getElementById('yearSelect');

        function renderTableDates() {
            const month = parseInt(monthSelect.value);
            const year = parseInt(yearSelect.value);
            const daysInMonth = new Date(year, month, 0).getDate();
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const headerRow = document.querySelector("#zero-config thead tr");
            const footerRow = document.querySelector("#zero-config tfoot tr");
            headerRow.innerHTML = "<th>Name</th><th>Email</th>";
            footerRow.innerHTML = "<th>Name</th><th>Email</th>";

            for (let i = 1; i <= daysInMonth; i++) {
                const dateStr = `${i}/${String(month).padStart(2, '0')}/${year}`;
                headerRow.innerHTML += `<th>${dateStr}</th>`;
                footerRow.innerHTML += `<th>${dateStr}</th>`;
            }

            const rows = document.querySelectorAll("#zero-config tbody tr");
            rows.forEach(row => {
                const nameCell = row.children[0].outerHTML;
                const emailCell = row.children[1].outerHTML;
                let cells = '';

                for (let i = 1; i <= daysInMonth; i++) {
                    const currentDate = new Date(year, month - 1, i);
                    currentDate.setHours(0, 0, 0, 0);
                    const isToday = currentDate.getTime() === today.getTime();
                    const disabled = isToday ? "" : "disabled";

                    cells += `
                <td>
                    <div class="switch form-switch-custom switch-inline form-switch-primary">
                        <input class="switch-input" type="checkbox" role="switch" ${disabled}>
                    </div>
                </td>`;
                }

                row.innerHTML = nameCell + emailCell + cells;
            });
        }


        monthSelect.addEventListener('change', renderTableDates);
        yearSelect.addEventListener('change', renderTableDates);

        renderTableDates(); 
    });
</script>