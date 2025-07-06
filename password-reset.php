<?php $pageTitle = "Password Reset";
include_once "./include/header-auth.php";
if (isset($_GET['error'])) {
  echo "<div class='alert alert-danger'>" . $_GET['error'] . "</div>";
}
?>

<div class="row">
  <form action="verfiy-otp.php" method="post">

    <div class="col-md-12 mb-3">

      <h2>Password Reset</h2>
      <p>Enter your email to recover your ID</p>

    </div>

    <div class="col-md-12">
      <div class="mb-4">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control">
      </div>
    </div>
    <div class="col-12">
      <div class="mb-4">
        <button class="btn btn-secondary w-100" name="recover" type="submit">RESET</button>
      </div>
    </div>
  </form>

</div>
<?php include_once "./include/footer-auth.php"; ?>