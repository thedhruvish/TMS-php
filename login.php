<?php $pageTitle = "Login";
include_once "./include/header-auth.php";

if (isset($_GET['error'])) {
  echo "<div class='alert alert-danger'>" . $_GET['error'] . "</div>";
}

if (isset($_SESSION['user_id'])) {

  $url = $_SESSION['role'] == "admin" ? "admin" : "staff";

  header("Location:" . $url);
}

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $result = $DB->read("users", [
    "where" => [
      "email" => ["=" => $email],
      'password' => ['=' => $password]
    ],
  ]);

  if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if ($user['is_verified'] == 0) {
    $insert_log = $DB->create("user_log",['user_id',"is_success","email"],[$user['id'],0,$email]);

      header("location: ./login.php?error=Contect to Admin for the approved to account");
      exit();
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    $insert_log = $DB->create("user_log",['user_id',"is_success","email"],[$user['id'],1,$email]);
    if ($user['role'] === 'admin') {
      header("Location: ./admin");
    } else {
      header("Location: ./staff");
    }
  } else {
    echo "<div class='alert alert-danger'>No user found with that email.</div>";
  }
}
?>

<div class="row">

  <form method="post">
    <div class="col-md-12 mb-3">

      <h2>Sign In</h2>
      <p>Enter your email and password to login</p>

    </div>
    <div class="col-md-12">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control">
      </div>
    </div>
    <div class="col-12">
      <div class="mb-4">
        <label class="form-label">Password</label>
        <input name="password" type="password" class="form-control">
      </div>
    </div>


    <div class="col-12">
      <div class="mb-4">
        <a href="password-reset.php" class="text-primary "> Forget your password ?</a>
      </div>
    </div>
    <div class="col-12">
      <div class="mb-4">
        <button name="login" type="submit" class="btn btn-secondary w-100">SIGN IN</button>
      </div>
    </div>
  </form>

  <div class="col-12 mb-4">
    <div class="">
      <div class="seperator">
        <hr>
        <div class="seperator-text"> <span>Or continue with</span></div>
      </div>
    </div>
  </div>

  <?php include_once 'include/login-with-google.php'; ?>

  <!-- <div class="col-12">
    <div class="text-center">
      <p class="mb-0">Dont't have an account ? <a href="<?php echo "register.php" ?>" class="text-warning">Sign Up</a></p>
    </div>
  </div> -->

</div>

<?php include_once "./include/footer-auth.php"; ?>