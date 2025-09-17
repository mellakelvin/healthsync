<?php
if (
  !defined('IN_ADMIN_PANEL') &&
  (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest')
) {
  http_response_code(403);
  exit('403 Forbidden: Direct access not allowed.');
}

require_once __DIR__ . '/../utils/connection.php';
require_once __DIR__ . '/../model/Appointment.php';
require_once __DIR__ . '/../model/User.php';

$userModel = new User($mysqli);
$users = array_merge(
  $userModel->getUsersByRole(4),
  $userModel->getUsersByRole(5),
  $userModel->getUsersByRole(6)
);

$appointmentModel = new Appointment($mysqli);

$appointmentsByUserId = [];
foreach ($users as $user) {
  $appointmentsByUserId[$user['id']] = $appointmentModel->findByUser($user['id'], 'COMPLETED');
}
?>

<style>
  #appointments-table {
    border-collapse: collapse;
    width: 100%;
  }

  #appointments-table thead tr {
    border-bottom: 1px solid #dee2e6;
  }

  #appointments-table th,
  #appointments-table td {
    padding: 1rem;
    vertical-align: middle;
    background-color: transparent !important;
    border: none;
  }

  #appointments-table tr:hover {
    background-color: #f8f9fa;
    cursor: pointer;
  }

  .action-btn {
    margin-right: 0.5rem;
  }

  #appointment-details .badge {
    font-size: 0.75rem;
    padding: 0.4em 0.6em;
  }

  #appointment-details h4 {
    font-size: 1.25rem;
  }

  #appointment-details p {
    margin-bottom: 0.25rem;
  }

  #appointment-details .small {
    font-size: 0.8rem;
    font-weight: 500;
    color: #6c757d;
  }
</style>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-semibold mb-0">Medical History</h2>
    <input type="text" class="form-control w-auto" id="search-medical-history" placeholder="Search...">
  </div>


  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="appointments-table">
      <thead>
        <tr class="text-secondary fw-semibold">
          <th>ID Number</th>
          <th>Name</th>
          <th>Role</th>
          <th>Course / Department</th>
          <th>Medical History</th>
          <th>Last Visit</th>
          <th>Diagnosis</th>
        </tr>
      </thead>
      <tbody id="appointments-table-body">
        <?php foreach ($users as $user): ?>
          <?php
          $appointments = $appointmentsByUserId[$user['id']] ?? [];
          $lastAppointment = end($appointments);
          ?>
          <tr class="appointment-row" data-user-id="<?= $user['id'] ?>">
            <td><?= htmlspecialchars($user['id_number']) ?></td>
            <td><?= htmlspecialchars($user['first_name'] . " " . $user['middle_name'] . " " . $user['last_name']) ?></td>
            <td><?= htmlspecialchars(ucwords($user['role_name'])) ?></td>
            <td><?= htmlspecialchars($user['course_code'] ?? '') ?></td>
            <td><?= count($appointments) ?></td>
            <td><?= $lastAppointment ? htmlspecialchars($lastAppointment['date']) : '—' ?></td>
            <td><?= $lastAppointment ? htmlspecialchars($lastAppointment['service'] ?? '') : '—' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="appointmentModalLabel">History</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="appointmentModalBody">
          <p class="text-center text-muted">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    const appointmentsByUserId = <?= json_encode($appointmentsByUserId) ?>;
    $(document).ready(function() {
      $('.appointment-row').on('click', function() {
        const userId = $(this).data('user-id');
        const appointments = appointmentsByUserId[userId] || [];

        if (appointments.length === 0) {
          $('#appointmentModalBody').html('<p class="text-center text-muted">No appointments found.</p>');
          $('#appointmentModal').modal('show');
          return;
        }

        let content = '<ul class="list-group">';
        appointments.forEach(appt => {
          content += `
            <li class="list-group-item">
              <strong>${appt.date}</strong> — ${appt.service ?? 'No Service'}<br>
              <small class="text-muted">Status: ${appt.status}</small>
            </li>
          `;
        });
        content += '</ul>';

        $('#appointmentModalBody').html(content);
        $('#appointmentModal').modal('show');
      });
    });
    $('#search-medical-history').on('input', function() {
      const query = $(this).val().toLowerCase();
      $('#appointments-table-body tr').each(function() {
        const rowText = $(this).text().toLowerCase();
        $(this).toggle(rowText.includes(query));
      });
    });

  })();
</script>