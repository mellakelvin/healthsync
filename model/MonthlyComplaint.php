<?php
class MonthlyComplaint
{
  private $conn;
  private $illnesses = [
    'Fever', 'Headache', 'Cough', 'Colds', 'Allergy',
    'Abdominal Cramps', 'Menstrual Cramps', 'Diarrhea',
    'Muscle Pain', 'Toothache', 'Epigastric Pain',
    'Tonsillitis', 'Wound', 'Vertigo', 'Sprain'
  ];
  private $departments = [
    'College of Engineering & Architecture',
    'College of Criminal Justice Education',
    'College of Arts & Sciences',
    'College of Teacher Education',
    'College of Human Sciences',
    'College of Information Technology Education',
    'College of Pharmacy',
    'College of Hospitality/Tourism Management',
    'College of Business Management & Accountancy',
    'College of Health Sciences'
  ];

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function getAll($month)
  {
    $stmt = $this->conn->prepare("SELECT * FROM monthly_complaints WHERE month = ?");
    $stmt->bind_param("s", $month);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
      $data[$row['department']] = $row;
    }

    foreach ($this->departments as $dept) {
      if (!isset($data[$dept])) {
        $data[$dept] = [
          'department' => $dept,
          'month' => $month
        ];
        foreach ($this->illnesses as $illness) {
          $data[$dept][$illness] = 0;
        }
        $this->create($dept, $month);
      }
    }

    return array_values($data);
  }

  public function create($department, $month)
  {
    $fields = implode(", ", array_map(fn($illness) => "`$illness`", $this->illnesses));
    $placeholders = implode(", ", array_fill(0, count($this->illnesses), 0));
    $query = "INSERT INTO monthly_complaints (department, month, $fields) VALUES (?, ?, $placeholders)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ss", $department, $month);
    $stmt->execute();
  }

  public function update($department, $month, $illness, $value)
  {
    $query = "UPDATE monthly_complaints SET `$illness` = ? WHERE department = ? AND month = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("iss", $value, $department, $month);
    return $stmt->execute();
  }

  public function getIllnesses()
  {
    return $this->illnesses;
  }

  public function getDepartments()
  {
    return $this->departments;
  }
}
