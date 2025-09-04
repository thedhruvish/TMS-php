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

// Get search and filter terms
$searchTerm = $_GET['search'] ?? '';
$filterCategory = $_GET['filter'] ?? '';

// Get all categories with product counts
$categories = [];
$error = null;

try {
  $params = [];
  $query = "SELECT * FROM category";
  $whereClauses = [];

  if (!empty($searchTerm)) {
      $whereClauses[] = "(tag LIKE ? OR description LIKE ?)";
      $searchTermWildcard = "%" . $searchTerm . "%";
      $params[] = &$searchTermWildcard;
      $params[] = &$searchTermWildcard;
  }

  if (!empty($filterCategory)) {
      $whereClauses[] = "tag = ?";
      $params[] = &$filterCategory;
  }

  if (!empty($whereClauses)) {
      $query .= " WHERE " . implode(" AND ", $whereClauses);
  }
  
  $res = $DB->custom_query($query, $params);

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

// Helper for cleaning output
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

<div class="row">
  <div class="seperator-header layout-top-spacing">
    <h4 class="mb-0">Category </h4>
    <a href="cetegory-add.php" class="btn btn-primary">Add New Category</a>
  </div>

  <div class="col-12 mt-4">
    <div class="statbox widget box box-shadow p-3">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-7 col-12 mb-2 mb-md-0">
                <form method="get" class="w-100">
                    <input type="hidden" name="filter" value="<?php echo e($filterCategory); ?>">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by Tag or Description..." value="<?php echo e($searchTerm); ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                        <?php if (!empty($searchTerm) || !empty($filterCategory)) { ?>
                            <a href="cetegory.php" class="btn btn-outline-danger">Clear</a>
                        <?php } ?>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-md-5 col-12 d-flex justify-content-md-end">
                <div class="dropdown w-100">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="categoryFilter" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo !empty($filterCategory) ? e($filterCategory) : 'Filter by Category' ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end w-100" aria-labelledby="categoryFilter">
                        <li><a class="dropdown-item" href="cetegory.php?search=<?php echo urlencode($searchTerm); ?>">All Categories</a></li>
                        <?php foreach ($allCategories as $cat) { ?>
                            <li><a class="dropdown-item" href="cetegory.php?filter=<?php echo urlencode($cat) ?>&search=<?php echo urlencode($searchTerm); ?>"><?php echo e($cat) ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="col-12">
    <?php if (isset($_SESSION['message'])) { ?>
      <div class="alert alert-success alert-dismissible fade show mt-4">
        <?php echo $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php unset($_SESSION['message']); } ?>

    <?php if ($error) { ?>
      <div class="alert alert-danger mt-4">
        Error: <?php echo $error ?>
      </div>
    <?php } ?>
  </div>
  

  <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
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
            <?php if (empty($categories)) { ?>
              <tr>
                <td colspan="6" class="text-center">No categories found matching your criteria.</td>
              </tr>
            <?php } else { ?>
              <?php foreach ($categories as $category) { ?>
                <tr>
                  <td><?php echo e($category['tag'] ?? '') ?></td>
                  <td><?php echo e($category['description'] ?? '') ?></td>
                  <td>
                    <?php if (!empty($category['image'])) { ?>
                      <div class="text-center">
                        <img alt="category-image" class="img-thumbnail"
                          src="../<?php echo e($category['image']) ?>"
                          style="max-width: 100px; max-height: 100px; object-fit: contain;">
                      </div>
                    <?php } else { ?>
                      <span class="text-muted">No image</span>
                    <?php } ?>
                  </td>
                  <td><span class="badge badge-primary"><?php echo $category['product_count'] ?? 0 ?></span></td>
                  <td>
                    <a href="cetegory-add.php?u_id=<?php echo $category['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                  </td>
                  <td>
                    <button onclick="confirmDelete(<?php echo $category['id'] ?>)" class="btn btn-sm btn-outline-danger">Delete</button>
                  </td>
                </tr>
              <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

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

<?php require_once './include/footer-admin.php'; ?>