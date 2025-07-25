<?php 
ob_start();
session_start();
$pageTitle = "User Create/Update";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';
require_once '../Database.php';

$DB = new Database();
$userData = null;
$isEditMode = false;

if (isset($_GET['u_id'])) {
    $u_id = $_GET['u_id'];
    $result = $DB->read("users", [
        "where" => [
            "id" => ["=" => $u_id]
        ]
    ]);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        $isEditMode = true;
    }
}

// Form Submit
if (isset($_POST['submit-user'])) {
    $profile_picture = $userData['profile_picture'] ?? NULL;

    // Upload new file if any
    if (!empty($_FILES['profile_picture']['name'])) {
        $filename = time() . '_' . $_FILES['profile_picture']['name'];
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], '../images/' . $filename);
        $profile_picture = $filename;
    }

    $columns = ['is_verified', 'name', 'email', 'password', 'mobile_no', 'role', 'profile_picture', 'auth_provider', 'two_step_auth', 'created_by'];
    $values = [1, $_POST['name'], $_POST['email'], $_POST['password'], $_POST['mobile_no'], $_POST['role'], $profile_picture, $_POST['auth_provider'], $_POST['two_step_auth'], $_SESSION['user_id']];

    if ($isEditMode) {
        $updateColumns = ['name', 'email', 'password', 'mobile_no', 'role', 'profile_picture', 'auth_provider', 'two_step_auth'];
        $updateValues = [$_POST['name'], $_POST['email'], $_POST['password'], $_POST['mobile_no'], $_POST['role'], $profile_picture, $_POST['auth_provider'], $_POST['two_step_auth']];

        $result = $DB->update('users', $updateColumns, $updateValues, 'id', $u_id);

        if ($result) {
            echo "<div class='alert alert-success m-4'>User updated successfully.</div>";
            header("Location: user.php");
            exit;
        } else {
            echo "<div class='alert alert-danger m-4'>Error occurred while updating user.</div>";
        }
    } else {
        // Check if email exists
        $result = $DB->read("users", [
            "where" => [
                "email" => ["=" => $_POST['email']],
            ],
        ]);
        if (mysqli_num_rows($result) === 0) {
            $result = $DB->create('users', $columns, $values);

            if ($result) {
                echo "<div class='alert alert-success m-4'>User created successfully.</div>";
                header("Location: user.php");
                exit;
            } else {
                echo "<div class='alert alert-danger m-4'>Error occurred while creating user.</div>";
            }
        } else {
            echo "<div class='alert alert-danger m-4'>User already exists.</div>";
        }
    }
}
?>


<div class="account-settings-container layout-top-spacing">
    <div class="account-content">
        <div class="row mb-3">
            <div class="col-md-12">
                <h2>Create User</h2>
            </div>
        </div>

        <div class="tab-content" id="animateLineContent-4">
            <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                        <form class="section general-info" method="POST" action="" enctype="multipart/form-data">
                            <div class="info">
                                <h6 class="">User Information</h6>
                                <div class="row">
                                    <div class="col-lg-11 mx-auto">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Full Name</label>
                                                    <input value="<?php echo $_POST['name']??$userData['name']??''?>" type="text" class="form-control mb-3" name="name" id="name" placeholder="Full Name" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input value="<?php echo $_POST['email'] ??$userData['email']?? '' ?>" type="email" class="form-control mb-3" name="email" id="email" placeholder="Email" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input value="<?php echo $_POST['password'] ?? $userData['password']??'' ?>" type="password" class="form-control mb-3" name="password" id="password" placeholder="Password" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobile_no">Mobile No</label>
                                                    <input value="<?php echo $_POST['mobile_no'] ??$userData['mobile_no']?? '' ?>" type="text" class="form-control mb-3" name="mobile_no" id="mobile_no" placeholder="Mobile Number" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="role">Role</label>
                                                    <select name="role" id="role" class="form-select mb-3" required>
                                                        <option value="">Select Role</option>
                                                        <option value="admin" <?= (($_POST['role'] ?? $userData['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                                                        <option value="staff" <?= (($_POST['role'] ?? $userData['role'] ?? '') === 'staff') ? 'selected' : '' ?>>Staff</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="profile_picture">Profile Picture</label>
                                                    <input type="file" class="form-control mb-3" name="profile_picture" id="profile_picture">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="auth_provider">Auth Provider</label>
                                                    <select name="auth_provider" id="auth_provider" class="form-select mb-3" required>
                                                        <option value="local" <?= (($_POST['auth_provider'] ?? $userData['auth_provider'] ?? '') === 'local') ? 'selected' : '' ?>>Local</option>
                                                        <option value="google" <?= (($_POST['auth_provider'] ?? $userData['auth_provider'] ?? '') === 'google') ? 'selected' : '' ?>>Google</option>
                                                </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="two_step_auth">Two Step Auth</label>
                                                    <select name="two_step_auth" id="two_step_auth" class="form-select mb-3">
                                                        <option value="0" <?= (($_POST['two_step_auth'] ?? $userData['two_step_auth'] ?? '') == '0') ? 'selected' : '' ?>>Disabled</option>
                                                        <option value="1" <?= (($_POST['two_step_auth'] ?? $userData['two_step_auth'] ?? '') == '1') ? 'selected' : '' ?>>Enabled</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <div class="form-group text-end">
                                                    <button type="submit" name="submit-user" class="btn btn-primary">Create User</button>
                                                </div>
                                            </div>

                                        </div> <!-- row end -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('./include/footer-admin.php'); ?>