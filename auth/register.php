<?php $pageTitle = "Register";
include('header-auth.php');
?>
<div class="row">
  <div class="col-md-12 mb-3">

    <h2>Sign Up</h2>
    <p>Enter your email and password to register</p>

  </div>
  <div class="col-md-12">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" class="form-control add-billing-address-input">
    </div>
  </div>
  <div class="col-md-12">
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" class="form-control">
    </div>
  </div>
  <div class="col-12">
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="text" class="form-control">
    </div>
  </div>
  <div class="col-12">
    <div class="mb-3">
      <div class="form-check form-check-primary form-check-inline">
        <input class="form-check-input me-3" type="checkbox" id="form-check-default">
        <label class="form-check-label" for="form-check-default">
          I agree the <a href="javascript:void(0);" class="text-primary">Terms and Conditions</a>
        </label>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="mb-4">
      <button class="btn btn-secondary w-100">SIGN UP</button>
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
      <p class="mb-0">Already have an account ? <a href="<?php echo "login.php" ?>" class="text-warning">Sign in</a></p>
    </div>
  </div>

</div>
<?php include('footer-auth.php'); ?>