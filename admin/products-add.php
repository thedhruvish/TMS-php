<?php
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';
?>

<link href="../layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
<link href="../layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
<link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/src/tagify/tagify.css">

<link rel="stylesheet" type="text/css" href="../src/assets/css/light/forms/switches.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/light/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/light/tagify/custom-tagify.css">
<link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../src/assets/css/dark/forms/switches.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/editors/quill/quill.snow.css">
<link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/tagify/custom-tagify.css">
<link href="../src/plugins/css/dark/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

<!--  BEGIN CUSTOM STYLE FILE  -->
<link rel="stylesheet" href="../src/assets/css/light/apps/ecommerce-create.css">
<link rel="stylesheet" href="../src/assets/css/dark/apps/ecommerce-create.css">
<!--  END CUSTOM STYLE FILE  -->

<?php

$product = [
    'name' => '',
    'description' => '',
    'product_code' => '',
    'category' => '',
    'tags' => '',
    'regular_price' => '',
    'sale_price' => '',
    'includes_tax' => 0,
    'in_stock' => 0,
    'show_publicly' => 1,
    'disabled' => 0,
    'images' => ''
];

$readonly = false;
$isUpdate = false;
$updateId = null;
$uploadedImages = [];

// Get all categories from database
$categories = [];
$categoriesRes = $DB->read("category");
if ($categoriesRes && mysqli_num_rows($categoriesRes) > 0) {
    $categories = mysqli_fetch_all($categoriesRes, MYSQLI_ASSOC);
}

// View mode
if (isset($_GET['id'])) {
    $res = $DB->read("products", ['where' => ['id' => ['=' => $_GET['id']]]]);
    if ($res && mysqli_num_rows($res) > 0) {
        $product = mysqli_fetch_assoc($res);
        $readonly = true;
        if (!empty($product['images'])) {
            $uploadedImages = json_decode($product['images'], true);
        }
    }
}

// Update mode
if (isset($_GET['u_id'])) {
    $res = $DB->read("products", ['where' => ['id' => ['=' => $_GET['u_id']]]]);
    if ($res && mysqli_num_rows($res) > 0) {
        $product = mysqli_fetch_assoc($res);
        if (!empty($product['images'])) {
            $uploadedImages = json_decode($product['images'], true);
        }
        $isUpdate = true;
        $updateId = $_GET['u_id'];
    }
}

// Handle Form Submission
if (isset($_POST['save'])) {
    $uploadedImagePaths = [];

    // Handle multiple file uploads
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = '../images/products/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $originalName = basename($_FILES['images']['name'][$key]);
                $uniqueName = time() . '_' . $key . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '_', $originalName);
                $targetPath = $uploadDir . $uniqueName;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $uploadedImagePaths[] =  $uniqueName;
                }
            }
        }
    }

    // If no new images uploaded but in update mode, keep existing images
    if ($isUpdate && empty($uploadedImagePaths) && !empty($product['images'])) {
        $uploadedImagePaths = json_decode($product['images'], true);
    }

    $data = [
        $_POST['name'],
        $_POST['description'],
        $_POST['product_code'],
        $_POST['category'],
        $_POST['tags'],
        $_POST['regular_price'],
        $_POST['sale_price'],
        isset($_POST['includes_tax']) ? 1 : 0,
        $product['in_stock'], // Keep existing stock status
        isset($_POST['show_publicly']) ? 1 : 0,
        isset($_POST['disabled']) ? 1 : 0,
        json_encode($uploadedImagePaths)
    ];

    $columns = [
        'name',
        'description',
        'product_code',
        'category',
        'tags',
        'regular_price',
        'sale_price',
        'includes_tax',
        'in_stock',
        'show_publicly',
        'disabled',
        'images'
    ];

    if ($isUpdate && $updateId !== null) {
        $DB->update('products', $columns, $data, 'id', $updateId);
        $_SESSION['message'] = "Product updated successfully";
    } else {
        $DB->create('products', $columns, $data);
        $_SESSION['message'] = "Product added successfully";
    }

    header("Location: products.php?success=1");
    exit;
}

?>

