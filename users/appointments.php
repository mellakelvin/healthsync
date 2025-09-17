<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Appointments";
include '../head.php';
require __DIR__ . '/../utils/connection.php';
require __DIR__ . '/../model/Appointment.php';

session_start();
$roleId = $_SESSION['role-id'] ?? null;
$userId = $_SESSION['auth-id'] ?? null;

$appointmentModel = new Appointment($mysqli);
$appointments = $appointmentModel->findByUser($userId);

function badgeClass($status)
{
  switch (strtoupper($status)) {
    case 'CONFIRMED':
      return 'success';
    case 'PENDING':
      return 'warning';
    case 'CANCELLED':
      return 'danger';
    case 'COMPLETED':
      return 'secondary';
    default:
      return 'dark';
  }
}
?>

<body>
  <div class="container pt-4 pb-5">
    <?php include __DIR__ . '/../header.php'; ?>
    <h1 class="mb-4 text-center fw-bold text-primary">Appointments</h1>
    <div id="appointments-container" class="row g-4">
      <?php if (empty(array_filter($appointments, fn($e) => $e['status'] === 'PENDING' || $e['status'] === 'CONFIRMED'))) : ?>
        <div class="text-center text-muted">No appointments found.</div>
      <?php else : ?>
        <?php foreach ($appointments as $appt) :
          if ($appt['status'] === 'CONFIRMED' || $appt['status'] === 'PENDING') :
            $badge = badgeClass($appt['status']);
        ?>
            <div class="col-md-6 col-lg-4">
              <div class="card shadow-sm border-0 rounded-4 p-4 d-flex flex-column h-100 bg-white">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h5 class="fw-semibold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($appt['service']) ?>">
                    <?= htmlspecialchars($appt['service']) ?>
                  </h5>
                  <span class="badge bg-primary text-uppercase"><?= htmlspecialchars($appt['type']) ?></span>
                </div>

                <p class="text-muted mb-2 text-truncate" title="<?= htmlspecialchars($appt['description']) ?>">
                  <?= htmlspecialchars($appt['description']) ?>
                </p>

                <div class="d-flex justify-content-between text-muted small mb-3">
                  <span><i class="bi bi-calendar-event"></i> <?= htmlspecialchars($appt['date']) ?></span>
                  <span><i class="bi bi-clock"></i> <?= htmlspecialchars($appt['time']) ?></span>
                </div>

                <div class="mb-3">
                  <span class="badge bg-<?= $badge ?> fw-semibold"><?= htmlspecialchars($appt['status']) ?></span>
                </div>

                <a href="/healthsync/users/appointment/view.php?id=<?= $appt['id'] ?>" class="btn btn-outline-primary w-100 mt-auto">
                  View Details
                </a>
              </div>
            </div>
        <?php
          endif;
        endforeach;
        ?>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>