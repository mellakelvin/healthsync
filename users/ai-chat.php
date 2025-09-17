<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "HealthSync - Chat";
$scripts = [
  'https://cdn.jsdelivr.net/npm/marked/marked.min.js'
];
include __DIR__ . '/../head.php';
?>

<body>
  <div style="overflow:hidden" class="container min-vh-100 pt-4 d-flex flex-column">
    <?php include __DIR__ . '/../header.php'; ?>
    <h1 class="text-center text-primary">CareMate: Your AI Assistant</h1>

    <div class="message-container flex-grow-1 overflow-auto" style="max-height: 70vh;">
      <div id="end"></div>

      <div style="display: block; text-align: left; color: black; margin-top: 10px">
        <p style="max-width: 70%; display: inline-block; background-color: #9EFFCA; color: black; padding: 10px; border-radius: 10px;">
          Hello there!, what's your questions?
        </p>
      </div>


      <style>
        .menu-faq p,
        .submenu p {
          margin: 0.5rem 0;
          padding: 0.5rem;
          cursor: pointer;
          border-radius: 5px;
          transition: background 0.2s;
        }

        .menu-faq p:hover,
        .submenu p:hover {
          background: #f0f0f0;
        }
      </style>

      <script>
        function dummyResponse(mdtext) {
          const text = marked.parse(mdtext);

          $('.message-container').append(`
          <div style="display: block; text-align: left; color: black; margin-top:10px">
            <div style="max-width: 70%; display: inline-block; background-color: #9EFFCA; color: black; padding: 10px; border-radius: 10px;">
              ${text}
            </div>
          </div>`)
        }

        function sendToChat(message) {
          const API_KEY = 'AIzaSyDYVH9N1dVtkgSbl6CyK93uZEAdQMdlleA';

          axios.post(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-lite:generateContent?key=${API_KEY}`, {
              contents: [{
                parts: [{
                  text: message
                }]
              }],
              system_instruction: {
                parts: [{
                  text: `You are a helpful assistant that only provides health-related advice.
            You have access to the following clinic and lab information:

            == Clinic Information ==
            University Clinic
            Hours: Monday–Friday, 8AM to 6PM
            Address: In front of Orata Bldg 1
            Phone: (075) 529-5223 local (2137)
            OIC: Dr. Amelia C. Fernando

            == Diagnostic & Laboratory Department ==
            Hours: Monday–Friday, 8AM to 6PM
            Location: 1/F Medical Technology Laboratory
            Phone: (075) 529-5223
            Pathologist: Dr. Ma. Theresa Q. Tosino-Enrile


            == Dentist Services ==
            Tooth Extraction
            Oral Phropylaxis
            Tooth Filling

            == Laboratory Service Process ==
            1. Get laboratory request from the university clinic or requesting physician (Triage).
              - Client: All University Patients or Outpatients
              - Provider: Patient
              - Requirements: None
              - Fees: None
              - Time: 3–5 minutes

            2. Proceed for payment of the laboratory request from University Clinic or requesting physician.
              - Client: All University Patients or Outpatients
              - Provider: Patient
              - Requirements: Laboratory Request
              - Fees: None
              - Time: –

            3. Present the laboratory request to the cashier.
              - Client: All University Patients or Outpatients
              - Provider: Patient
              - Requirements: Laboratory Request
              - Fees: None
              - Time: –

            4. Payment for the laboratory tests and issuance of official receipt.
              - Provider: Cashier
              - Requirements: Laboratory Request
              - Fees: As per posted price list
              - Time: 3–5 minutes (depending on queue)

            5. Proceed to the laboratory (Reception Area).
              - Provider: Patient
              - Requirements: Laboratory Request and Official Receipt
              - Fees: None
              - Time: –

            6. Present the laboratory request and official receipt to laboratory personnel (Reception Area).
              - Provider: Laboratory Receptionist
              - Requirements: Laboratory Request and Official Receipt
              - Fees: None
              - Time: 1 minute

            7. Proceed for extraction or specimen collection.
              - Provider: Medical Technologist
              - Requirements: None
              - Fees: None
              - Time: 1–3 minutes

            8. Endorsement of sample with the patient's laboratory request to the lab processing area.
              - Provider: Laboratory Personnel
              - Requirements: Laboratory Request with collected specimen
              - Fees: None
              - Time: –

            9. Release of results.
              - Provider: Laboratory Personnel
              - Requirements: None
              - Fees: None
              - Time: Depends on type of test (up to 3–4 hours)

            == Laboratory Tests ==
            | Department                           | Tests                                                                                                     | Fees (PHP) | Time Frame |
            |--------------------------------------|-----------------------------------------------------------------------------------------------------------|------------|-------------|
            | Hematology                           | Complete Blood Count (CBC)                                                                                | 280        | 1–2 hours   |
            | Clinical Chemistry                   | Glucose (FBS/RBS), Creatinine, BUN, Uric Acid, ALT (SGPT), AST (SGOT), Triglycerides, Cholesterol, HDL – each | 150    | 2–3 hours   |
            |                                      | Lipid Profile (TAG, Chole, HDL, LDL)                                                                      | 600        | 2–3 hours   |
            | Clinical Microscopy and Parasitology | Pregnancy Test                                                                                            | 150        | 1–2 hours   |
            |                                      | Urinalysis, Fecalysis – each                                                                              | 60         | 2–3 hours   |
            | Immunology – Serology                | Hepatitis B Surface Antigen (HBsAg) Screening                                                             | 250        | 2–3 hours   |
            |                                      | Dengue Duo                                                                                                | 600        | 2–3 hours   |



            Use this University Clinic data to assist with questions about health services:

            == UNIVERSITY CLINIC INFORMATION ==
             Location: In front of Orata Bldg 1
             Contact: (075) 529-5223 local (2137)
             Officer-in-Charge: Dr. Amelia C. Fernando, M.D.
             Hours: Monday–Friday, 8:00 AM – 6:00 PM

            == VISION ==
            To become the leading health care provider for the Urdaneta City University Community.

            == MISSION ==
            Promote health and well-being through basic medical services, client health education, and medical research.

            == GOALS ==
            1. Deliver high-quality and accessible medical care.
            2. Promote health literacy and education.
            3. Ensure a safe learning environment through health inspections.
            4. Coordinate with health authorities during outbreaks.
            5. Offer dental care services.
            6. Protect patient confidentiality.
            7. Promote overall health and wellness of the university community.

            == SERVICES AND STEPS ==

            1.  ISSUANCE OF MEDICAL CERTIFICATE (Students/Employees)
            - Schedule: Mon–Fri, 8:00 AM – 5:00 PM
            - Requirements: UCU ID, Registration Form, Admitting Form
            - Duration: 15–20 mins
            - Steps:
              1. Book appointment or walk-in → triage, profile, vitals
              2. Register in logbook
              3. Inform nurse on duty
              4. Vitals taken
              5. Proceed to consultation room
              6. Certificate issued

            2.  ANNUAL MEDICAL EXAMINATION (Employees)
            - Schedule: Mon–Fri 7:30 AM–5:00 PM, Sat 8:00 AM–5:00 PM
            - Requirements: UCU Employee ID
            - Duration: 15–25 mins
            - Steps:
              1. Register with ID
              2. Lab: blood, urine, X-ray, ECG (if 40+)
              3. Return to clinic for vitals
              4. Proceed to consultation

            3. MEDICAL ASSESSMENT (Newly hired/promoted employees)
            - Schedule: Mon–Fri 7:30 AM–6:00 PM, Sat 8:00 AM–5:00 PM
            - Requirements: Medical form, lab results, X-ray
            - Duration: 10–25 mins
            - Steps:
              1. Approach nurse
              2. Take vitals, check results
              3. Physical examination
              4. Post-assessment instruction

            4. DENTAL CONSULTATION
            - Schedule: Mon–Fri, 8:00 AM–12:00 NN & 1:00 PM–5:00 PM
            - Requirements: UCU ID or Registration
            - Duration: 10–15 mins
            - Steps:
              1. Book appointment or walk-in → triage
              2. Register in logbook
              3. Take vitals
              4. Proceed to Dental Room
              5. Post-consult instructions, meds if needed

            5. MEDICAL CERTIFICATE for OJT Students
            - Schedule: Mon–Fri 7:00 AM–6:00 PM, Sat 8:00 AM–5:00 PM
            - Requirements: Medical form, CBC, X-ray, Urinalysis, Fecalysis (CHTM), COVID Card, Hepa A & B Vaccine
            - Duration: 10–15 mins
            - Steps:
              1. Approach nurse and receive form
              2. Fill out and submit form
              3. Take vitals (BMI, etc.)
              4. Physical exam
              5. Final nurse instructions

            6. 👨‍⚕️ MEDICAL CONSULTATION (Students/Employees)
            - Schedule: Mon–Fri 7:30 AM–6:00 PM, Sat 8:00 AM–5:00 PM
            - Requirements: UCU ID or Registration
            - Duration: 10–15 mins
            - Steps:
              1. Book or walk-in
              2. Log in the book
              3. Inform nurse about condition
              4. Take vitals
              5. Proceed to consultation room
              6. Post-consultation meds/instructions

            7. 📱 TELECONSULTATION
            - Schedule: Mon–Sat, 8:00 AM–5:00 PM
            - Requirements: UCU ID or Registration
            - Duration: 1–1.5 hours
            - Steps:
              1. Message UCU Facebook page
              2. Fill pre-consult questions
              3. Schedule and prepare for Viber or FB call
              4. Consult with physician
              5. Log and chart patient info

            8. ADMISSION TO UNIVERSITY CLINIC (Walk-in)
            - Schedule: Mon–Sat, 8:00 AM–5:00 PM
            - Requirements: UCU ID or Registration
            - Duration: 16–26 mins
            - Steps:
              1. Enter clinic, undergo history-taking
              2. Assessment and vitals
              3. Proceed to diagnostics/therapeutic procedures

            For clinic, all services are **free of charge**. All students and employees are eligible unless stated otherwise.

            You have the freedom to respond to user questions based on the above, or any health related problems, health advices or any questions related to health.

            NOTE: If the answer is a bit short, make sure that its descriptive
            Also, you can provide medical advices
            `
                }]
              }


            }, {
              headers: {
                'Content-Type': 'application/json'
              }
            })
            .then(response => {
              $('.loading-bubble').remove();
              dummyResponse(response.data.candidates?.[0]?.content?.parts?.[0]?.text);
              scrollToBottom();
            })
            .catch(error => {
              console.error('Error:', error.response?.data || error.message);
            });
        }
        $(document).ready(function() {
          const subFaqs = {
            clinic: [
              "What services are available at the university clinic?",
              "What is the clinic operating hours?",
              "Where is the clinic located?"
            ],
            dentist: [
              "What dental services are offered?",
              "Do I need an appointment for dental check-up?",
              "Are dental services free for students and employees?"
            ],
            lab: [
              "What laboratory tests are available?",
              "Do I need a referral for a lab test?",
              "How long before I get my lab results?",
              "How can I view or download my lab results online?"
            ],
            certificate: [
              "How can I request a medical certificate?",
              "What requirements are needed for a medical certificate?",
              "How long does it take to process?",
              "Can I request it online?"
            ],
            appointments: [
              "How do I book an appointment with a doctor/nurse?",
              "Can I cancel or reschedule my appointment?",
              "Will I get reminders for upcoming appointments?"
            ],
            medicines: [
              "Is medicine free for students and employees?",
              "How do I know if a medicine is available at the clinic?",
              "Can I get OTC (over-the-counter) medicine without consultation?"
            ]
          };

          $('.menu-item').click(function() {
            const key = $(this).data('key');
            const items = subFaqs[key] || [];
            const html = items.map(item => `<p class="submenu-item">${item}</p>`).join('');
            $('.submenu-content').html(html);
            $('.menu-faq').hide();
            $('.submenu').show();
          });

          $('.back-btn').click(function() {
            $('.submenu').hide();
            $('.menu-faq').show();
          });

          $('.submenu-content').on('click', '.submenu-item', function() {
            const clickedText = $(this).text();
            console.log("Clicked subFAQ:", clickedText);
            $('.message-container').append(`
              <div style="display: block; text-align: right; color: white; margin-top:10px">
                <p class="bg-primary p-2 rounded" style="max-width: 70%; display: inline-block;">
                  ${clickedText}
                </p>
              </div>
            `);
            sendToChat(clickedText);

          });
        });
      </script>


    </div>
    <style>
      #menu {
        text-align: center;
        max-width: 400px;
        margin-top: 10px;
        margin-left: 0;
        margin-right: 0;
      }

      @media (max-width: 768px) {
        #menu {
          margin: auto;
        }
      }
    </style>
    <div id="menu" style="overflow-y: auto; overflow-x: hidden; text-align: center; max-width: 400px; margin-left: auto">
      <div id="menu-controller" style='height:1em; background: #1b8eba'></div>
      <div class="menu-faq card p-3 rounded shadow" style="text-align:left; width:400px; background:white;">
        <p class="menu-item" data-key="clinic">Clinic Services</p>
        <p class="menu-item" data-key="dentist">Dentist Services</p>
        <p class="menu-item" data-key="lab">Laboratory Services</p>
        <p class="menu-item" data-key="certificate">Medical Certificates</p>
        <p class="menu-item" data-key="appointments">Appointments</p>
        <p class="menu-item" data-key="medicines">Medicines</p>
      </div>
      <div class="submenu card p-3 rounded shadow" style="text-align:left; max-width:400px ;background:white; display:none;">
        <button class="back-btn btn btn-light mb-2">← Back</button>
        <div class="submenu-content"></div>
      </div>
    </div>
    <form id="message-form">
      <div class="input-group mt-2">
        <textarea id="message" class="form-control" placeholder="Enter text here"></textarea>
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit">
            <i class="bi bi-send"></i> Send
          </button>
        </div>
      </div>
    </form>
  </div>
</body>

<style>
  .typing-dots div {
    animation: blink 1.2s infinite;
    background-color: lightgrey;
    aspect-ratio: 1/1;
    width: 20px;
    border-radius: 999px;
  }

  .typing-dots div:nth-child(2) {
    animation-delay: 0.2s;
  }

  .typing-dots div:nth-child(3) {
    animation-delay: 0.4s;
  }

  @keyframes blink {
    0% {
      opacity: 0.2;
    }

    20% {
      opacity: 1;
    }

    100% {
      opacity: 0.2;
    }
  }

  .message-container {
    max-height: 70vh;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
  }

  #end {
    flex-grow: 1;
  }
</style>

<script>
  $(document).ready(function() {

    const menuController = $('#menu-controller');
    const menu = $('#menu');

    let isMenuHidden = true;

    menuController.on('click', function() {
      isMenuHidden = !isMenuHidden;
      console.log(isMenuHidden);
      menu.css('transition', 'height 100ms ease');
      menu.css('height', isMenuHidden ? '20em' : '2em');
    });

    function keywordMatch(message, keyword) {
      const pattern = new RegExp(`\\b${keyword.replace(/[-/\\^$*+?.()|[\]{}]/g, '\\$&')}\\b`, 'i');
      return pattern.test(message);
    }

    function scrollToBottom() {
      var container = $('.message-container');
      container.animate({
        scrollTop: $('#end')[0].offsetTop
      }, );
    }
    const conversationData = [{
        keywords: ['what', "clinic's", 'clinic', 'hours'],
        response: 'The clinic is open every Monday to Friday, 8:00 AM to 5:00 PM.'
      },
      {
        keywords: ["clinic's", 'clinic', 'location', 'where'],
        response: 'The clinic\'s location is beside Building 5 and Building 9.'
      },
      {
        keywords: ['what', "laboratory's", 'laboratory', 'hours'],
        response: 'The Laboratory is open every Monday to Friday, 8:00 AM to 5:00 PM.'
      },
      {
        keywords: ["diagnostics", 'diagnostic', 'location', 'where'],
        response: 'The diagnostic\'s location is beside Building 5 and Building 9.'
      },
    ];



    scrollToBottom();

    $('#message-form').on('submit', function(e) {
      e.preventDefault();

      var message = $('#message').val().replace(/\n/g, '<br>');

      $('.message-container').append(`
          <div style="display: block; text-align: right; color: white;">
            <p class="bg-primary p-2 rounded" style="max-width: 70%; display: inline-block;">
              ${message}
            </p>
          </div>
        `);

      $('#message').val('');


      $('.message-container').append(`
      <div class="loading-bubble" style="display: block; text-align: left; color: black; margin-bottom: 10px;">
        <div style="max-width: 70%; width:min-content;  display: flex; align-items:center; background-color: #9EFFCA; color: black; padding: 10px; border-radius: 10px;">
          <span class="typing-dots" style="display:flex; align-items:center; gap:4px">
          <div></div>
          <div></div>
          <div></div>
          </span>
        </div>
      </div>
      `);

      sendToChat(message);
      scrollToBottom();
    });

  });
</script>


</html>