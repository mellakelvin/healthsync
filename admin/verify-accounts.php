<?php
if (
  !defined('IN_ADMIN_PANEL') &&
  (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest')
) {
  http_response_code(403);
  exit('403 Forbidden: Direct access not allowed.');
}
?>

<style>
  .nav-link.active {
    background-color: transparent !important;
    border-bottom: 2px solid #f4f4f4 !important;
  }

  #users-table {
    border-collapse: collapse;
    width: 100%;
  }

  #users-table thead tr {
    border-bottom: 1px solid #dee2e6;
  }

  #users-table th,
  #users-table td {
    padding: 1rem;
    vertical-align: middle;
    background-color: transparent !important;
    border: none;
  }

  #users-table tr:hover {
    background-color: #f8f9fa;
  }
</style>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-semibold mb-0">Account Verifications</h2>
    <input type="text" id="userSearchInput" class="form-control w-25" placeholder="Search by name, ID, email...">
  </div>

  <ul class="nav nav-tabs mb-3" id="userTabs">
    <li class="nav-item">
      <button class="nav-link active" data-status="PENDING">Pending</button>
    </li>
    <li class="nav-item">
      <button class="nav-link" data-status="REJECTED">Rejected</button>
    </li>
  </ul>

  <h4 class="mt-4">Students</h4>
  <div class="table-responsive mb-5">
    <table id="users-table" class="table table-hover table-borderless align-middle" id="student-table" style="font-size: 0.95rem;">
      <thead class="border-bottom">
        <tr class="text-secondary">
          <th>Name</th>
          <th>ID Number</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Course & Year</th>
          <th>Gender</th>
          <th>Status</th>
          <th>Registered</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="student-table-body"></tbody>
    </table>
  </div>

  <h4>Employees</h4>
  <div class="table-responsive">
    <table id='users-table' class="table table-hover table-borderless align-middle" id="employee-table" style="font-size: 0.95rem;">
      <thead class="border-bottom">
        <tr class="text-secondary">
          <th>Name</th>
          <th>ID Number</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Department</th>
          <th>Gender</th>
          <th>Status</th>
          <th>Registered</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="employee-table-body"></tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="verifyModalLabel">Account Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3" id="modal-user-details"></div>
      </div>
      <div class="modal-footer border-0">
        <button class="btn btn-outline-danger" id="reject-btn">Reject</button>
        <button class="btn btn-primary" id="accept-btn">Accept</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmActionModalLabel">Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to <span id="confirmActionText" class="fw-bold text-uppercase"></span> this account?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="confirmActionBtn" type="button" class="btn">Confirm</button>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    let selectedUserId = null;
    let selectedUser = null;
    let currentStatus = "PENDING";
    let confirmAction = null;
    let confirmUserId = null;

    function formatUserDetails(user) {
      const fullName = `${user.first_name} ${user.middle_name} ${user.last_name}`;
      return `
        <div class="col-md-6"><strong>Full Name</strong><p>${fullName}</p></div>
        <div class="col-md-6"><strong>ID Number</strong><p>${user.id_number}</p></div>
        <div class="col-md-6"><strong>Email</strong><p>${user.email_address}</p></div>
        <div class="col-md-6"><strong>Phone</strong><p>${user.phone_number}</p></div>
        <div class="col-md-6">
          <strong>${(user.role == 5 || user.role == 6) ? 'Department' : 'Course & Year'}</strong>
          <p>${(user.role == 5 || user.role == 6) ? user.course_name : user.course_code}${user.year_name ? " - " + user.year_name : ""}</p>
        </div>
        <div class="col-md-6"><strong>Gender</strong><p>${user.gender}</p></div>
        <div class="col-md-6"><strong>Address</strong><p>${user.address}</p></div>
        <div class="col-md-6"><strong>Status</strong><p><span class="badge bg-${getStatusColor(user.status)}">${user.status}</span></p></div>
        <div class="col-md-6"><strong>Role</strong><p>${user.role_name[0].toUpperCase() + user.role_name.slice(1)}</p></div>
        <div class="col-md-6"><strong>Created</strong><p>${user.created_at}</p></div>
        <div class="col-md-6"><strong>Updated</strong><p>${user.updated_at}</p></div>
      `;
    }

    function showConfirmationModal(action, userId) {
      confirmAction = action;
      confirmUserId = userId;
      const actionText = action === 'accept' ? 'accept' : 'reject';
      const btnClass = action === 'accept' ? 'btn-primary' : 'btn-danger';

      $('#confirmActionText').text(actionText);
      $('#confirmActionBtn')
        .text('Confirm')
        .removeClass('btn-primary btn-danger')
        .addClass(btnClass);

      $('#confirmActionModal').modal('show');
    }

    $('#confirmActionBtn').on('click', function() {
      if (!confirmUserId || !confirmAction) return;

      axios.post('/healthsync/api/user/verify-action.php', {
        id: confirmUserId,
        action: confirmAction
      }).then(() => {
        $('#confirmActionModal').modal('hide');
        $('#verifyModal').modal('hide');
        fetchUsers(currentStatus);
      }).catch(err => {
        console.error('Failed to perform action:', err);
      });
    });

    function getStatusColor(status) {
      switch (status) {
        case "PENDING":
          return "warning text-dark";
        case "REJECTED":
          return "danger";
        case "ACTIVE":
          return "success";
        default:
          return "secondary";
      }
    }

    function fetchUsers(status) {
      axios.post('/healthsync/api/user/users.php', {
          status
        })
        .then(res => {
          const users = res.data;
          const studentBody = $('#student-table-body');
          const employeeBody = $('#employee-table-body');
          studentBody.empty();
          employeeBody.empty();

          users.forEach(user => {
            const fullName = `${user.first_name} ${user.middle_name} ${user.last_name}`;
            const row = $(`
              <tr class="view">
                <td class="py-3">${fullName}</td>
                <td class="py-3">${user.id_number}</td>
                <td class="py-3">${user.email_address}</td>
                <td class="py-3">${user.phone_number}</td>
                <td class="py-3">${(user.role == 5 || user.role == 6) ? user.course_name : user.course_code}${user.year_name ? " - " + user.year_name : ""}</td>
                <td class="py-3">${user.gender}</td>
                <td class="py-3"><span class="badge bg-${getStatusColor(user.status)}">${user.status}</span></td>
                <td class="py-3">${user.created_at}</td>
                <td class="py-3">
                  <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-primary me-2 accept-btn">Accept</button>
                    <button class="btn btn-sm btn-danger ms-2 reject-btn">Reject</button>
                  </div>
                </td>
              </tr>
            `);

            row.on('click', function(e) {
              console.log(selectedUser)
              e.stopPropagation();
              selectedUserId = user.id;
              selectedUser = user;
              $('#modal-user-details').html(formatUserDetails(user));
              $('#verifyModal').modal('show');
            });

            row.find('.accept-btn').on('click', function(e) {
              e.stopPropagation();
              showConfirmationModal('accept', user.id)
            });

            row.find('.reject-btn').on('click', function(e) {
              e.stopPropagation();
              showConfirmationModal('reject', user.id)
            });

            if (user.role == 4) {
              studentBody.append(row);
            } else if (user.role == 5 || user.role == 6) {
              employeeBody.append(row);
            }
          });
        })
        .catch(err => {
          console.error('Failed to fetch users:', err);
        });
    }

    $('#accept-btn').click(function() {

      $('#verifyModal').modal('hide');
      showConfirmationModal('accept', selectedUser.id);
    });

    $('#reject-btn').click(function() {
      $('#verifyModal').modal('hide');
      showConfirmationModal('reject', selectedUser.id);
    });

    $('#userTabs .nav-link').on('click', function() {
      $('#userTabs .nav-link').removeClass('active');
      $(this).addClass('active');
      currentStatus = $(this).data('status');
      fetchUsers(currentStatus);
    });

    $(document).ready(() => fetchUsers(currentStatus));
    $('#userSearchInput').on('input', function() {
      const query = $(this).val().toLowerCase();

      ['#student-table-body', '#employee-table-body'].forEach(tableId => {
        $(`${tableId} tr`).each(function() {
          const rowText = $(this).text().toLowerCase();
          $(this).toggle(rowText.includes(query));
        });
      });
    });
  })();
</script>