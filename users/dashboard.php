<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "HealthSync - Home";
include './head.php';
?>

<body>
  <div class="container pt-4 pb-5">
    <?php include __DIR__ . '/../header.php'; ?>
    <div class="text-center my-4">
      <img src="./assets/logo-bg-remove.png" style="width: 120px; height: 120px; object-fit: contain;" alt="Logo">
    </div>

    <div class="bg-white shadow-sm rounded-4 p-4 mb-4">
      <h5 class="fw-bold">Hello, <span class="text-primary"><?php echo $firstName . " " . $middleName . " " . $lastName ?></span> 👋</h5>
      <p class="text-muted mb-0">How can we help you today?</p>
    </div>

    <div class="row g-4">
      <div class="col-6">
        <a href='/healthsync/users/lab-results.php' class="w-100 text-decoration-none bg-white shadow-sm rounded-4 text-center p-4 d-block">
          <i class="bi bi-file-medical fs-3 text-success"></i>
          <div class="mt-2 fw-semibold text-dark">Lab Result</div>
        </a>
      </div>
      <div class="col-6">
        <a href="/healthsync/users/ai-chat.php" class="w-100 text-decoration-none bg-white shadow-sm rounded-4 text-center p-4 d-block">
          <i class="bi bi-chat-dots fs-3 text-info"></i>
          <div class="mt-2 fw-semibold text-dark">Health Chatbot</div>
        </a>
      </div>
      <div class="col-6">
        <button type="button" class="w-100 text-decoration-none bg-white shadow-sm rounded-4 text-center p-4 border-0" data-bs-toggle="modal" data-bs-target="#appointmentModal">
          <i class="bi bi-calendar-event fs-3 text-success"></i>
          <div class="mt-2 fw-semibold text-dark">Create Appointment</div>
        </button>
      </div>
      <div class="col-6">
        <a href="/healthsync/users/medical-history.php" class="w-100 text-decoration-none bg-white shadow-sm rounded-4 text-center p-4 d-block">
          <i class="bi bi-clipboard-data fs-3 text-primary"></i>
          <div class="mt-2 fw-semibold text-dark">Medical History</div>
        </a>
      </div>
      <div class="col-6">
        <a href="#" class="w-100 text-decoration-none bg-white shadow-sm rounded-4 text-center p-4 d-block">
          <i class="bi bi-clipboard-data fs-3 text-primary"></i>
          <div class="mt-2 fw-semibold text-dark">Medical Requests</div>
        </a>
      </div>
      <div class="col-6">
        <a href="/healthsync/users/appointments.php" class="w-100 text-decoration-none bg-white shadow-sm rounded-4 text-center p-4 d-block">
          <i class="bi bi-clipboard-data fs-3 text-primary"></i>
          <div class="mt-2 fw-semibold text-dark">Appointments</div>
        </a>
      </div>
    </div>

    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fs-4 fw-semibold text-primary" id="appointmentModalLabel">
              <i class="bi bi-calendar2-plus me-2"></i> Create Appointment
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body pt-2">
            <div id="appointment-warning" class="alert alert-warning d-none" role="alert"></div>

            <form>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="appointment-date" class="form-label">Date</label>
                  <input type="date" class="form-control" id="appointment-date">
                </div>
                <div class="col-md-6">
                  <label for="appointment-time" class="form-label">Time</label>
                  <input type="time" class="form-control" id="appointment-time" min="08:00" max="17:00">
                </div>
                <div class="col-12">
                  <label for="appointment-type" class="form-label">Appointment Type</label>
                  <select class="form-select" id="appointment-type">
                    <option selected disabled>Choose type...</option>
                    <option value="clinic">Clinic</option>
                    <option value="labtech">Laboratory</option>
                    <option value="dentist">Dentist</option>
                  </select>
                </div>
                <div class="col-12">
                  <label for="appointment-service" class="form-label">Service</label>
                  <select class="form-select" id="appointment-service">
                    <option selected disabled>Choose...</option>
                  </select>
                </div>
                <div class="col-12 receipt-group d-none">
                  <label for="receipt" class="form-label">Official Receipt</label>
                  <input class="form-control" type="file" id="receipt">
                </div>
                <div class="col-12">
                  <label for="appointment-notes" class="form-label">Additional Notes</label>
                  <textarea class="form-control" id="appointment-notes" rows="3" placeholder="Symptoms, concerns, etc."></textarea>
                </div>
              </div>
            </form>
          </div>

          <div class="modal-footer border-0 pt-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button id="confirm-appointment" type="button" class="btn btn-primary px-4">
              <i class="bi bi-check-circle me-1"></i> Confirm
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
      <div id="success-toast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">Appointment booked successfully!</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
