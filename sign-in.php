<?php
// not required
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
  $basePath = dirname($_SERVER['SCRIPT_NAME']);
  $basePath = rtrim($basePath, '/');
  header('Location: ' . $basePath);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Healthsync - Sign Up";
include './head.php';
?>

<body>
  <div class="container-fluid min-vh-100 d-flex flex-column align-items-center px-3 px-md-5">
    <div class="row flex-grow-1 align-items-center w-100 overflow-auto">
      <div class="col-12 col-md-6 d-none d-md-flex justify-content-center align-items-center mb-4 mb-md-0">
        <img src="./assets/sign_in_hero.png" class="img-fluid" alt="Hero Image" style="max-height: 500px;">
      </div>
      
      <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
        <div class="bg-white p-4 p-md-5 rounded shadow w-100" style="max-width: 50em;">
          <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h1 class="display-6 display-md-5 text-center text-md-start m-0">
              Welcome to <br>
              <span class="fw-bold text-primary">HealthSync</span>
            </h1>
            <div class="d-flex gap-2">
              <img src="./assets/ucu.jpg" alt="Logo 1" style="height: 100px;">
              <img src="./assets/logo-bg-remove.png" alt="Logo 2" style="height: 100px;">
            </div>
          </div>
          
        <form class="d-flex flex-column gap-3">
          <div class="alert alert-danger d-none" id="warning">
            Incorrect email or password
          </div>


            <div class="form-group d-flex align-items-center bg-secondary bg-opacity-10 rounded px-3" style="height: 70px;">
              <span class="input-group-text bg-transparent border-0">
                <i class="bi bi-envelope-fill text-primary fs-5"></i>
              </span>
              <input type="text" class="form-control border-0 bg-transparent ms-3 shadow-none" id="email-address" name="email-address" placeholder="Email Address">
            </div>

            <div class="form-group position-relative d-flex align-items-center bg-secondary bg-opacity-10 rounded px-3" style="height: 70px;">
              <span class="input-group-text bg-transparent border-0">
                <i class="bi bi-lock-fill text-primary fs-5"></i>
              </span>
              <input type="password" class="form-control border-0 bg-transparent ms-3 shadow-none" id="password" name="password" placeholder="Password">
              <button type="button" id="toggle-password" class="btn btn-sm bg-transparent border-0 position-absolute end-0 me-3">
                <i class="bi bi-eye-slash" id="toggle-password-icon"></i>
              </button>
            </div>

            <div class="d-flex flex-column flex-sm-row align-items-center">
              <div class="form-check me-sm-auto mb-2 mb-sm-0">
                <input class="form-check-input" type="checkbox" id="remember-me" name="remember-me">
                <label class="form-check-label" for="remember-me">Remember me</label>
              </div>
              <a href="./forgot-password.php" class="text-decoration-none text-primary">Forgot password?</a>
            </div>

            <button id="sign-in" class="btn btn-primary py-3 w-100">Sign in</button>

            <div class="d-flex align-items-center">
              <hr class="flex-grow-1 border-1 border-secondary">
              <span class="px-3 text-secondary">or</span>
              <hr class="flex-grow-1 border-1 border-secondary">
            </div>

            <a href="./sign-up.php" class="m-auto text-decoration-none text-primary">Create an Account</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#sign-in').click(function(e) {
        e.preventDefault();
        $('#warning').addClass('d-none');
        axios.post('/healthsync/auth/sign-in.php', {
          'email-address': $('#email-address').val(),
          'password': $('#password').val()
        }).then((res) => {
          location.href = '/healthsync/';
        }).catch((err) => {
          $('#warning').removeClass('d-none');
          console.error(err);
        });
      });

      $('#toggle-password').click(function() {
        const passwordInput = $('#password');
        const icon = $('#toggle-password-icon');

        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);

        icon.toggleClass('bi-eye-slash bi-eye');
      });
    });
  </script>
</body>

</html>