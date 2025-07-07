<?php
$pageTitle = "OTP Verfity";
include_once "./include/header-auth.php";
include_once "send_mail.php";

$is_otp = true;
$email = $_SESSION['reset_email'] ?? '';
$user_id = $_SESSION['user_id_reset'] ?? '';


function send_otp()
{
  global $user_id, $email, $DB;
  $otp = rand(1000, 9999);
  $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));
  $DB->create('reset_password', ["user_id", "email", 'otp', "otp_expires", "is_verified"], [$user_id, $email, $otp, $expires, 0]);

  send_otp_mail($email, $otp,"Password Reset ");
}

//  password-reset form submit than genrate new opt ,save db and send mail to
if (isset($_POST['recover'])) {
  $email = $_POST['email'];
  $_SESSION['reset_email'] = $email;

  $result = $DB->read("users", [
    "where" => [
      "email" => ["=" => $email],
    ]
  ]);

  // check user is found
  if (mysqli_num_rows($result) === 1) {
    $user_data = mysqli_fetch_assoc($result);
    if ($user_data['auth_provider'] == "google") {
      header("Location: password-reset.php?error=Login with google can't reset password");
    }
    $_SESSION['user_id_reset'] = $user_data['id'];
    send_otp();
  } else {
    header("Location: password-reset.php?error=Invalid email");
  }
}

// otp check after set user new password
if (isset($_POST['reset_passowrd'])) {
  $pwd = $_POST['passowrd'];
  $DB->update("users", ["password"], [$pwd], "email", $email);
  session_reset();
  header("Location: login.php");
}

// check opt are the valid or not
if (isset($_POST['verify'])) {
  // concet otp
  $otp_user = (int)$_POST['otp-1'] . $_POST['otp-2'] . $_POST['otp-3'] . $_POST['otp-4'];

  $reset_password_result = $DB->read("reset_password", [
    "where" => [
      "email" => ["=" => $email],
      "otp" => ["=" => $otp_user],
      "is_verified" => ["=" => 0]
    ]
  ]);
  $reset_password_data = mysqli_fetch_assoc($reset_password_result);

  if (mysqli_num_rows($reset_password_result) == 1) {
    $DB->update("reset_password", ["is_verified"], [1], "id", $reset_password_data['id']);
    $is_otp = false;
  } else {
    echo "<div class='alert alert-danger'>Invalid OTP</div>";
    $is_otp = true;
  }
}

if (isset($_POST['resend'])) {
  send_otp();
}

?>
<?php if ($is_otp) { ?>
  <form method="post">
    <div class="row">
      <div class="col-md-12 mb-3">

        <h2>OTP Verification</h2>
        <p>Check the <b> <?php echo $email; ?></b> mail box. Enter the code for verification.</p>

      </div>
      <div class="col-sm-2 col-3 ms-auto">
        <div class="mb-3">
          <input type="text" name="otp-1" maxlength="1" class="form-control opt-input">
        </div>
      </div>
      <div class="col-sm-2 col-3">
        <div class="mb-3">
          <input type="text" name="otp-2" maxlength="1" class="form-control opt-input">
        </div>
      </div>
      <div class="col-sm-2 col-3">
        <div class="mb-3">
          <input type="text" name="otp-3" maxlength="1" class="form-control opt-input">
        </div>
      </div>
      <div class="col-sm-2 col-3 me-auto">
        <div class="mb-3">
          <input type="text" name="otp-4" maxlength="1" class="form-control opt-input">
        </div>
      </div>

      <div class="col-12 mt-4">
        <div class="mb-4">
          <button class="btn btn-secondary w-100" name="verify" type="submit">VERIFY</button>
        </div>
      </div>
      <div class="col-12 mt-3 text-center">
        <button type="submit" name="resend" id="resendBtn" class="btn btn-link text-danger p-0" disabled>
          Didn't receive the code? <strong>Resend (<span id="resend-timer">1200</span>s)</strong>
        </button>
      </div>


    </div>
  </form>
  <script>
    let countdownTime = 1200; // seconds
    const resendBtn = document.getElementById('resendBtn');
    const timerSpan = document.getElementById('resend-timer');

    const resendCountdown = setInterval(() => {
      countdownTime--;

      timerSpan.textContent = countdownTime;

      if (countdownTime <= 0) {
        clearInterval(resendCountdown);
        resendBtn.disabled = false;
        resendBtn.innerHTML = `Didn't receive the code? <strong>Resend</strong>`;
      }
    }, 1000);
  </script>

<?php  } else { ?>

  <div class="row">
    <form action="verfiy-otp.php" method="post">

      <div class="col-md-12 mb-3">

        <h2>Enter New Password</h2>

      </div>

      <div class="col-md-12">
        <div class="mb-4">
          <label class="form-label">Password</label>
          <input name="passowrd" type="password" class="form-control">
        </div>
      </div>
      <div class="col-12">
        <div class="mb-4">
          <button class="btn btn-secondary w-100" name="reset_passowrd" type="submit">Reset Password </button>
        </div>
      </div>
    </form>

  </div>

<?php } ?>


<?php include_once "./include/footer-auth.php"; ?>