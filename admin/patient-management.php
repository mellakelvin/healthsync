<style>
  #students-table,
  #employees-table {
    border-collapse: collapse;
    width: 100%;
  }

  #students-table thead tr #employee-tables thead tr {
    border-bottom: 1px solid #dee2e6;
  }

  #students-table th,
  #students-table td,
  #employees-table th,
  #employees-table td {
    padding: 1rem;
    vertical-align: middle;
    background-color: transparent !important;
    border: none;
  }

  #students-table tr:hover,
  #employees-table tr:hover {
    background-color: #f8f9fa;
  }
</style>

<div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="generateReportModalLabel">Generate Report</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
          <label for="typeSelect" class="form-label">User Type</label>
          <select class="form-select" id="typeSelect">
            <option value="">All</option>
            <option value="4">Student</option>
            <option value="5">Employee</option>
            <option value="6">Employee Non-teaching</option>
            <option value="2">Lab Technician</option>
            <option value="3">Nurse</option>
            <option value="1">Admin</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="statusSelect" class="form-label">Status</label>
          <select class="form-select" id="statusSelect">
            <option value="">All</option>
            <option value="PENDING">Pending</option>
            <option value="ACTIVE">Active</option>
            <option value="INACTIVE">Inactive</option>
            <option value="REJECTED">Rejected</option>
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


<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-semibold mb-0">Active Students</h2>

    <div class="d-flex align-items-center gap-2">
      <input type="text" id="search-users" class="form-control" placeholder="Search students and employees..." style="width: 250px;">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
        Generate report
      </button>
    </div>
  </div>


  <div class="table-responsive">
    <table class="table table-hover table-borderless align-middle mb-0" id="students-table" style="font-size: 0.95rem;">
      <thead class="border-bottom text-secondary fw-semibold">
        <tr>
          <th>Name</th>
          <th>ID Number</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Course & Year</th>
          <th>Gender</th>
          <th>Status</th>
          <th>Registered</th>
        </tr>
      </thead>
      <tbody id="students-table-body"></tbody>
    </table>
  </div>
</div>

<div class="container-fluid px-4 py-4">
  <h2 class="mb-4 fw-semibold">Active Employees</h2>

  <div class="table-responsive">
    <table class="table table-hover table-borderless align-middle mb-0" id="employees-table" style="font-size: 0.95rem;">
      <thead class="border-bottom text-secondary fw-semibold">
        <tr>
          <th>Name</th>
          <th>ID Number</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Department</th>
          <th>Gender</th>
          <th>Status</th>
          <th>Registered</th>
        </tr>
      </thead>
      <tbody id="employees-table-body"></tbody>
    </table>
  </div>
</div>
<script>
  $(document).ready(function() {
    document.getElementById("enableCustomRange").addEventListener("change", function() {
      const rangeFields = document.getElementById("customRangeFields");
      rangeFields.classList.toggle("d-none", !this.checked);
    });

    document.getElementById("generate-report").addEventListener("click", function() {
      const type = document.getElementById("typeSelect").value;
      const status = document.getElementById("statusSelect").value;
      const preset = document.getElementById("presetRange").value;
      const enableCustom = document.getElementById("enableCustomRange").checked;
      const startDate = document.getElementById("startDate").value;
      const endDate = document.getElementById("endDate").value;

      const params = new URLSearchParams();

      if (type) params.append("role", type);
      if (status) params.append("status", status);
      if (preset && !enableCustom) params.append("preset", preset);
      if (enableCustom) {
        if (startDate) params.append("start_date", startDate);
        if (endDate) params.append("end_date", endDate);
        params.append("preset", "custom");
      }
      // console.log(params.toString());
      // return
      const url = "/healthsync/api/user/generate-report.php?" + params.toString();
      window.open(url, '_blank');
    });






    axios.post('/healthsync/api/user/users.php', {
      status: 'ACTIVE',
      roles: [4, 5, 6]
    }).then(function(res) {
      const users = res.data || [];

      const students = users.filter(u => u.role == 4);
      const employees = users.filter(u => u.role == 5 || u.role == 6);

      renderTable('#students-table-body', students, 'student');
      renderTable('#employees-table-body', employees, 'employee');
    }).catch(function(err) {
      console.error("Error fetching users:", err);
    });

    function renderTable(tbodySelector, users, type) {
      const $tbody = $(tbodySelector);
      $tbody.empty();

      users.forEach(function(u) {
        const fullName = `${u.first_name} ${u.middle_name} ${u.last_name}`;
        const tr = $(`
<tr>
  <td class="py-3">${fullName}</td>
  <td class="py-3">${u.id_number}</td>
  <td class="py-3">${u.email_address}</td>
  <td class="py-3">${u.phone_number}</td>
  <td class="py-3">${u.course_code}</td>
  <td class="py-3">${u.gender}</td>
  <td class="py-3"><span class="badge bg-${getStatusColor(u.status)}">${u.status}</span></td>
  <td class="py-3">${u.created_at}</td>
</tr>
`);

        $tbody.append(tr);
      });
    }

    function getStatusColor(status) {
      switch (status) {
        case 'ACTIVE':
          return 'success';
        case 'PENDING':
          return 'warning text-dark';
        case 'INACTIVE':
          return 'secondary';
        case 'REJECTED':
          return 'danger';
        default:
          return 'light';
      }
    }
    $('#search-users').on('keyup', function() {
      const query = $(this).val().toLowerCase();
      console.log(query);
      $('#students-table-body tr, #employees-table-body tr').each(function() {
        const rowText = $(this).text().toLowerCase();
        $(this).toggle(rowText.includes(query));
      });
    });

  });
</script>