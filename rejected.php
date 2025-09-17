<?php
session_start();

if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("Location: /healthsync");
  exit;
}

require __DIR__ . '/utils/connection.php';
require __DIR__ . '/model/User.php';

$userModel = new User($mysqli);
$user = $userModel->findById((int)$_SESSION['auth-id']);
?>

<!DOCTYPE html>
<html lang="en">
<?php $pageTitle = "HealthSync - Account Rejected";
include 'head.php'; ?>

<body class="bg-light">
  <div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
    <div class="card shadow rounded-4 p-5 text-center" style="max-width: 500px;">
      <div class="mb-4">
        <img src="/healthsync/assets/logo_small.png" alt="HealthSync Logo" style="height: 80px;">
      </div>
      <h3 class="mb-3 text-danger fw-bold">Account Rejected</h3>
      <p class="text-muted">
        Sorry, <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>,
        <br>Your account registration has been rejected.
      </p>
      <p class="text-secondary small">
        If you believe this is a mistake or wish to appeal, please contact the administrator at
        <a href="mailto:admin@healthsync.com">admin@healthsync.com</a>.
      </p>
      <div class="mt-4">
        <a href="?logout=true" class="btn btn-outline-secondary">Sign Out</a>
      </div>
    </div>
  </div>
</body>
</html>
