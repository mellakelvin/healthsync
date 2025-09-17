<?php
if (
  !defined('IN_ADMIN_PANEL') &&
  (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest')
) {
  http_response_code(403);
  exit('403 Forbidden: Direct access not allowed.');
}
require __DIR__ . '/../utils/connection.php';
require __DIR__ . '/../model/Appointment.php';
require __DIR__ . '/../model/User.php';

$appointmentModel = new Appointment($mysqli);
$userModel =  new User($mysqli);

$appointments = $appointmentModel->getAll('clinic');

date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d');

$todayAppointments = array_filter($appointments, function ($appointment) use ($today) {
    return isset($appointment['date']) && $appointment['date'] === $today;
});
$todayAppointments = array_values($todayAppointments);

$nurses = $userModel->getUsersByRole(3, 'ACTIVE');
$labTechs = $userModel->getUsersByRole(2, 'ACTIVE');
$students = $userModel->getUsersByRole(4, 'ACTIVE');
$instructors = $userModel->getUsersByRole(5, 'ACTIVE');
$faculty = $userModel->getUsersByRole(6, 'ACTIVE');
?>
<main class="container mt-4">
  <style>
    #appointments-table {
      border-collapse: collapse;
      width: 100%;
    }

    .table-responsive {
      height: 25em;
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
  <div class="row mb-4 justify-content-center">
    <div class="col-md-3">
      <div class="card text-white" style="background-color: purple;">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title">Today's Appointments</h5>
            <p class="fs-4"><?= count($todayAppointments) ?></p>
          </div>
          <i class="fas fa-calendar-check fa-3x"></i>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-primary">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title">Total Appointments</h5>
            <p class="fs-4"><?= count($appointments) ?></p>
          </div>
          <i class="fas fa-calendar-check fa-3x"></i>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-success">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title">Patients</h5>
            <p class="fs-4"><?= (count($students) + count($instructors) + count($faculty)) ?></p>
          </div>
          <i class="fas fa-user-injured fa-3x"></i>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-info">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title">Active Nurses</h5>
            <p class="fs-4"><?= count($nurses) ?></p>
          </div>
          <i class="fas fa-user-nurse fa-3x"></i>
        </div>
      </div>
    </div>

  </div>

  <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content rounded-4">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-4 pt-2 pb-4">
          <div id="appointment-details"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Appointment stats</h5>

          <ul class="nav nav-tabs mb-3" id="appointmentTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily" type="button" role="tab">Daily</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="weekly-tab" data-bs-toggle="tab" data-bs-target="#weekly" type="button" role="tab">Weekly</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab">Monthly</button>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane fade show active" id="daily" role="tabpanel">
              <div style="position: relative; height:40vh; width:100%;">
                <canvas id="dailyChart"></canvas>
              </div>
            </div>
            <div class="tab-pane fade" id="weekly" role="tabpanel">
              <div style="position: relative; height:40vh; width:100%;">
                <canvas id="weeklyChart"></canvas>
              </div>
            </div>
            <div class="tab-pane fade" id="monthly" role="tabpanel">
              <div style="position: relative; height:40vh; width:100%;">
                <canvas id="monthlyChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Appointments</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="appointments-table">
              <thead>
                <tr class="text-secondary fw-semibold">
                  <th>Service</th>
                  <th>Status</th>
                  <th>Scheduled At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="appointments-table-body">
                <!-- dynamic content -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="success-toast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Appointment booked successfully!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <div class="modal fade" id="statusConfirmModal" tabindex="-1" aria-labelledby="statusConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 shadow-sm">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-semibold" id="statusConfirmLabel">Confirm Action</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger d-none" id="warning">
          </div>
          <p id="status-confirm-message" class="mb-0">Are you sure you want to update the appointment status?</p>
          <div id="note-group" class="mt-3">
            <label for="note" class="form-label fw-semibold">Diagnosis</label>
            <input type="text" id="note" name="note" class="form-control rounded-3 shadow-sm" placeholder="Enter your diagnosis here">
          </div>
        </div>


        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirm-status-btn">Yes, proceed</button>
        </div>
      </div>
    </div>
  </div>
  <div class="mb-4">
    <?php
    include __DIR__ . '/medical-supplies.php';
    include __DIR__ . '/patient-management.php';

    ?>
  </div>

</main>
<script>
  (function() {


    async function loadChartData(range, chart) {
      try {
        const response = await fetch(`/healthsync/api/appointment/stats.php?range=${range}&type=clinic`);
        const result = await response.json();

        chart.data.labels = result.labels;
        chart.data.datasets[0].data = result.data;
        chart.update();
      } catch (error) {
        console.error("Failed to load chart data:", error);
      }
    }

    const dailyChart = new Chart(document.getElementById('dailyChart'), {
      type: 'bar',
      data: {
        labels: [],
        datasets: [{
          label: 'Appointments',
          data: [],
          backgroundColor: '#0d6efd'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });

    const weeklyChart = new Chart(document.getElementById('weeklyChart'), {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
          label: 'Appointments',
          data: [],
          borderColor: '#198754',
          backgroundColor: 'rgba(25,135,84,0.2)',
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });

    const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
      type: 'bar',
      data: {
        labels: [],
        datasets: [{
          label: 'Appointments',
          data: [],
          backgroundColor: '#dc3545'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });

    loadChartData('daily', dailyChart);
    loadChartData('weekly', weeklyChart);
    loadChartData('monthly', monthlyChart);

    let pendingStatusChange = {
      id: null,
      status: null
    };

    function formatAppointmentDetails(app) {
      const isStudent = app.role === 4;
      return `
    <div class="border-bottom pb-3 mb-4">
      <h4 class="fw-bold text-primary text-capitalize mb-1">${app.service}</h4>
      <span class="badge bg-primary text-uppercase small mb-2">${app.type}</span>
      <p class="text-muted mb-0">${app.description || '<em>No description</em>'}</p>
    </div>

    <div class="border rounded p-3 mb-4 bg-light-subtle">
      <div class="row gy-2 text-muted">
        <div class="col-md-6">
          <div class="small fw-semibold">Full Name</div>
          <div>${app.full_name}</div>
        </div>
        <div class="col-md-6">
          <div class="small fw-semibold">ID Number</div>
          <div>${app.id_number}</div>
        </div>
        <div class="col-md-6">
          <div class="small fw-semibold">Role</div>
          <div>${isStudent ? 'Student' : 'Employee'}</div>
        </div>
        ${isStudent ? `
          <div class="col-md-6">
            <div class="small fw-semibold">Year Level</div>
            <div>${app.year}</div>
          </div>
          <div class="col-md-6">
            <div class="small fw-semibold">Course</div>
            <div>${app.course_code || '—'}</div>
          </div>
        ` : ''}
      </div>
    </div>

    <div class="row text-muted mb-3 gy-3">
    ${app.note != null && app.note.trim() !== ''
        ? `
        <div class="col-md-12">
          <div class="small">Diagnosis</div>
          <div>${app.note}</div>
        </div>
        ` : ''
      }
      <div class="col-md-6">
        <div class="small">Date</div>
        <div>${app.date}</div>
      </div>
      <div class="col-md-6">
        <div class="small">Time</div>
        <div>${formatTime(app.time)}</div>
      </div>
      <div class="col-md-6">
        <div class="small">Status</div>
        <div><span class="badge bg-${getStatusColor(app.status)}">${app.status}</span></div>
      </div>
      <div class="col-md-6">
        <div class="small">Created At</div>
        <div>${formatFullDate(app.created_at)}</div>
      </div>
      <div class="col-md-6">
        <div class="small">Updated At</div>
        <div>${formatFullDate(app.updated_at)}</div>
      </div>
    </div>
  `;
    }


    function getStatusColor(status) {
      switch (status) {
        case 'PENDING':
          return 'warning text-dark';
        case 'CONFIRMED':
          return 'primary';
        case 'COMPLETED':
          return 'success';
        case 'CANCELLED':
          return 'secondary';
        default:
          return 'light';
      }
    }

    function formatTime(timeStr) {
      const date = new Date(`1970-01-01T${timeStr}Z`);
      return date.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
      });
    }

    function formatFullDate(dateStr) {
      const date = new Date(dateStr);
      return date.toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
      });
    }

    function confirmStatusUpdate(id, status) {
      pendingStatusChange = {
        id,
        status
      };
      const label = status.charAt(0) + status.slice(1).toLowerCase();
      $('#status-confirm-message').text(`Are you sure you want to set this appointment to "${label}"?`);
      if (status === 'COMPLETED') {
        $('#note-group').removeClass('d-none');
      } else {
        $('#note-group').addClass('d-none');
      }
      $('#statusConfirmModal').modal('show');
    }

    $('#confirm-status-btn').click(() => {
      $('#warning').addClass('d-none');
      if (pendingStatusChange.status == "COMPLETED" && $('#note').val().trim() == "") {
        $('#warning').text('Missing diagnostics')
        $('#warning').removeClass('d-none');
        return;
      }
      axios.post('/healthsync/api/appointment/update-status.php', {
          ...pendingStatusChange,
          note: $('#note').val()
        })
        .then(() => {
          const toast = new bootstrap.Toast(document.getElementById('success-toast'));
          $('.toast-body').text(`Appointment ${pendingStatusChange.status.toLowerCase()} successfully`);
          toast.show();
          $('#statusConfirmModal').modal('hide');
          fetchClinicAppointments($('#status-filter').val());
        })
        .catch(err => console.error('Status update failed:', err));
    });

    function createActionButtons(app) {
      const group = $('<div class="d-flex"></div>');
      if (app.status === 'PENDING') {
        group.append(`<button class="btn btn-sm btn-success action-btn" data-action="CONFIRMED">Confirm</button>`);
      }
      if (app.status === 'CONFIRMED') {
        group.append(`<button class="btn btn-sm btn-primary action-btn" data-action="COMPLETED">Complete</button>`);
      }
      if (!['COMPLETED', 'CANCELLED'].includes(app.status)) {
        group.append(`<button class="btn btn-sm btn-outline-danger action-btn" data-action="CANCELLED">Cancel</button>`);
      }

      group.find('button').click(function(e) {
        e.stopPropagation();
        confirmStatusUpdate(app.id, $(this).data('action'));
      });

      return group;
    }

    function fetchClinicAppointments(status = '') {
      axios.post('/healthsync/api/appointment/all-appointments.php', {
        type: 'clinic',
        status: 'PENDING'
      }).then(res => {
        const tbody = $('#appointments-table-body').empty();
        (res.data || []).forEach(app => {
          const tr = $('<tr style="cursor:pointer;"></tr>');
          tr.append(`<td class="py-3"><p class="mb-0 text-truncate text-nowrap" style="max-width: 150px;">${app.service}</p></td>`);
          tr.append(`<td class="py-3"><span class="badge bg-${getStatusColor(app.status)}">${app.status}</span></td>`);
          tr.append(`<td class="py-3"><p class="mb-0 text-truncate text-nowrap" style="max-width: 180px;">${app.date} ${app.time}</p></td>`);
          tr.append($('<td class="py-3"></td>').append(createActionButtons(app)));

          tr.on('click', () => {
            $('#appointment-details').html(formatAppointmentDetails(app));
            $('#appointmentModal').modal('show');
          });

          tbody.append(tr);
        });
      }).catch(err => console.error("Error loading clinic appointments:", err));
    }

    $(document).ready(() => {
      const appointmentId = <?= json_encode($appointmentId) ?>;
      fetchClinicAppointments();

      $('#status-filter').on('change', function() {
        fetchClinicAppointments($(this).val());
      });

      axios.post('/healthsync/api/appointment/all-appointments.php', {
        type: 'clinic',
        status: 'PENDING'
      }).then(res => {
        const tbody = $('#appointments-table-body').empty();
        const appointments = res.data || [];

        appointments.forEach(app => {
          const tr = $('<tr style="cursor:pointer;"></tr>');
          tr.append(`<td class="py-3"><p class="mb-0 text-truncate text-nowrap" style="max-width: 150px;">${app.service}</p></td>`);
          tr.append(`<td class="py-3"><span class="badge bg-${getStatusColor(app.status)}">${app.status}</span></td>`);
          tr.append(`<td class="py-3"><p class="mb-0 text-truncate text-nowrap" style="max-width: 180px;">${app.date} ${app.time}</p></td>`);
          tr.append($('<td class="py-3"></td>').append(createActionButtons(app)));

          tr.on('click', () => {
            $('#appointment-details').html(formatAppointmentDetails(app));
            $('#appointmentModal').modal('show');
          });

          tbody.append(tr);
        });

        if (appointmentId) {
          const match = appointments.find(app => String(app.id) === appointmentId);
          if (match) {
            const html = formatAppointmentDetails(match);
            $('#appointment-details').html(html);
            $('#appointmentModal').modal('show');
          }
        }

      }).catch(err => console.error("Error loading clinic appointments:", err));
    });
    $('#search-field').on('keyup', function() {
      const query = $(this).val().toLowerCase();
      $('#appointments-table-body tr').each(function() {
        const rowText = $(this).text().toLowerCase();
        $(this).toggle(rowText.includes(query));
      });
    });
  })()
</script>