<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Forgot Password";
include './head.php';
?>

<body>
  <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center bg-light px-3">
    <div class="bg-white p-4 p-md-5 rounded shadow w-100" style="max-width: 500px;">
      <h2 class="text-center text-primary mb-4">Forgot Password</h2>
      <p class="text-center text-muted mb-4">Enter your email to receive a reset link.</p>

      <form id="forgot-form" class="d-flex flex-column gap-3">
        <div class="form-group d-flex align-items-center bg-secondary bg-opacity-10 rounded px-3" style="height: 60px;">
          <span class="input-group-text bg-transparent border-0">
            <i class="bi bi-envelope-fill text-primary fs-5"></i>
          </span>
          <input type="email" class="form-control border-0 bg-transparent ms-3 shadow-none" name="email" id="email" placeholder="Email address" required>
        </div>

        <div class="alert alert-danger d-none" id="forgot-error"></div>
        <div class="alert alert-success d-none" id="forgot-success"></div>

        <button type="submit" class="btn btn-primary py-3 w-100">Send Reset Link</button>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#forgot-form').submit(function(e) {
        e.preventDefault();

        const email = $('#email').val();
        $('#forgot-error, #forgot-success').addClass('d-none');

        axios.post('/healthsync/auth/request-reset-password.php', {
          email: email
        }).then(res => {
          $('#forgot-success').removeClass('d-none').text(res.data.message);
        }).catch(err => {
          const msg = err.response?.data?.error || "Something went wrong.";
          $('#forgot-error').removeClass('d-none').text(msg);
        });
      });
    });
  </script>
</body>

</html>