<?php
if (
  !defined('IN_ADMIN_PANEL') &&
  (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest')
) {
  http_response_code(403);
  exit('403 Forbidden: Direct access not allowed.');
}
$appointmentId = $_GET['id'] ?? null;
?>

<style>
  #appointments-table {
    border-collapse: collapse;
    width: 100%;
  }

  .table-responsive {
    height: 75vh;
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

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-semibold mb-0">Dentist Appointments</h2>
    <div class="d-flex">
      <input class="me-2" type="search" name="search" id="search-field" placeholder="Search">
      <select id="status-filter" class="form-select w-auto">
        <option value="">All Status</option>
        <option value="PENDING">Pending</option>
        <option value="CONFIRMED">Confirmed</option>
        <option value="COMPLETED">Completed</option>
        <option value="CANCELLED">Cancelled</option>
      </select>
      <button type="button" class="ms-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Generate report
      </button>
    </div>
  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Generate Report</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="statusSelect" class="form-label">Status</label>
            <select class="form-select" id="statusSelect">
              <option>All</option>
              <option value="PENDING">Pending</option>
              <option value="COMPLETED">Completed</option>
              <option value="CANCELLED">Cancelled</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="roleSelect" class="form-label">User Role</label>
            <select class="form-select" id="roleSelect">
              <option>All</option>
              <option value="4">Student</option>
              <option value="5">Employee Teaching</option>
              <option value="6">Employee Non-Teaching</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="presetRange" class="form-label">Preset Range</label>
            <select class="form-select" id="presetRange">
              <option disabled selected>Select preset</option>
              <option value="this_month">This Month</option>
              <option value="last_month">Last Month</option>
              <option value="this_year">This Year</option>
            </select>
          </div>

          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="enableCustomRange">
            <label class="form-check-label" for="enableCustomRange">Enable Custom Date Range</label>
          </div>

          <div id="customRangeFields" class="row g-2 d-none">
            <div class="col-md-6">
              <label for="startDate" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="startDate">
            </div>
            <div class="col-md-6">
              <label for="endDate" class="form-label">End Date</label>
              <input type="date" class="form-control" id="endDate">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button id="generate-report" type="button" class="btn btn-primary">Generate</button>
        </div>
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="appointments-table">
      <thead>
        <tr class="text-secondary fw-semibold">
          <th>Service</th>
          <th>Description</th>
          <th>Receipt</th>
          <th>Status</th>
          <th>Scheduled At</th>
          <th>Created</th>
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
          <label for="note" class="form-label fw-semibold">Diagnostics</label>
          <input type="text" id="note" name="note" class="form-control rounded-3 shadow-sm" placeholder="Enter your diagnostics">
        </div>

        <div id="file-upload-group" class="mt-3 d-none">
          <label for="lab-result-file" class="form-label">Attach Result File</label>
          <input type="file" class="form-control" id="lab-result-file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirm-status-btn">Yes, proceed</button>
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
<script>
  (function() {

    let startDate = null;
    let endDate = null;
    let presetDate = null;
    let role = null;
    let temp = null;

    $('#enableCustomRange').on('change', function() {
      $('#customRangeFields').toggleClass('d-none', !this.checked);
      $('#presetRange').prop('disabled', this.checked);

      if (this.checked) {
        startDate = $('#startDate').val();
        endDate = $('#endDate').val();
        temp = presetDate;
        presetDate = null;
      } else {
        presetDate = temp;
      }
    });

    $('#presetRange').on('change', function() {
      presetDate = $(this).val()
      console.log(presetDate)
    })

    $('#roleSelect').on('change', function(e) {
      role = e.target.value;
    });

    $('#generate-report').on('click', function() {
      let strReport = ""
      strReport = strReport.concat(`preset=${presetDate}&`);
      strReport = strReport.concat(`status=${$('#statusSelect').val()}&`);
      strReport = strReport.concat(`start_date=${presetDate == null ? $('#startDate').val() : null}&`);
      strReport = strReport.concat(`end_date=${presetDate == null ? $('#endDate').val() : null}&`);
      strReport = strReport.concat(`role=${role}`)
      window.open(`/healthsync/api/appointment/generate-report.php?type=dentist&${strReport}`, '_blank');
    })

    let pendingStatusChange = {
      id: null,
      status: null,
    };

    function formatAppointmentDetails(app) {
      const isStudent = app.role === 4;
      return `
        <div class="border-bottom pb-3 mb-4">
          <h4 class="fw-bold text-primary text-capitalize mb-1">${app.service}</h4>
          <span class="badge bg-primary text-white text-uppercase small mb-2">${app.type}</span>
          <p class="text-muted mb-0">${app.description || '<em>No description</em>'}</p>
        </div>

        <div class="border rounded p-3 mb-4 bg-light-subtle">
          <div class="row gy-2 text-muted">
            <div class="col-md-6"><div class="small fw-semibold">Full Name</div><div>${app.full_name}</div></div>
            <div class="col-md-6"><div class="small fw-semibold">ID Number</div><div>${app.id_number}</div></div>
            <div class="col-md-6"><div class="small fw-semibold">Role</div><div>${isStudent ? 'Student' : 'Employee'}</div></div>
            ${isStudent ? `
              <div class="col-md-6"><div class="small fw-semibold">Year Level</div><div>${app.year}</div></div>
            ` : ''}

              <div class="col-md-6"><div class="small fw-semibold">${isStudent ? 'Course' : 'Department'}</div><div>${app.course_name || '—'}</div></div>
          </div>
        <div class="mt-4">
          <h5 class="fw-semibold text-dark mb-2">Official Receipt</h5>
          <div class="p-3 border rounded bg-white d-flex justify-content-between align-items-center">
            <div class="me-3 text-truncate" style="max-width: 70%;">
              <i class="bi bi-file-earmark-text-fill me-2 text-primary"></i>
                ${app.receipt_url.split('/').pop()}
            </div>
            <a href="${app.receipt_url}" target="_blank" class="btn btn-sm btn-outline-primary">
              View File
            </a>
          </div>
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
          <div class="col-md-6"><div class="small">Date</div><div>${app.date}</div></div>
          <div class="col-md-6"><div class="small">Time</div><div>${formatTime(app.time)}</div></div>
          <div class="col-md-6"><div class="small">Status</div><div><span class="badge bg-${getStatusColor(app.status)}">${app.status}</span></div></div>
          <div class="col-md-6"><div class="small">Created At</div><div>${formatFullDate(app.created_at)}</div></div>
          <div class="col-md-6"><div class="small">Updated At</div><div>${formatFullDate(app.updated_at)}</div></div>
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

    function confirmStatusUpdate(id, status, note) {
      pendingStatusChange = {
        id,
        status,
        note
      };
      $('#status-confirm-message').text(`Are you sure you want to set this appointment to "${status}"?`);
      if (status === 'COMPLETED') {
        $('#file-upload-group').removeClass('d-none');
        $('#note-group').removeClass('d-none');
      } else {
        $('#note-group').addClass('d-none');
        $('#file-upload-group').addClass('d-none');
      }
      $('#statusConfirmModal').modal('show');
    }

    $('#confirm-status-btn').click((e) => {
      e.preventDefault()

      const formData = new FormData();
      formData.append('id', pendingStatusChange.id);
      formData.append('status', pendingStatusChange.status);
      formData.append('note', $('#note').val());

      const file = $('#lab-result-file')[0].files[0];

      if (pendingStatusChange.status === 'COMPLETED' && $('#note').val().trim() == "") {
        $('#warning').text("Missing diagnostics")
        $('#warning').removeClass('d-none');
        return;
      }
      if (pendingStatusChange.status === 'COMPLETED' && !file) {
        $('#warning').text("Missing lab result")
        $('#warning').removeClass('d-none');
        return;
      }
      if (pendingStatusChange.status === 'COMPLETED' && file) {
        formData.append('file', file);
        $('#warning').text("")
        $('#warning').addClass('d-none');
      }
      axios.post('/healthsync/api/appointment/update-status.php', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(() => {
        const toast = new bootstrap.Toast(document.getElementById('success-toast'));
        $('.toast-body').text(`Appointment ${pendingStatusChange.status.toLowerCase()} successfully`);
        toast.show();
        $('#statusConfirmModal').modal('hide');
        fetchClinicAppointments($('#status-filter').val());
      }).catch(err => {
        console.error('Status update failed:', err);
        alert("Failed to update appointment.");
      });
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
        confirmStatusUpdate(app.id, $(this).data('action'), $('#note').val());
      });

      return group;
    }

    function fetchClinicAppointments(status = '') {
      axios.post('/healthsync/api/appointment/all-appointments.php', {
        type: 'dentist',
        status: status
      }).then(res => {
        const tbody = $('#appointments-table-body').empty();
        (res.data || []).forEach(app => {
          const tr = $('<tr style="cursor:pointer;"></tr>');
          tr.append(`<td class="py-3">${app.service}</td>`);
          tr.append(`<td class="py-3 text-muted">${app.description}</td>`);
          tr.append(`<td class="py-3 text-muted"><a href="${app.receipt_url}" target="_blank">Receipt</a></td>`);
          tr.append(`<td class="py-3"><span class="badge bg-${getStatusColor(app.status)}">${app.status}</span></td>`);
          tr.append(`<td class="py-3">${app.date} ${app.time}</td>`);
          tr.append(`<td class="py-3">${app.created_at}</td>`);
          tr.append($('<td class="py-3"></td>').append(createActionButtons(app)));

          tr.on('click', () => {
            $('#appointment-details').html(formatAppointmentDetails(app));
            $('#appointmentModal').modal('show');
          });

          tbody.append(tr);
        });
      }).catch(err => {
        console.error("Error loading lab appointments:", err);
      });
    }

    $(document).ready(() => {
      const appointmentId = <?= json_encode($appointmentId) ?>;
      fetchClinicAppointments();

      $('#status-filter').on('change', function() {
        fetchClinicAppointments($(this).val());
      });

      axios.post('/healthsync/api/appointment/all-appointments.php', {
        type: 'dentist',
        status: ''
      }).then(res => {
        const tbody = $('#appointments-table-body').empty();
        const appointments = res.data || [];

        appointments.forEach(app => {
          const tr = $('<tr style="cursor:pointer;"></tr>');
          tr.append(`<td class="py-3"><p class="mb-0 text-truncate text-nowrap" style="max-width: 150px;">${app.service}</p></td>`);
          tr.append(`<td class="py-3"><p class="mb-0 text-muted text-truncate text-nowrap" style="max-width: 200px;">${app.description}</p></td>`);
          tr.append(`<td class="py-3"><span class="badge bg-${getStatusColor(app.status)}">${app.status}</span></td>`);
          tr.append(`<td class="py-3"><p class="mb-0 text-truncate text-nowrap" style="max-width: 180px;">${app.date} ${app.time}</p></td>`);
          tr.append(`<td class="py-3"><p class="mb-0 text-truncate text-nowrap" style="max-width: 180px;">${app.created_at}</p></td>`);
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
  })();
</script>