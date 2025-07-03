<?php $pageTitle = "Register";
include_once "./include/header-auth.php";

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $mobile_no = $_POST['mobile_no'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Insert query
  $sql = "INSERT INTO users (name, mobile_no, email, password) VALUES ('$name', '$mobile_no', '$email', '$password')";

  if (mysqli_query($conn, $sql)) {
    echo "<div class='alert alert-success'>User registered successfully!</div>";

    header("Location: login.php");
  } else {
    echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
  }
}

?>
<div class="row">
  <form method="post" action="">
    <div class="col-md-12 mb-1">
      <h3>Sign Up</h3>
      <p>Enter your email and password to register</p>
    </div>

    <div class="col-md-12">
      <div class="mb-1">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
    </div>

    <div class="col-12">
      <div class="mb-1">
        <label class="form-label">Mobile No:</label>
        <input type="text" name="mobile_no" class="form-control" required>
      </div>
    </div>

    <div class="col-md-12">
      <div class="mb-1">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
    </div>

    <div class="col-12">
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
    </div>

    <div class="col-12">
      <div class="mb-4">
        <button type="submit" name="submit" class="btn btn-secondary w-100">SIGN UP</button>
      </div>
    </div>
  </form>
  <?php include_once 'include/login-with-google.php'; ?>
  <div class="col-12">
    <div class="text-center">
      <p class="mb-0">Already have an account ? <a href="<?php echo "login.php" ?>" class="text-warning">Sign in</a></p>
    </div>
  </div>

</div>

<?php include_once "./include/footer-auth.php"; ?>