<?php $pageTitle = "Category Create";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';
?>

<div class="row mb-4 layout-spacing layout-top-spacing">
    <form method="POST" enctype="multipart/form-data">
        <!-- Left Side (Main Content) -->
        <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="widget-content widget-content-area blog-create-section">
                <!-- Category Title -->
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label class="form-label">Enter category title</label>
                        <input type="text" class="form-control" name="title" placeholder="Category name" required>
                    </div>
                </div>

                <!-- Description -->
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Enter category description"></textarea>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label class="form-label">Category Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                </div>
            
                <!-- Publish Toggle -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="showPublicly" name="is_published" checked>
                            <label class="form-check-label" for="showPublicly">Publish</label>
                        </div>
                    </div>
                </div>

                <!-- Tags Input -->
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label">Tags</label>
                        <input type="text" class="form-control" name="tags" placeholder="Add tags (comma separated)">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mb-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success w-100">SUBMIT</button>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>

<?php include('./include/footer-admin.php'); ?>