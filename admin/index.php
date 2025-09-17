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
  './assets/js/admin-navigation.js'
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
    <div class="d-flex w-100 min-vh-100">
      <nav class="d-flex flex-column align-items-center px-4 sidebar text-white">
        <div class="d-flex flex-row justify-content-center align-items-center my-4" style="gap: 1em;">
          <img src="./assets/logo-bg-remove.png" class="img-fluid" alt="Logo" style="max-height: 8em;">
          <img src="./assets/clinic-bg-remove.png" class="img-fluid" alt="" style="max-height: 8em;">
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

        <?php if ($roleId === 1): ?>
          <div class="w-100 mb-3">
            <p class="fw-bold small">User Management</p>

            <!-- Removed MCC from here -->

            <button class="custom-nav-down btn d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#userSubmenu" aria-expanded="false" aria-controls="userSubmenu">
              <span><i class="bi bi-person-gear me-2"></i> Manage Users</span>
              <i class="bi bi-chevron-down small"></i>
            </button>

            <div class="collapse mt-1" id="userSubmenu">
              <button class="custom-nav-link btn ps-5" data-target="verify-accounts">
                <i class="bi bi-shield-check me-2"></i> Account Verifications
              </button>
              <button class="custom-nav-link btn ps-5" data-target="patient-management">
                <i class="bi bi-person-lines-fill me-2"></i> Patient Management
              </button>
            </div>
          </div>

          <!-- New Records Section -->
          <div class="w-100 mb-3">
            <p class="fw-bold small">Records</p>
            <button class="custom-nav-link btn" data-target="monthly-chief-complaint">
              <i class="bi-exclamation-octagon me-2"></i> Monthly Chief Complaint
            </button>
            <button class="custom-nav-link btn" data-target="patient-chart">
              <i class="bi-file-medical me-2"></i> Patient Chart
            </button>
            <button class="custom-nav-link btn" data-target="medicine-release">
              <i class="bi-box-seam me-2"></i> Medicine Release
            </button>
            <button class="custom-nav-link btn" data-target="other-records">
              <i class="bi-folder me-2"></i> Other Records
            </button>
          </div>
        <?php endif; ?>

        <div class="w-100 mb-3">
          <p class="fw-bold small">Inventory</p>
          <button class="custom-nav-link btn" data-target="medical-supplies">Medical Suppplies</button>
          <button class="custom-nav-link btn" data-target="equipment-inventory">Equipments</button>
        </div>

        <div class="w-100 mb-3">
          <p class="fw-bold small">History</p>
          <button class="custom-nav-link btn" data-target="medical-history">Medical History</button>
        </div>

        <?php if ($roleId === 1): ?>
          <!-- <div class="w-100 mb-3">
            <p class="fw-bold small">Activity</p>
            <button class="custom-nav-link btn" data-target="activity-logs">Activity Logs</button>
          </div> -->
        <?php endif; ?>

        <form class="mt-auto w-100" method="POST" action="./auth/sign-out.php">
          <button type="submit" class="custom-nav-link btn mb-4">
            Sign-out
          </button>
        </form>
      </nav>

      <main class="flex-grow-1 overflow-auto p-4 bg-light">
        <?php include __DIR__ . '/../header.php' ?>
        <div class="loading-content loading-strip"></div>
        <div id="main-container"></div>
      </main>
    </div>
</body>

</html>
