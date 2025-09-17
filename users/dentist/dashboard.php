<?php
require __DIR__ . '/../../utils/connection.php';
require __DIR__ . '/../../model/Appointment.php';
require __DIR__ . '/../../model/User.php';

$appointmentModel = new Appointment($mysqli);
$userModel =  new User($mysqli);

$appointments = $appointmentModel->getAll('labtech');
$nurses = $userModel->getUsersByRole(3);
$labTechs = $userModel->getUsersByRole(2);
$students = $userModel->getUsersByRole(4);
$instructors = $userModel->getUsersByRole(5);
$faculty = $userModel->getUsersByRole(6);
?>
<div class="container mt-4">
  <div class="mb-4">
    <h2 class="fw-bold">DASHBOARD</h2>
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

    <div class="col-md-6">
      <div class="card py-3 px-3 h-100">
        <h5 class="card-title mb-3">Most Frequent Test Types</h5>
        <canvas id="testTypeChart"></canvas>
      </div>
    </div>

  </div>

  <div class="card py-3 px-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">New Appointment</h4>
      <input type="text" id="search-new-appointments" class="form-control w-25" placeholder="Search appointments...">
    </div>


    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="appointments-table">
        <thead>
          <tr class="text-secondary fw-semibold">
            <th>Name</th>
            <th>Service</th>
            <th>Status</th>
            <th>Scheduled</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="appointments-table-body"></tbody>
      </table>
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

  <div class="modal fade" id="statusConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-semibold">Confirm Action</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p id="status-confirm-message" class="mb-0">Are you sure?</p>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirm-status-btn">Yes, proceed</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {

    async function loadChartData(range, chart) {
      try {
        const response = await fetch(`/healthsync/api/appointment/stats.php?range=${range}&type=dentist`);
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

    function confirmStatusUpdate(id, status) {
      pendingStatusChange = {
        id,
        status
      };
      $('#status-confirm-message').text(`Are you sure you want to set this appointment to "${status}"?`);
      $('#statusConfirmModal').modal('show');
    }

    $('#confirm-status-btn').click(() => {
      axios.post('/healthsync/api/appointment/update-status.php', pendingStatusChange)
        .then(() => {
          $('#statusConfirmModal').modal('hide');
          fetchClinicAppointments($('#status-filter').val());
        })
        .catch(err => console.error('Status update failed:', err));
    });

    function createActionButtons(app) {
      const group = $('<div class="d-flex"></div>');
      if (app.status === 'PENDING') {
        group.append(`<button class="btn btn-sm btn-success me-1" data-action="CONFIRMED">Confirm</button>`);
      }
      if (app.status === 'CONFIRMED') {
        group.append(`<button class="btn btn-sm btn-primary me-1" data-action="COMPLETED">Complete</button>`);
      }
      if (app.status !== 'COMPLETED' && app.status !== 'CANCELLED') {
        group.append(`<button class="btn btn-sm btn-outline-danger" data-action="CANCELLED">Cancel</button>`);
      }
      group.find('button').click(function(e) {
        e.stopPropagation();
        confirmStatusUpdate(app.id, $(this).data('action'));
      });
      return group;
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

    function fetchClinicAppointments() {
      axios.post('/healthsync/api/appointment/all-appointments.php', {
          type: 'dentist',
          status: 'PENDING'
        })
        .then(res => {
          const tbody = $('#appointments-table-body');
          tbody.empty();
          (res.data || []).forEach(app => {
            const tr = $('<tr style="cursor:pointer;"></tr>');
            tr.append(`<td>${app.first_name} ${app.last_name}</td>`);
            tr.append(`<td>${app.service}</td>`);
            tr.append(`<td><span class="badge bg-${app.status === 'PENDING' ? 'warning text-dark' : app.status === 'CONFIRMED' ? 'primary' : app.status === 'COMPLETED' ? 'success' : 'secondary'}">${app.status}</span></td>`);
            tr.append(`<td>${app.date} ${app.time}</td>`);
            tr.append($('<td></td>').append(createActionButtons(app)));
            tr.click(() => {
              console.log('Clicked appointment:', app);
              $('#appointment-details').html(formatAppointmentDetails(app));
              $('#appointmentModal').modal('show');
            });
            tbody.append(tr);
          });
        })
        .catch(err => console.error("Error loading appointments:", err));
    }

    function loadTestTypeChart(type = null) {
      axios.get('/healthsync/api/appointment/test-type-stats.php?type=dentist')
        .then(function(response) {
          const res = response.data;
          const ctx = $('#testTypeChart')[0].getContext('2d');

          window.testTypeChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: res.labels,
              datasets: [{
                label: 'Number of Tests',
                data: res.data,
                backgroundColor: '#0d6efd'
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    stepSize: 1
                  }
                }
              }
            }
          });
        })
        .catch(function(error) {
          console.error('Error loading test type chart:', error);
        });
    }
    loadTestTypeChart('dentist');
    $('#search-new-appointments').on('input', function() {
      const query = $(this).val().toLowerCase();
      $('#appointments-table-body tr').each(function() {
        const rowText = $(this).text().toLowerCase();
        $(this).toggle(rowText.includes(query));
      });
    });

    $(document).ready(() => {
      fetchClinicAppointments();
    });
  })();
</script>