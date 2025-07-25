<?php
$pageTitle = "User Create / Update";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$userData = null;
$isEditMode = false;
$isViewMode = false;


if (isset($_GET['u_id'])) {
    $u_id = $_GET['u_id'];
    $result = $DB->read("users", ["where" => ["id" => ["=" => $u_id]]]);
    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        $isEditMode = true;
    }
} elseif (isset($_GET['id'])) {
    $u_id = $_GET['id'];
    $result = $DB->read("users", ["where" => ["id" => ["=" => $u_id]]]);
    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        $isViewMode = true;
    }
}

/* ---------- form processing ---------- */
if (isset($_POST['submit-user'])) {
    $profile_picture = $userData['profile_picture'] ?? null;

    if (!empty($_FILES['profile_picture']['name'])) {
        $filename = time() . '_' . $_FILES['profile_picture']['name'];
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], '../images/profile/' . $filename);
        $profile_picture = $filename;
    }

    $columns  = [
        'is_verified',
        'name',
        'email',
        'password',
        'mobile_no',
        'role',
        'profile_picture',
        'auth_provider',
        'two_step_auth',
        'created_by'
    ];
    $values   = [
        1,
        $_POST['name'],
        $_POST['email'],
        $_POST['password'],
        $_POST['mobile_no'],
        $_POST['role'],
        $profile_picture,
        $_POST['auth_provider'],
        $_POST['two_step_auth'],
        $_SESSION['user_id']
    ];

    if ($isEditMode) {
        $updateColumns = [
            'name',
            'email',
            'password',
            'mobile_no',
            'role',
            'profile_picture',
            'auth_provider',
            'two_step_auth',
            'is_verified'
        ];
        $updateValues  = [
            $_POST['name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['mobile_no'],
            $_POST['role'],
            $profile_picture,
            $_POST['auth_provider'],
            $_POST['two_step_auth'],
            $_POST['is_verified']
        ];

        $result = $DB->update('users', $updateColumns, $updateValues, 'id', $u_id);
        if ($result) {
            $_SESSION['msg'] = "User updated successfully.";
            header("Location: user.php");
            exit();
        } else {
            $msg = "<div class='alert alert-danger'>Error updating user.</div>";
        }
    } else {
        $chk = $DB->read("users", ["where" => ["email" => ["=" => $_POST['email']]]]);
        if (mysqli_num_rows($chk) === 0) {
            $result = $DB->create('users', $columns, $values);
            if ($result) {
                $_SESSION['msg'] = "User created successfully.";
                header("Location: user.php");
                exit();
            } else {
                $msg = "<div class='alert alert-danger'>Error creating user.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>User already exists.</div>";
        }
    }
}

$disabledAttr = $isViewMode ? 'readonly disabled' : '';
$selectDisabled = $isViewMode ? 'disabled' : '';
?>

<?php echo $msg ?? ''; ?>

<div class="row align-items-center justify-content-between mt-10">
    <div class="col-lg-10 m-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= $isEditMode ? 'Edit User' : ($isViewMode ? 'User Details' : 'Create New User') ?></h5>
                <a href="user.php" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>

            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" novalidate>
                    <div class="row row-cols-1 row-cols-md-2 g-3">

                        <!-- Full Name -->
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Full Name"
                                    value="<?= htmlspecialchars($_POST['name'] ?? $userData['name'] ?? '') ?>"
                                    required <?= $disabledAttr ?>>
                                <label for="name">Full Name *</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Email"
                                    value="<?= htmlspecialchars($_POST['email'] ?? $userData['email'] ?? '') ?>"
                                    required <?= $disabledAttr ?>>
                                <label for="email">Email *</label>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password"
                                    value="<?= htmlspecialchars($_POST['password'] ?? $userData['password'] ?? '') ?>"
                                    <?php
                                    if (!empty($userData['auth_provider']) && $userData['auth_provider'] === 'google') {
                                        echo 'readonly disabled';
                                    } else {
                                        echo 'required';
                                    }
                                    ?> <?= $disabledAttr ?>>
                                <label for="password">Password *</label>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="mobile_no" name="mobile_no"
                                    placeholder="Mobile"
                                    value="<?= htmlspecialchars($_POST['mobile_no'] ?? $userData['mobile_no'] ?? '') ?>"
                                    <?= $disabledAttr ?>>
                                <label for="mobile_no">Mobile Number</label>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" id="role" name="role" required <?= $selectDisabled ?>>
                                    <option value="">Select Role</option>
                                    <option value="admin" <?= (($_POST['role'] ?? $userData['role'] ?? '') === 'admin')  ? 'selected' : '' ?>>Admin</option>
                                    <option value="staff" <?= (($_POST['role'] ?? $userData['role'] ?? '') === 'staff')  ? 'selected' : '' ?>>Staff</option>
                                </select>
                                <label for="role">Role *</label>
                            </div>
                        </div>

                        <!-- Auth Provider -->
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" id="auth_provider" name="auth_provider" required <?= $selectDisabled ?>>
                                    <option value="local" <?= (($_POST['auth_provider'] ?? $userData['auth_provider'] ?? '') === 'local')  ? 'selected' : '' ?>>Local</option>
                                    <option value="google" <?= (($_POST['auth_provider'] ?? $userData['auth_provider'] ?? '') === 'google') ? 'selected' : '' ?>>Google</option>
                                </select>
                                <label for="auth_provider">Auth Provider *</label>
                            </div>
                        </div>

                        <!-- Two-step -->
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" id="two_step_auth" name="two_step_auth" <?= $selectDisabled ?>>
                                    <option value="0" <?= (($_POST['two_step_auth'] ?? $userData['two_step_auth'] ?? '') == '0') ? 'selected' : '' ?>>Disabled</option>
                                    <option value="1" <?= (($_POST['two_step_auth'] ?? $userData['two_step_auth'] ?? '') == '1') ? 'selected' : '' ?>>Enabled</option>
                                </select>
                                <label for="two_step_auth">Two-Step Auth</label>
                            </div>
                        </div>

                        <!-- Verified -->
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" id="is_verified" name="is_verified" <?= $selectDisabled ?>>
                                    <option value="0" <?= (($_POST['is_verified'] ?? $userData['is_verified'] ?? '') == '0') ? 'selected' : '' ?>>No</option>
                                    <option value="1" <?= (($_POST['is_verified'] ?? $userData['is_verified'] ?? '') == '1') ? 'selected' : '' ?>>Yes</option>
                                </select>
                                <label for="is_verified">Is Verified</label>
                            </div>
                        </div>

                        <!-- Profile Picture -->
                        <div class="col-12">
                            <label class="form-label">Profile Picture</label>
                            <?php if ($isViewMode && !empty($userData['profile_picture'])): ?>
                                <div class="mt-2">
                                    <img src="../images/profile/<?= $userData['profile_picture'] ?>"
                                        alt="Profile" class="rounded" style="max-height: 150px;">
                                </div>
                            <?php elseif (!$isViewMode): ?>
                                <input type="file" class="form-control" name="profile_picture"
                                    accept="image/*" <?= $selectDisabled ?>>
                                <?php if (!empty($userData['profile_picture'])): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Current:</small>
                                        <img src="../images/profile/<?= $userData['profile_picture'] ?>"
                                            alt="Profile" class="rounded ms-2" style="max-height: 80px;">
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                    </div><!-- ./row -->

                    <?php if (!$isViewMode): ?>
                        <div class="text-end mt-4">
                            <button type="submit" name="submit-user" class="btn btn-primary px-4">
                                <?= $isEditMode ? 'Update' : 'Create' ?>
                            </button>
                        </div>
                    <?php endif; ?>
                </form>
            </div><!-- ./card-body -->
        </div><!-- ./card -->
    </div>
</div>


<?php include './include/footer-admin.php'; ?>
