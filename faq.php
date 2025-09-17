<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Healthsync - FAQ";
require __DIR__ . '/head.php';
?>

<body>
  <style>
    body {
      background-color: white !important;
    }

    .main-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 2rem;
      padding: 2rem;
    }

    .faq-container h1 {
      font-weight: bold;
    }

    .faq-hero img {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
    }

    .accordion-button:focus {
      box-shadow: none;
    }
  </style>

  <div class="container main-container">
    <div class="faq-container col-md-6">
      <h1 class="mb-4">Frequently Asked <br>Questions</h1>
      <div class="accordion" id="faqAccordion">
      </div>
    </div>

    <div class="faq-hero col-md-6 text-center">
      <img src="/healthsync/assets/faq-hero.jpg" alt="FAQ Hero Image">
    </div>
  </div>

  <script>
    const faqData = [{
        question: "What is Healthsync?",
        answer: "Healthsync is a digital platform designed to simplify your health monitoring and clinic management experience."
      },
      {
        question: "How do I book an appointment?",
        answer: "You can book appointments by logging into your account and navigating to the Appointments section."
      },
      {
        question: "Is my data secure?",
        answer: "Absolutely. We use encryption and comply with industry standards to protect your information."
      },
      {
        question: "What are the clinic hours?",
        answer: "The University Clinic is open Monday to Friday, from 8:00 AM to 6:00 PM."
      },
      {
        question: "Where is the clinic located?",
        answer: "The University Clinic is located in front of Orata Building 1, Urdaneta City University."
      },
      {
        question: "What are the laboratory hours?",
        answer: "The Diagnostic and Laboratory Department is open Monday to Friday, from 8:00 AM to 6:00 PM."
      },
      {
        question: "Where is the diagnostic laboratory located?",
        answer: "The Diagnostic and Laboratory Department is located on the 1st Floor of the Medical Technology Laboratory building at Urdaneta City University."
      }
    ];


    const faqAccordion = document.getElementById('faqAccordion');

    faqData.forEach((faq, index) => {
      const card = document.createElement('div');
      card.className = 'accordion-item mb-2';

      card.innerHTML = `
        <h2 class="accordion-header" id="heading${index}">
          <button class="accordion-button ${index !== 0 ? 'collapsed' : ''}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${index}" aria-expanded="${index === 0 ? 'true' : 'false'}" aria-controls="collapse${index}">
            ${faq.question}
          </button>
        </h2>
        <div id="collapse${index}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" aria-labelledby="heading${index}" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            ${faq.answer}
          </div>
        </div>
      `;

      faqAccordion.appendChild(card);
    });
  </script>
</body>

</html>