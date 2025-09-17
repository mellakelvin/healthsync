<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Sign Up";
include './head.php';
require __DIR__ . '/utils/connection.php';
require __DIR__ . "/model/Course.php";

$courseModel = new Courses($mysqli);
$courses = $courseModel->getAll();
?>

<body>

  <style>
    .profile-label {
      width: 300px;

      aspect-ratio: 1 / 1;
      border-radius: 100%;
      overflow: hidden;
      position: relative;
      cursor: pointer;
    }

    .profile-label .overlay {
      position: absolute;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s ease;
      border-radius: 100%;
      text-align: center;
      pointer-events: none;
    }

    .profile-label:hover .overlay {
      opacity: 1;
    }

    .profile-label img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
    }
  </style>

  <div class="container-fluid min-vh-100 d-flex flex-column align-items-center px-3 px-md-5">
    <div class="row flex-grow-1 align-items-center w-100 overflow-auto">
      <div class="col-12 col-md-6 d-none d-md-flex justify-content-center align-items-center mb-4 mb-md-0">
        <img src="./assets/sign_in_hero.png" class="img-fluid" alt="Hero Image" style="max-height: 500px;">
      </div>

      <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
        <div class="bg-white p-4 p-md-5 rounded shadow w-100" style="max-width: 50em;">
          <h1 class="display-6 display-md-5 mb-4 text-center text-md-start">
            Create your <span class="fw-bold text-primary">HealthSync</span> account
          </h1>

          <form class="d-flex flex-column gap-3" id="sign-up-form">
            <div class="alert alert-danger d-none" id="warning">
              Please fill out all required fields correctly.
            </div>

            <div class="btn-group" role="group" aria-label="Main role">
              <input type="radio" class="btn-check" name="main-role" id="role-student" value="student" autocomplete="off" checked>
              <label class="btn btn-outline-primary" for="role-student">Student</label>

              <input type="radio" class="btn-check" name="main-role" id="role-employee" value="employee" autocomplete="off">
              <label class="btn btn-outline-primary" for="role-employee">Employee</label>
            </div>

            <div class="btn-group d-none mt-2" id="employee-subrole" role="group" aria-label="Employee subtype">
              <input type="radio" class="btn-check" name="role" id="subrole-instructor" value="5" autocomplete="off" checked>
              <label class="btn btn-outline-secondary" for="subrole-instructor">Teaching</label>

              <input type="radio" class="btn-check" name="role" id="subrole-faculty" value="6" autocomplete="off">
              <label class="btn btn-outline-secondary" for="subrole-faculty">Non-Teaching</label>
            </div>

            <div class="form-group d-flex align-items-center bg-secondary bg-opacity-10 rounded px-3" style="height: 70px;">
              <span class="input-group-text bg-transparent border-0">
                <i class="bi bi-person-badge-fill text-primary fs-5"></i>
              </span>
              <input type="text" class="form-control border-0 bg-transparent ms-3 shadow-none" id="id-number" name="id-number" placeholder="ID Number">
            </div>

            <div class="row g-2">
              <div class="col-12 col-md">
                <input type="text" class="form-control bg-secondary bg-opacity-10 rounded px-3" id="first-name" placeholder="First Name">
              </div>
              <div class="col-12 col-md">
                <input type="text" class="form-control bg-secondary bg-opacity-10 rounded px-3" id="middle-name" placeholder="Middle Name">
              </div>
              <div class="col-12 col-md">
                <input type="text" class="form-control bg-secondary bg-opacity-10 rounded px-3" id="last-name" placeholder="Last Name">
              </div>
            </div>

            <input type="text" class="form-control bg-secondary bg-opacity-10 rounded px-3" id="address" placeholder="Address">
            <input type="text" class="form-control bg-secondary bg-opacity-10 rounded px-3" id="phone-number" placeholder="Phone Number">

            <div class="row g-2">
              <div class="year-selection col-12 col-md">
                <select class="form-select bg-secondary bg-opacity-10" id="year">
                  <option value="">Select Year</option>
                  <option value="1">1st Year</option>
                  <option value="2">2nd Year</option>
                  <option value="3">3rd Year</option>
                  <option value="4">4th Year</option>
                </select>
              </div>
              <div class="col-12 col-md">
                <select class="form-select course-select bg-secondary bg-opacity-10" id="course">
                </select>
              </div>
              <div class="col-12 col-md">
                <select class="form-select bg-secondary bg-opacity-10" id="gender">
                  <option value="">Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>

            <input type="email" class="form-control bg-secondary bg-opacity-10 rounded px-3" id="email-address" placeholder="Email Address">
            <input type="password" class="form-control bg-secondary bg-opacity-10 rounded px-3" id="password" placeholder="Password">
            <button id='btn-profile-picture' type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
              Select profile picture
            </button>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center">
                    <input class="d-none" type="file" name="profile-picker" id="profile-picker">
                    <label for="profile-picker" class="d-inline-block profile-label">
                      <img src="/healthsync/assets/profile-picture.png" id="profile-preview" alt="">
                      <span class="overlay">Upload new photo</span>
                    </label>
                    <p class="mt-3">Image file size must be 10MB or smaller</p>
                  </div>
                  <div class="modal-footer">
                  </div>
                </div>
              </div>
            </div>
            <button class="btn btn-primary py-3 w-100" id="sign-up">Sign up</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="tosModal" tabindex="-1" aria-labelledby="tosModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-white">
        <div class="modal-header">
          <div>
            <div class="modal-subtitle">Agreement</div>
            <h5 class="modal-title" id="tosModalLabel">Terms of Service</h5>
          </div>
        </div>

        <div class="modal-body">
          <h6 class="section-title">1. Terms</h6>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel diam a justo feugiat dictum.</p>

          <h6 class="section-title">2. Use License</h6>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
          <ul>
            <li>Lorem ipsum dolor sit amet.</li>
            <li>Praesent libero. Sed cursus ante dapibus.</li>
            <li>Nunc feugiat mi a tellus consequat imperdiet.</li>
          </ul>

          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pharetra bibendum malesuada.</p>
        </div>

        <div class="modal-footer justify-content-end">
          <button type="button" class="btn btn-light" onclick="location.href = '/healthsync'">Decline</button>
          <button type="button" class="btn btn-primary tos-accept" data-bs-dismiss="modal">Accept</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const courses = <?= json_encode($courses) ?>;
    let tosAccepted = false;
    let selectedRole = 4;
    let selectedPhoto = null
    const tosModal = new bootstrap.Modal($('#tosModal'));
    tosModal.show();
    $('.tos-accept').on('click', function() {
      tosAccepted = true;
    })


    $('#profile-picker').on('change', function(e) {
      var file = e.target.files[0];
      if (file) {
        var reader = new FileReader();

        reader.onload = function(event) {
          $('#profile-preview').attr('src', event.target.result)
          selectedPhoto = event.target.result
        }
        reader.readAsDataURL(file);
      }
      if (!selectedPhoto) {
        $('.profile-label .overlay').text('Replace Photo');
      }
      $('#btn-profile-picture').text('Replace profile picture')
    });

    $('input[name="main-role"]').on('change', function() {
      const main = $(this).val();
      if (main === 'student') {
        selectedRole = 4;
        $('.year-selection').removeClass('d-none');
        $('#employee-subrole').addClass('d-none');
      } else {
        $('#employee-subrole').removeClass('d-none');
        selectedRole = $('input[name="role"]:checked').val();
        $('.year-selection').addClass('d-none');
      }
      updateCourseSelection(selectedRole);
    });

    $('input[name="role"]').on('change', function() {
      selectedRole = $(this).val();
      updateCourseSelection(selectedRole);
    });

    function updateCourseSelection(role) {
      const courseSelect = $('.course-select');
      courseSelect.empty();

      const label = role == 4 ? 'course' : 'department';
      courseSelect.append(`<option value="">Choose ${label}</option>`);

      courses.forEach(e => {
        if (role == 4 && e.role_id == 4) {
          courseSelect.append(`<option value="${e.id}">${e.code.toUpperCase()}</option>`);
        } else if (role == 5 && e.role_id == 5) {
          courseSelect.append(`<option value="${e.id}">${e.name}</option>`);
        } else if (role == 6 && e.role_id == 6) {
          courseSelect.append(`<option value="${e.id}">${e.name}</option>`);
        }
      });
    }

    $('#sign-up').click(async function(e) {
      e.preventDefault();
      $('#warning').addClass('d-none');

      if (!tosAccepted) {
        $('#warning').removeClass('d-none');
        $('#warning').text('Accept the Terms of Service');
        return;
      }
      if (!$('#first-name').val() || !$('#middle-name').val() || !$('#last-name').val() || !$('#email-address').val() || !$('#gender').val() || !$('#phone-number').val()) {
        $('#warning').removeClass('d-none');
        $('#warning').texti('Please fill out all required fields correctly.');
        return;
      }

      const formData = new FormData();

      formData.append('id_number', $('#id-number').val());
      formData.append('first_name', $('#first-name').val());
      formData.append('middle_name', $('#middle-name').val());
      formData.append('last_name', $('#last-name').val());
      formData.append('address', $('#address').val());
      formData.append('phone_number', $('#phone-number').val());
      formData.append('year', parseInt($('#year').val()));
      formData.append('course', parseInt($('#course').val()));
      formData.append('gender', $('#gender').val());
      formData.append('email_address', $('#email-address').val());
      formData.append('password', $('#password').val());
      formData.append('role', parseInt(selectedRole));

      if (selectedPhoto) {
        const blob = dataURLtoBlob(selectedPhoto);
        formData.append('image_url', blob, 'profile.jpg');
      }

      axios.post('/healthsync/auth/sign-up.php', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(res => {
          location.href = '/healthsync/';
        })
        .catch(err => {
          console.error(err);
          let message = 'Please fill out all required fields correctly.';
          if (err.response && err.response.data && err.response.data.error) {
            const error = err.response.data.error;
            if (error.includes('Email')) {
              message = 'Email already exists.';
            } else if (error.includes('ID number')) {
              message = 'ID number already exists.';
            }
          }
          $('#warning').text(message).removeClass('d-none');
        });
    });

    function dataURLtoBlob(dataUrl) {
      const arr = dataUrl.split(',');
      const mime = arr[0].match(/:(.*?);/)[1];
      const bstr = atob(arr[1]);
      let n = bstr.length;
      const u8arr = new Uint8Array(n);
      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }
      return new Blob([u8arr], {
        type: mime
      });
    }

    updateCourseSelection(selectedRole);
  </script>
</body>

</html>