</body>

<script>
  const servicesByType = {
    clinic: [
      { value: 'Doctor Consultation', label: 'Doctor Consultation' },
      { value: 'Medical Certificate Request', label: 'Medical Certificate Request' }
    ],
    labtech: [
      { value: 'Blood Test', label: 'Blood Test' },
      { value: 'Urinalysis', label: 'Urinalysis' },
      { value: 'Fecalysis', label: 'Fecalysis' },
      { value: 'X-Ray', label: 'X-Ray' },
      { value: 'Drug Test', label: 'Drug Test' }
    ],
    dentist: [
      { value: 'Tooth Extraction', label: 'Tooth Extraction' },
      { value: 'Oral Phropylaxis', label: 'Oral Phropylaxis' },
      { value: 'Tooth Filling', label: 'Tooth Filling' }
    ]
  };

  function updateServiceOptions(type) {
    const serviceSelect = $('#appointment-service');
    serviceSelect.empty();

    if (!type || !servicesByType[type]) {
      serviceSelect.append(`<option selected disabled>Choose...</option>`);
      return;
    }

    serviceSelect.append(`<option selected disabled>Choose...</option>`);
    servicesByType[type].forEach(service => {
      serviceSelect.append(`<option value="${service.value}">${service.label}</option>`);
    });
  }

  $(document).ready(function () {
    const today = new Date().toISOString().split('T')[0];
    $('#appointment-date').attr('min', today);

    $('#appointment-type').on('change', function () {
      const selectedType = $(this).val();

      if (selectedType === 'labtech' || selectedType === 'dentist') {
        $('.receipt-group').removeClass('d-none');
      } else {
        $('.receipt-group').addClass('d-none');
      }

      updateServiceOptions(selectedType);
    });

    $('#confirm-appointment').click(function () {
      const type = $('#appointment-type').val();
      const service = $('#appointment-service').val();
      const date = $('#appointment-date').val();
      const time = $('#appointment-time').val();
      const description = $('#appointment-notes').val();
      const warning = $('#appointment-warning');
      const receipt = $('#receipt')[0].files[0];

      if (time) {
        const [hours, minutes] = time.split(':').map(Number);
        const totalMinutes = hours * 60 + minutes;
        const minTime = 8 * 60;
        const maxTime = 17 * 60;
        if (totalMinutes < minTime || totalMinutes > maxTime) {
          warning.removeClass('d-none').text('Please select a time between 8:00 AM and 5:00 PM.');
          return;
        }
      }

      warning.addClass('d-none').text('');

      if (!type || !service || !date || !time || ((type === 'labtech' || type === 'dentist') && !receipt)) {
        warning.removeClass('d-none').text('Please fill in all required fields.');
        return;
      }

      const formData = new FormData();
      formData.append('type', type);
      formData.append('service', service);
      formData.append('date', date);
      formData.append('time', time);
      formData.append('description', description);
      formData.append('receipt', receipt);

      axios.post('/healthsync/api/appointment/create-appointment.php', formData)
        .then(res => {
          $('#appointmentModal').modal('hide');
          $('#appointment-type').val('');
          $('#appointment-date').val('');
          $('#appointment-time').val('');
          $('#appointment-service').empty().append(`<option selected disabled>Choose...</option>`);
          $('#appointment-notes').val('');
          $('.receipt-group').addClass('d-none');
          $('#receipt').val('');
          warning.addClass('d-none');
          const toast = new bootstrap.Toast(document.getElementById('success-toast'));
          toast.show();
        })
        .catch(err => {
          console.error(err);
          warning.removeClass('d-none').text('Failed to book appointment. Please try again.');
        });
    });
  });
</script>

</html>
