<?php
require __DIR__ . '/utils/connection.php';

$token = $_GET['tid'] ?? null;

if (!$token) {
  http_response_code(400);
  echo "Invalid reset link.";
  exit;
}

$stmt = $mysqli->prepare("SELECT id FROM users WHERE password_reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  http_response_code(400);
  header('Location: /healthsync/');
}

$pageTitle = "Reset Your Password";
include './head.php';
?>

<!DOCTYPE html>
<html lang="en">

<body>
  <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center bg-light px-3">
    <div class="bg-white p-4 p-md-5 rounded shadow w-100" style="max-width: 500px;">
      <h2 class="text-center text-primary mb-4">Reset Password</h2>
      <p class="text-center text-muted mb-4">Enter your new password below.</p>

      <form id="reset-form" class="d-flex flex-column gap-3">
        <input type="hidden" name="token" id="reset-token" value="<?= htmlspecialchars($token) ?>">

        <div class="form-group position-relative d-flex align-items-center bg-secondary bg-opacity-10 rounded px-3" style="height: 60px;">
          <span class="input-group-text bg-transparent border-0">
            <i class="bi bi-lock-fill text-primary fs-5"></i>
          </span>
          <input type="password" class="form-control border-0 bg-transparent ms-3 shadow-none" name="password" id="password" placeholder="New Password" required>
        </div>

        <div class="form-group position-relative d-flex align-items-center bg-secondary bg-opacity-10 rounded px-3" style="height: 60px;">
          <span class="input-group-text bg-transparent border-0">
            <i class="bi bi-lock-fill text-primary fs-5"></i>
          </span>
          <input type="password" class="form-control border-0 bg-transparent ms-3 shadow-none" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required>
        </div>

        <div class="alert alert-danger d-none" id="reset-error">Passwords do not match</div>

        <button type="submit" class="btn btn-primary py-3 w-100">Reset Password</button>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#reset-form').submit(function(e) {
        e.preventDefault();
        const password = $('#password').val();
        const confirmPassword = $('#confirm-password').val();
        const token = $('#reset-token').val();

        if (password !== confirmPassword) {
          $('#reset-error').removeClass('d-none').text("Passwords do not match.");
          return;
        }

        $('#reset-error').addClass('d-none');

        axios.post('/healthsync/auth/reset-password.php', {
          token: token,
          password: password
        }).then(res => {
          alert('Password reset successful!');
          window.location.href = '/healthsync/';
        }).catch(err => {
          $('#reset-error').removeClass('d-none').text("Invalid or expired token.");
          console.error(err);
        });
      });
    });
  </script>
</body>

</html>