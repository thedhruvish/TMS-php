<?php
$pageTitle = "User";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Check if a search term is provided
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Conditionally read from the database
if (!empty($search_query)) {
    // If there's a search term, filter users by email
    $users = $DB->read("users", ['where' => ['email' => ['LIKE' => "%" . $search_query . "%"]]]);
} else {
    // Otherwise, read all users
    $users = $DB->read("users");
}

if (isset($_GET['d_id'])) {
    $DB->delete("users", "id", $_GET['d_id']);
    header("Location: user.php");
    exit();
}
?>

<div class="row layout-spacing layout-top-spacing" id="cancel-row">
    <div class="col-lg-12">
        <div class="widget-content searchable-container list">

            <div class="row mb-4">
                <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search layout-spacing align-self-center">
                    <form class="form-inline my-2 my-lg-0" method="GET" action="">
                        <div class="input-group">

                            <input type="text" class="form-control product-search" name="search" id="input-search"
                                placeholder="Search by email..." value="<?php echo $search_query; ?>">
                        </div>
                    </form>
                </div>

                <div
                    class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-sm-right text-center layout-spacing align-self-center">
                    <div class="d-flex justify-content-sm-end justify-content-center">
                        <a href="user-add.php" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-user-plus">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                            Add User
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Email</th>
                            <th>Login Type</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Verified</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($users && mysqli_num_rows($users) > 0) {
                            while ($row = mysqli_fetch_assoc($users)) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php
                                            if ($row['auth_provider'] == "google") {
                                                echo $row['profile_picture'];
                                            } else {
                                                echo ($row['profile_picture'] == null) ? '../images/profile/avatar.png' : '../images/profile/' . $row['profile_picture'];
                                            } ?>" alt="avatar" class="rounded-circle me-3" width="40" height="40"
                                                style="object-fit: cover;">
                                            <div>
                                                <div class="fw-bold"><?php echo $row['name']; ?></div>
                                                <small class="text-muted"><?php echo $row['username'] ?? ''; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle"><?php echo $row['email']; ?></td>
                                    <td class="align-middle">
                                        <span class="badge bg-info"><?php echo ucfirst($row['auth_provider']); ?></span>
                                    </td>
                                    <td class="align-middle"><?php echo $row['mobile_no'] ?? 'N/A'; ?></td>
                                    <td class="align-middle">
                                        <span class="badge bg-<?php echo $row['role'] == 'admin' ? 'danger' : 'secondary'; ?>">
                                            <?php echo ucfirst($row['role']); ?>
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-<?php echo $row['is_verified'] ? 'success' : 'warning'; ?>">
                                            <?php echo $row['is_verified'] ? 'Yes' : 'No'; ?>
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group">
                                            <a href="user-add.php?u_id=<?php echo $row['id']; ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-edit-2">
                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                </svg>
                                            </a>
                                            <a href="user.php?d_id=<?php echo $row['id']; ?>"
                                                onclick="return confirm('Are you sure you want to delete this user?')"
                                                class="btn btn-sm btn-outline-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-trash-2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center py-4"><p class="text-muted mb-0">No users found.</p></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php require_once './include/footer-admin.php'; ?>