<!-- Messages -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php unset($_SESSION['message']);
endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        Error: <?php echo htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="POST" class="row mb-4 layout-spacing layout-top-spacing" enctype="multipart/form-data">
    <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="widget-content widget-content-area ecommerce-create-section">
            <div class="row mb-4">
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="name" value="<?php echo $product['name'] ?>" placeholder="Product Name" <?php echo $readonly ? 'readonly' : '' ?>>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-12">
                    <label>Description</label>
                    <textarea class="form-control" name="description" <?php echo $readonly ? 'readonly' : '' ?>><?php echo $product['description'] ?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <label>Upload Images</label>
                    <input type="file" class="form-control" name="images[]" multiple <?php echo $readonly ? 'disabled' : '' ?>>

                    <?php if (!empty($uploadedImages)): ?>
                        <div class="mt-3">
                            <div class="image-preview-container">
                                <div class="main-image-container">
                                    <img src="../images/products/<?php echo $uploadedImages[0] ?>" alt="Product Image" class="main-product-image" style="max-height: 200px;">
                                </div>
                                <div class="thumbnail-container d-flex mt-2">
                                    <?php foreach ($uploadedImages as $index => $image): ?>
                                        <div class="thumbnail-wrapper me-2">
                                            <img src="../images/products/<?php echo $image ?>" alt="Thumbnail <?php echo $index ?>" class="thumbnail-image <?php echo $index === 0 ? 'active' : '' ?>" style="height: 60px; width: 60px; object-fit: cover; cursor: pointer;" onclick="changeMainImage(this, '<?php echo $image ?>')">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <p class="text-muted small mt-1">Click thumbnails to view</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="show_publicly" <?php echo $product['show_publicly'] ? 'checked' : '' ?> <?php echo $readonly ? 'disabled' : '' ?>>
                        <label class="form-check-label">Display publicly</label>
                    </div>
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="disabled" <?php echo $product['disabled'] ? 'checked' : '' ?> <?php echo $readonly ? 'disabled' : '' ?>>
                        <label class="form-check-label">Disable Product</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="row">
            <div class="col-xxl-12 col-xl-8 col-lg-8 col-md-7 mt-xxl-0 mt-4">
                <div class="widget-content widget-content-area ecommerce-create-section">
                    <div class="row">
                        <div class="col-xxl-12 col-md-6 mb-4">
                            <label>Product Code</label>
                            <input type="text" class="form-control" name="product_code" value="<?php echo $product['product_code'] ?>" <?php echo $readonly ? 'readonly' : '' ?>>
                        </div>

                        <div class="col-xxl-12 col-md-6 mb-4">
                            <label>Category</label>
                            <select name="category" class="form-select" <?php echo $readonly ? 'disabled' : '' ?>>
                                <option value="">Choose...</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['tag']) ?>" <?php echo $product['category'] === $cat['tag'] ? 'selected' : '' ?>>
                                        <?php echo htmlspecialchars($cat['tag']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-xxl-12 col-lg-6 col-md-12">
                            <label>Tags</label>
                            <input type="text" class="form-control" name="tags" value="<?php echo $product['tags'] ?>" <?php echo $readonly ? 'readonly' : '' ?>>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-12 col-xl-4 col-lg-4 col-md-5 mt-4">
                <div class="widget-content widget-content-area ecommerce-create-section">
                    <div class="row">
                        <div class="col-sm-12 mb-4">
                            <label>Regular Price</label>
                            <input type="text" class="form-control" name="regular_price" value="<?php echo $product['regular_price'] ?>" <?php echo $readonly ? 'readonly' : '' ?>>
                        </div>
                        <div class="col-sm-12 mb-4">
                            <label>Sale Price</label>
                            <input type="text" class="form-control" name="sale_price" value="<?php echo $product['sale_price'] ?>" <?php echo $readonly ? 'readonly' : '' ?>>
                        </div>
                        <div class="col-sm-12 mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="includes_tax" <?php echo $product['includes_tax'] ? 'checked' : '' ?> <?php echo $readonly ? 'disabled' : '' ?>>
                                <label class="form-check-label">Price includes taxes</label>
                            </div>
                        </div>
                        <?php if (!$readonly): ?>
                            <div class="col-sm-12">
                                <button type="submit" name="save" class="btn btn-success w-100"><?php echo $isUpdate ? 'Update' : 'Add' ?> Product</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function changeMainImage(element, imageSrc) {
        // Update main image
        document.querySelector('.main-product-image').src = imageSrc;

        // Update active thumbnail
        document.querySelectorAll('.thumbnail-image').forEach(img => {
            img.classList.remove('active');
        });
        element.classList.add('active');
    }
</script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../src/plugins/src/editors/quill/quill.js"></script>
<script src="../src/plugins/src/filepond/filepond.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginFileValidateType.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImageCrop.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImageResize.min.js"></script>
<script src="../src/plugins/src/filepond/FilePondPluginImageTransform.min.js"></script>
<script src="../src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>

<script src="../src/plugins/src/tagify/tagify.min.js"></script>

<script src="../src/assets/js/apps/ecommerce-create.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<?php require_once './include/footer-admin.php'; ?>