<?php 
$pageTitle = "Edit Product";
require_once './include/header-admin.php';

// Initialize variables
$error = null;
$product = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = (int)$_POST['id'];
        $columns = [
            'name',
            'category',
            'regular_price',
            'sale_price',
            'in_stock',
            'description'
        ];
        
        $values = [
            $_POST['name'],
            $_POST['category'],
            (float)$_POST['regular_price'],
            !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null,
            isset($_POST['in_stock']) ? 1 : 0,
            $_POST['description']
        ];
        
        $result = $DB->update("products", $columns, $values, "id", $id);
        
        if ($result) {
            $_SESSION['message'] = "Product updated successfully";
            header("Location: products.php");
            exit();
        } else {
            $error = "Failed to update product";
        }
    } catch (Exception $e) {
        $error = "Error updating product: " . $e->getMessage();
    }
}

// Get product data
if (isset($_GET['id'])) {
    try {
        $id = (int)$_GET['id'];
        $res = $DB->read("products", ["where" => ["id" => ["=" => $id]]]);
        if ($res && mysqli_num_rows($res) > 0) {
            $product = mysqli_fetch_assoc($res);
        } else {
            header("Location: products.php");
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else {
    header("Location: products.php");
    exit();
}
?>

<div class="container mt-4">
    <h2>Edit Product</h2>
    
    <?php if ($error): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <form method="post">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($product['category']) ?>" required>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Regular Price</label>
                <input type="number" step="0.01" name="regular_price" class="form-control" 
                       value="<?= htmlspecialchars($product['regular_price']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Sale Price (optional)</label>
                <input type="number" step="0.01" name="sale_price" class="form-control" 
                       value="<?= htmlspecialchars($product['sale_price']) ?>">
            </div>
        </div>
        
        <div class="mb-3 form-check">
            <input type="checkbox" name="in_stock" class="form-check-input" id="in_stock" 
                   <?= $product['in_stock'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="in_stock">In Stock</label>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include_once('./include/footer-admin.php'); ?>