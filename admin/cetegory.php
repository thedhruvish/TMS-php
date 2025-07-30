<?php
$pageTitle = "Category";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle delete action
if (isset($_GET['delete_id'])) {
  try {
    $deleteId = (int)$_GET['delete_id'];
    $result = $DB->delete("category", "id", $deleteId);
    if ($result) {
      $_SESSION['message'] = "Category deleted successfully";
    } else {
      $_SESSION['message'] = "Error deleting category";
    }
    header("Location: cetegory.php");
    exit();
  } catch (Exception $e) {
    $error = "Error deleting category: " . $e->getMessage();
  }
}

// Get search term if exists
$searchTerm = $_GET['search'] ?? '';
$filterCategory = $_GET['filter'] ?? '';

// Get all categories with product counts
$categories = [];
$error = null;

try {
  // Base query conditions
  $filters = [];

  // Add search filter if term exists
  if (!empty($searchTerm)) {
    $filters['or_where'] = [
      'tag' => ['LIKE' => "%$searchTerm%"],
      'description' => ['LIKE' => "%$searchTerm%"]
    ];
  }

  // Add category filter if selected
  if (!empty($filterCategory)) {
    $filters['where'] = ['tag' => ['=' => $filterCategory]];
  }

  // Get all categories
  $res = $DB->read("category", $filters);
  if ($res === false) {
    throw new Exception("Query failed");
  }

  if (mysqli_num_rows($res) > 0) {
    $categories = mysqli_fetch_all($res, MYSQLI_ASSOC);

    // Get product counts for each category
    foreach ($categories as &$category) {
      $productRes = $DB->read("products", [
        'where' => ['category' => ['=' => $category['tag']]]
      ]);
      $category['product_count'] = $productRes ? mysqli_num_rows($productRes) : 0;
    }
    unset($category);
  }

  // Get all unique category tags for filter dropdown
  $allCategoriesRes = $DB->read("category");
  $allCategories = [];
  if ($allCategoriesRes && mysqli_num_rows($allCategoriesRes) > 0) {
    $allCategories = array_unique(array_column(mysqli_fetch_all($allCategoriesRes, MYSQLI_ASSOC), 'tag'));
  }
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>

<div class="row">
  <div class="seperator-header layout-top-spacing">
    <h4 class="mb-0">Category </h4>
    <a href="cetegory-add.php" class="btn btn-primary">Add New Category</a>
  </div>

  <!-- Search and Filter Section -->
  <div class="row mb-4 align-items-center justify-content-between">
    <div class="col-lg-6 d-flex align-items-center">
      <form method="get" class="d-flex flex-grow-1 gap-2">
        <?php if (!empty($searchTerm) || !empty($filterCategory)): ?>
          <a href="cetegory.php" class="btn btn-outline-secondary">Clear</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="col-lg-6 text-lg-end text-start mt-3 mt-lg-0">
      <div class="dropdown d-inline-block">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="categoryFilter" data-bs-toggle="dropdown" aria-expanded="false">
          <?= !empty($filterCategory) ? $filterCategory : 'Filter by Category' ?>
        </button>
        <ul class="dropdown-menu" aria-labelledby="categoryFilter">
          <li><a class="dropdown-item" href="cetegory.php">All Categories</a></li>
          <?php foreach ($allCategories as $cat): ?>
            <li><a class="dropdown-item" href="cetegory.php?filter=<?= urlencode($cat) ?>"><?= $cat ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>

  <!-- Messages -->
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?= $_SESSION['message'] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php unset($_SESSION['message']);
  endif; ?>

  <?php if ($error): ?>
    <div class="alert alert-danger">
      Error: <?= $error ?>
    </div>
  <?php endif; ?>

  <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
    <div class="statbox widget box box-shadow">
      <div class="widget-content widget-content-area">
        <table id="html5-extension" class="table dt-table-hover" style="width:100%">
          <thead>
            <tr>
              <th>Category</th>
              <th>Description</th>
              <th>Image</th>
              <th>Total Product</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($categories)): ?>
              <tr>
                <td colspan="6" class="text-center">No categories found</td>
              </tr>
            <?php else: ?>
              <?php foreach ($categories as $category): ?>
                <tr>
                  <td><?= $category['tag'] ?? '' ?></td>
                  <td><?= $category['description'] ?? '' ?></td>
                  <td>
                    <?php if (!empty($category['image'])): ?>
                      <div class="text-center">
                        <img alt="category-image" class="img-thumbnail"
                          src="../<?= $category['image'] ?>"
                          style="max-width: 120px; max-height: 120px; object-fit: contain;">
                      </div>
                    <?php else: ?>
                      <span class="text-muted">No image</span>
                    <?php endif; ?>
                  </td>
                  <td><?= $category['product_count'] ?? 0 ?></td>
                  <td>
                    <a href="cetegory-add.php?u_id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                  </td>
                  <td>
                    <button onclick="confirmDelete(<?= $category['id'] ?>)" class="btn btn-sm btn-outline-danger">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this category?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a id="deleteConfirmBtn" href="#" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmDelete(categoryId) {
    const deleteBtn = document.getElementById('deleteConfirmBtn');
    deleteBtn.href = `cetegory.php?delete_id=${categoryId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
  }
</script>

<?php include('./include/footer-admin.php'); ?>