<?php $pageTitle = "Login";
include('header-auth.php');
?>
<div class="row">
  <div class="col-md-12 mb-3">

    <h2>Sign In</h2>
    <p>Enter your email and password to login</p>

  </div>
  <div class="col-md-12">
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" class="form-control">
    </div>
  </div>
  <div class="col-12">
    <div class="mb-4">
      <label class="form-label">Password</label>
      <input type="text" class="form-control">
    </div>
  </div>


  <div class="col-12">
    <div class="mb-4">
      <button class="btn btn-secondary w-100">SIGN IN</button>
    </div>
  </div>

  <div class="col-12 mb-4">
    <div class="">
      <div class="seperator">
        <hr>
        <div class="seperator-text"> <span>Or continue with</span></div>
      </div>
    </div>
  </div>

  <?php include('login-with-google.php'); ?>

  <div class="col-12">
    <div class="text-center">
      <p class="mb-0">Dont't have an account ? <a href="<?php echo "register.php" ?>" class="text-warning">Sign Up</a></p>
    </div>
  </div>

</div>

<?php include('footer-auth.php'); ?>