<?php
define('IN_ADMIN_PANEL', true);
?>
<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Admin";
$scripts = [
  'https://cdn.jsdelivr.net/npm/chart.js'
];
$deferScripts = [
  './assets/js/labtech-navigation.js'
];
include './head.php';
?>

<style>
  .sidebar {
    background-color: #004E64;
    max-width: 300px;
    width: 100%;
  }

  .custom-nav-link,
  .custom-nav-down {
    color: white;
    padding: 1rem;
    text-align: left;
    width: 100%;
    border-radius: 0.375rem;
    background-color: transparent;
    transition: background-color 0.2s;
  }

  .custom-nav-link:hover {
    background-color: rgba(255, 255, 255, 0.2);
  }

  .sidebar-divider {
    height: 2px;
    background-color: rgba(255, 255, 255, 0.3);
    width: 100%;
    margin: 1rem 0;
  }

  .custom-active {
    background-color: rgba(255, 255, 255, 0.2) !important;
    border-radius: 0.375rem;
  }
</style>

<body>
  <div class="d-flex w-100 min-vh-100">
    <nav class="d-flex flex-column align-items-center px-4 sidebar text-white">
      <div class="d-flex flex-row justify-content-center align-items-center my-4" style="gap: 1em;">
        <img src="./assets/logo-bg-remove.png" class="img-fluid" alt="Logo" style="max-height: 8em;">
        <img src="./assets/lab-bg-remove.png" class="img-fluid" alt="" style="max-height: 8em;">
      </div>
      <div class="sidebar-divider"></div>

      <div class="w-100 mb-3">
        <p class="fw-bold small">Main</p>
        <button class="custom-nav-link btn" data-target="dashboard">Dashboard</button>
      </div>

      <div class="w-100 mb-3">
        <p class="fw-bold small">Appointments</p>
        <button class="custom-nav-link btn" data-target="appointments">Appointments</button>
      </div>

      <div class="w-100 mb-3">
        <p class="fw-bold small">Inventory</p>
        <button class="custom-nav-link btn" data-target="lab-results">Lab results</button>
      </div>

      <form class="mt-auto w-100" method="POST" action="./auth/sign-out.php">
        <button type="submit" class="custom-nav-link btn mb-4">
          Sign-out
        </button>
      </form>
    </nav>

    <main class="flex-grow-1 overflow-auto p-4 bg-light">
      <?php include __DIR__ . '/../../header.php' ?>
      <div class="loading-content loading-strip"></div>
      <div id="main-container"></div>
    </main>
  </div>
</body>

</html>