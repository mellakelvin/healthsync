<style>
  #complaint-table {
    border-collapse: collapse;
    width: 100%;
  }

  #complaint-table thead tr {
    border-bottom: 1px solid #dee2e6;
  }

  #complaint-table th,
  #complaint-table td {
    padding: 1rem;
    text-align: center;
    vertical-align: middle;
    background-color: transparent !important;
    border: none;
  }

  #complaint-table tr:hover {
    background-color: #f8f9fa;
  }

  td[contenteditable="true"] {
    background-color: #fff8dc;
    border-radius: 4px;
  }
</style>

<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2>MONTHLY CHIEF COMPLAINTS OF STUDENTS & SEEN AT THE UNIVERSITY CLINIC</h2>
      <h5 id="selected-month-label">(Month)</h5>
    </div>
    <div class="d-flex">
      <a id="export-mcc" class="btn btn-primary" target="_blank">Export</a>
      <div class="dropdown">
        <button class="btn btn-primary ms-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="month-dropdown">
          Select Month
        </button>
        <ul class="dropdown-menu" id="month-list">
        </ul>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table id="complaint-table" class="table table-bordered table-hover table-sm">
      <thead class="table-light">
        <tr>
          <th>COLLEGE/DEPARTMENT</th>
        </tr>
      </thead>
      <tbody id="complaint-body">
      </tbody>
    </table>
  </div>
</div>

<script>
  (function() {
    const illnesses = [
      'Fever', 'Headache', 'Cough', 'Colds', 'Allergy', 'Abdominal Cramps',
      'Menstrual Cramps', 'Diarrhea', 'Muscle Pain', 'Toothache',
      'Epigastric Pain', 'Tonsillitis', 'Wound', 'Vertigo', 'Sprain'
    ];

    const months = [];
    const start = new Date('2025-01-01');
    const today = new Date();

    while (start <= today) {
      const yyyy = start.getFullYear();
      const mm = String(start.getMonth() + 1).padStart(2, '0');
      months.unshift(`${yyyy}-${mm}`);
      start.setMonth(start.getMonth() + 1);
    }

    let selectedMonth = months[0];


    function renderTable(data) {
      const $thead = $('#complaint-table thead tr');
      $thead.find('th:not(:first)').remove();
      illnesses.forEach(i => $thead.append(`<th>${i}</th>`));

      const $tbody = $('#complaint-body').empty();
      data.forEach(row => {
        let html = `<tr data-dept="${row.department}"><td>${row.department}</td>`;
        illnesses.forEach(i => {
          const val = row[i] ?? 0;
          html += `<td><input type="text" class="form-control form-control-sm complaint-input text-center" value="${val}" data-original="${val}" data-illness="${i}"></td>`;
        });
        html += `</tr>`;
        $tbody.append(html);
      });

      updateTotalRow(data);
    }

    function updateTotalRow(data) {
      let totals = {};
      illnesses.forEach(i => totals[i] = 0);
      data.forEach(row => {
        illnesses.forEach(i => {
          totals[i] += parseInt(row[i] ?? 0);
        });
      });

      let html = `<tr class="fw-bold table-secondary"><td>TOTAL</td>`;
      illnesses.forEach(i => {
        html += `<td>${totals[i]}</td>`;
      });
      html += `</tr>`;
      $('#complaint-body').append(html);
    }

    function fetchData(month) {
      axios.get('/healthsync/api/mcc/index.php', {
          params: {
            month
          }
        })
        .then(response => {
          renderTable(response.data);
          $('#selected-month-label').text(new Date(month + '-01').toLocaleString('default', {
            month: 'long',
            year: 'numeric'
          }));
        })
        .catch(error => {
          alert('Failed to fetch data.');
          console.error(error);
        });
    }

    $(document).on('blur change', '.complaint-input', function() {
      const $input = $(this);
      const newValue = parseInt($input.val()) || 0;
      const original = parseInt($input.attr('data-original')) || 0;
      if (newValue === original) return;

      const $row = $input.closest('tr');
      const department = $row.data('dept');
      const illness = $input.data('illness');

      axios.post('/healthsync/api/mcc/index.php', {
          department,
          month: selectedMonth,
          illness,
          value: newValue
        })
        .then(() => {
          $input.attr('data-original', newValue);
          fetchData(selectedMonth);
        })
        .catch(error => {
          alert('Failed to update. Please try again.');
          console.error(error);
        });
    });

    $(document).ready(function() {
      months.forEach(m => {
        const label = new Date(m + '-01').toLocaleString('default', {
          month: 'long',
          year: 'numeric'
        });
        $('#month-list').append(`<li><div class="dropdown-item month-option" data-month="${m}">${label}</div></li>`);
      });

      $(document).on('click', '.month-option', function() {
        selectedMonth = $(this).data('month');
        fetchData(selectedMonth);
      });

      fetchData(selectedMonth);

      $(document).on('click', '.dropdown-item', function(e) {
        e.preventDefault();
        const selectedMonth = $(this).data('month');
        $('#month-dropdown').text(selectedMonth);
        $('#export-mcc').attr('href', `/healthsync/api/mcc/export.php?month=${selectedMonth}`);
      });
      $('#month-dropdown').text(selectedMonth);
      $('#export-mcc').attr('href', `/healthsync/api/mcc/export.php?month=${selectedMonth}`);
    });

  })()
</script>