<style>
  #lab-results-table th,
  #lab-results-table td {
    padding: 1rem;
    vertical-align: middle;
    border: none;
  }

  #lab-results-table tr:hover {
    background-color: #f8f9fa;
    cursor: pointer;
  }

  .action-btn {
    margin-right: 0.5rem;
  }
</style>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-semibold mb-0">Lab Results</h2>
    <div class="d-flex">
      <input type="text" id="search-lab-results" class="form-control " placeholder="Search results..." style="width: auto; min-width: 220px;">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#labResultsReportModal">
        Generate Report
      </button>
    </div>
  </div>

  <div class="modal fade" id="labResultsReportModal" tabindex="-1" aria-labelledby="labResultsReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="labResultsReportModalLabel">Generate Report</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="labPresetRange" class="form-label">Preset Range</label>
            <select class="form-select" id="labPresetRange">
              <option disabled selected>Select preset</option>
              <option value="this_month">This Month</option>
              <option value="last_month">Last Month</option>
              <option value="this_year">This Year</option>
            </select>
          </div>

          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="labEnableCustomRange">
            <label class="form-check-label" for="labEnableCustomRange">Enable Custom Date Range</label>
          </div>

          <div id="labCustomRangeFields" class="row g-2 d-none">
            <div class="col-md-6">
              <label for="labStartDate" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="labStartDate">
            </div>
            <div class="col-md-6">
              <label for="labEndDate" class="form-label">End Date</label>
              <input type="date" class="form-control" id="labEndDate">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button id="generateLabResultsReport" type="button" class="btn btn-primary">Generate</button>
        </div>
      </div>
    </div>
  </div>

</div>

<div class="table-responsive">
  <table class="table table-hover align-middle mb-0" id="lab-results-table">
    <thead>
      <tr class="text-secondary fw-semibold">
        <th>ID</th>
        <th>Appointment ID</th>
        <th>Result</th>
        <th>Created</th>
        <th>Updated</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="lab-results-table-body"></tbody>
  </table>
</div>
</div>


<script>
  (function() {
    document.getElementById("labEnableCustomRange").addEventListener("change", function() {
      document.getElementById("labCustomRangeFields").classList.toggle("d-none", !this.checked);
    });

    document.getElementById("generateLabResultsReport").addEventListener("click", function() {
      const preset = document.getElementById("labPresetRange").value;
      const enableCustom = document.getElementById("labEnableCustomRange").checked;
      const startDate = document.getElementById("labStartDate").value;
      const endDate = document.getElementById("labEndDate").value;

      const params = new URLSearchParams();
      if (preset && !enableCustom) params.append("preset", preset);
      if (enableCustom) {
        params.append("preset", "custom");
        if (startDate) params.append("start_date", startDate);
        if (endDate) params.append("end_date", endDate);
      }

      const url = "/healthsync/api/labresult/generate-report.php?" + params.toString();
      window.open(url, "_blank");
      document.getElementById("generateLabResultsReport").innerText = "Generating...";
      setTimeout(() => {
        document.getElementById("generateLabResultsReport").innerText = "Generate";
        bootstrap.Modal.getInstance(document.getElementById("labResultsReportModal")).hide();
      }, 1000);
    });





    function loadLabResults() {
      axios.get("/healthsync/api/labresult/labresult.php")
        .then(response => {
          const data = response.data;
          const tbody = $("#lab-results-table-body").empty();

          if (Array.isArray(data) && data.length) {
            data.forEach(result => {
              const tr = $('<tr></tr>');
              tr.append(`<td class="py-3">${result.id}</td>`);
              tr.append(`<td class="py-3">${result.appointment_id ?? '[deleted]'}</td>`);
              tr.append(`<td class="py-3"><a href="${result.result_url}" target="_blank" class="text-primary text-decoration-underline">View</a></td>`);
              tr.append(`<td class="py-3 text-muted">${result.created_at}</td>`);
              tr.append(`<td class="py-3 text-muted">${result.updated_at}</td>`);

              const deleteBtn = $(`
                <button class="btn btn-sm btn-outline-danger action-btn" data-id="${result.id}">
                  Delete
                </button>
              `).click(function(e) {
                e.stopPropagation();
                const id = $(this).data("id");
                if (!confirm("Are you sure you want to delete this lab result?")) return;

                axios.delete("/healthsync/api/labresult/labresult.php", {
                  data: {
                    id: id
                  }
                }).then(res => {
                  if (res.data.success) {
                    alert("Deleted successfully");
                    loadLabResults();
                  } else {
                    alert("Failed to delete");
                  }
                }).catch(() => alert("Error deleting lab result"));
              });

              const actionTd = $('<td class="py-3 d-flex"></td>').append(deleteBtn);
              tr.append(actionTd);

              tbody.append(tr);
            });
          } else {
            tbody.html('<tr><td colspan="6" class="text-muted text-center py-4">No lab results found.</td></tr>');
          }
        })
        .catch(err => {
          console.error(err);
          alert("Failed to load lab results.");
        });
    }
    $('#search-lab-results').on('input', function() {
      const query = $(this).val().toLowerCase();
      $('#lab-results-table-body tr').each(function() {
        const rowText = $(this).text().toLowerCase();
        $(this).toggle(rowText.includes(query));
      });
    });

    $(document).ready(loadLabResults);
  })();
</script>