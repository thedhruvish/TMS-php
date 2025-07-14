<div class="col-sm-4 col-12 offset-sm-4">
  <div class="mb-4">
    <a href="https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=<?php echo GOOGLE_CLIENT_ID; ?>&scope=openid email profile&redirect_uri=<?php echo GOOGLE_REDIRECT_URI; ?>&prompt=consent">
      <button class="btn btn-social-login w-100">
        <img src="./src/assets/img/google-gmail.svg" alt="" class="img-fluid">
        <span class="btn-text-inner">Google</span>
      </button>
    </a>
  </div>

</div>