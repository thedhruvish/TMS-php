<?php
$pageTitle = "Category Create";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$category = [
    'tag' => '',
    'description' => '',
    'image' => ''
];

$isUpdate = false;
$updateId = null;
$uploadedImagePath = null;

// Update mode
if (isset($_GET['u_id'])) {
    $res = $DB->read("category", ['where' => ['id' => ['=' => $_GET['u_id']]]]);
    if ($res && mysqli_num_rows($res) > 0) {
        $category = mysqli_fetch_assoc($res);
        $uploadedImagePath = $category['image'];
        $isUpdate = true;
        $updateId = $_GET['u_id'];
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../images/categories/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = basename($_FILES['image']['name']);
        $uniqueName = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '_', $originalName);
        $targetPath = $uploadDir . $uniqueName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $uploadedImagePath = 'images/categories/' . $uniqueName;

            // Delete old image if it exists and we're updating
            if ($isUpdate && !empty($category['image']) && file_exists('../' . $category['image'])) {
                unlink('../' . $category['image']);
            }
        }
    } elseif ($isUpdate && !empty($category['image'])) {
        // Keep existing image if no new image was uploaded
        $uploadedImagePath = $category['image'];
    }

    // Prepare data - matches your table structure
    $data = [
        $_POST['description'],
        $uploadedImagePath,
        $_POST['title']  // This is stored in 'tag' column
    ];

    $columns = ['description', 'image', 'tag'];

    try {
        if ($isUpdate && $updateId !== null) {
            $result = $DB->update('category', $columns, $data, 'id', $updateId);
            if ($result) {
                $_SESSION['message'] = "Category updated successfully";
                header("Location: cetegory.php");
                exit;
            }
        } else {
            $result = $DB->create('category', $columns, $data);
            if ($result) {
                $_SESSION['message'] = "Category added successfully";
                header("Location: cetegory.php");
                exit;
            }
        }

        // If we get here, the operation failed
        $error = "Failed to save category to database. Please check your data.";
    } catch (Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<link href="../layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
<link href="../layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />

<!-- Messages -->
<?php if (isset($_SESSION['message'])) { ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php unset($_SESSION['message']);
} ?>

<?php if (isset($error)) { ?>
    <div class="alert alert-danger">
        Error: <?php echo $error ?>
    </div>
<?php } ?>

<div class="row mb-4 layout-spacing layout-top-spacing">
    <form method="POST" enctype="multipart/form-data">
        <!-- Left Side (Main Content) -->
        <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="widget-content widget-content-area blog-create-section">
                <!-- Category Title (stored in 'tag' column) -->
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label class="form-label">Category Name (Tag) *</label>
                        <input type="text" class="form-control" name="title" placeholder="Category name"
                            value="<?php echo $category['tag'] ?>" required>
                        <small class="text-muted">This will be stored as the category tag</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label class="form-label">Description *</label>
                        <textarea required class="form-control" name="description" rows="4"
                            placeholder="Enter category description"><?php echo $category['description'] ?></textarea>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label class="form-label">Category Image *</label>
                        <input required type="file" class="form-control" name="image" accept="image/*">
                        <?php if ($isUpdate && !empty($category['image'])) { ?>
                            <div class="mt-2">
                                <img src="../<?php echo $category['image'] ?>" alt="Current Category Image" style="max-height: 100px;">
                                <p class="text-muted small mt-1">Current image</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mb-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success w-100">
                            <?php echo $isUpdate ? 'UPDATE CATEGORY' : 'ADD CATEGORY' ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once './include/footer-admin.php'; ?>