<?php
require __DIR__ . '/../../utils/connection.php';
require __DIR__ . '/../../model/Appointment.php';
session_start();

if (!isset($_SESSION['auth-id'])) {
  header("Location: /healthsync");
  exit;
}

$userId = $_SESSION['auth-id'];
$id = $_GET['id'] ?? null;

if (!$id) {
  header("Location: /healthsync/users/medical-history.php");
  exit;
}

$appointmentModel = new Appointment($mysqli);
$appointment = $appointmentModel->findById($id);

if (!$appointment) {
  echo "Appointment not found.";
  exit;
}

function h($str)
{
  return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "View Appointment";
include __DIR__ . '/../../head.php';
?>

<body class="bg-light">
  <div class="container pt-4 pb-5">
    <?php include __DIR__ . '/../../header.php'; ?>

    <section class="mx-auto mt-5" style="max-width: 720px;">
      <div class="border-bottom pb-3 mb-4">
        <h2 class="fw-bold text-primary text-capitalize"><?= h($appointment['service']) ?></h2>
        <p class="text-muted mb-1"><span class="badge bg-primary"><?= strtoupper(h($appointment['type'])) ?></span></p>
        <p class="text-muted mb-0"><?= nl2br(h($appointment['description'])) ?></p>
      </div>

      <div class="row text-muted mb-4 gy-3">
        <div class="col-md-6">
          <div class="small fw-semibold">Date</div>
          <div><?= h($appointment['date']) ?></div>
        </div>
        <div class="col-md-6">
          <div class="small fw-semibold">Time</div>
          <div><?= date('h:i A', strtotime($appointment['time'])) ?></div>
        </div>
        <div class="col-md-6">
          <div class="small fw-semibold">Status</div>
          <div><span class="badge bg-secondary"><?= h($appointment['status']) ?></span></div>
        </div>
        <div class="col-md-6">
          <div class="small fw-semibold">Created At</div>
          <div><?= date('F j, Y g:i A', strtotime($appointment['created_at'])) ?></div>
        </div>
        <div class="col-md-6">
          <div class="small fw-semibold">Last Updated</div>
          <div><?= date('F j, Y g:i A', strtotime($appointment['updated_at'])) ?></div>
        </div>
        <div class="col-md-12">
          <div class="fw-semibold">Diagnosis</div>
          <div><?= htmlspecialchars($appointment['note']) ?></div>
        </div>
      </div>

      <?php if (!empty($appointment['lab_result_url'])): ?>
        <div class="mt-4">
          <h5 class="fw-semibold text-dark mb-2">Result</h5>
          <div class="p-3 border rounded bg-white d-flex justify-content-between align-items-center">
            <div class="me-3 text-truncate" style="max-width: 70%;">
              <i class="bi bi-file-earmark-text-fill me-2 text-primary"></i>
              <?= basename(h($appointment['lab_result_url'])) ?>
            </div>
            <a href="<?= h($appointment['lab_result_url']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
              View File
            </a>
          </div>
        </div>
      <?php endif; ?>

      <div class="d-flex justify-content-end mt-4">
        <a href="/healthsync/users/medical-history.php" class="btn btn-outline-primary">
          ← Back to Appointments
        </a>
      </div>
    </section>
  </div>
</body>

</html>