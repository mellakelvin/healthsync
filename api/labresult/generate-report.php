<?php
require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../vendor/autoload.php';

if (session_status() == PHP_SESSION_NONE) session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

header('Content-Type: application/json');

$roleId = $_SESSION['role-id'] ?? null;
if (!in_array($roleId, [1, 2, 3])) {
  header("Location: /healthsync");
  exit;
}

$preset = $_GET['preset'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

$strPreset = null;

if ($preset && $preset !== 'custom') {
  $today = new DateTime();
  switch ($preset) {
    case 'this_month':
      $startDate = $today->format('Y-m-01');
      $endDate = $today->format('Y-m-t');
      break;
    case 'last_month':
      $today->modify('first day of last month');
      $startDate = $today->format('Y-m-01');
      $endDate = $today->format('Y-m-t');
      break;
    case 'this_year':
      $startDate = $today->format('Y-01-01');
      $endDate = $today->format('Y-12-31');
      break;
  }
}

if ($startDate && $endDate) {
  $strPreset = "from $startDate to $endDate";
}

$sql = "
  SELECT
    lr.id,
    lr.result_url,
    lr.created_at,
    lr.updated_at,
    a.id AS appointment_id,
    a.date AS appointment_date,
    a.time AS appointment_time,
    CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) AS patient_name,
    u.id_number
  FROM lab_results lr
  LEFT JOIN appointments a ON lr.appointment_id = a.id
  LEFT JOIN users u ON a.user_id = u.id
  WHERE 1=1
";

$params = [];
$types = '';

if ($startDate && $endDate) {
  $sql .= " AND DATE(lr.created_at) BETWEEN ? AND ?";
  $params[] = $startDate;
  $params[] = $endDate;
  $types .= 'ss';
}

$sql .= " ORDER BY lr.created_at DESC";

$stmt = $mysqli->prepare($sql);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

foreach (range('A', 'H') as $col) {
  $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->mergeCells('A1:H1');
$sheet->setCellValue('A1', "Lab Results Report $strPreset");
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->fromArray([
  'Lab Result ID',
  'Appointment ID',
  'Patient ID Number',
  'Patient Name',
  'Appointment Date',
  'Appointment Time',
  'Result URL',
  'Created At'
], null, 'A2');

$rowIndex = 3;
foreach ($data as $row) {
  $sheet->setCellValue('A' . $rowIndex, $row['id']);
  $sheet->setCellValue('B' . $rowIndex, $row['appointment_id']);
  $sheet->setCellValue('C' . $rowIndex, $row['id_number']);
  $sheet->setCellValue('D' . $rowIndex, $row['patient_name']);
  $sheet->setCellValue('E' . $rowIndex, $row['appointment_date']);
  $sheet->setCellValue('F' . $rowIndex, $row['appointment_time']);

  if (!empty($row['result_url'])) {
    $url = "http://127.0.0.1/" . $row['result_url'];
    $sheet->setCellValue('G' . $rowIndex, $url);
    $sheet->getCell('G' . $rowIndex)->getHyperlink()->setUrl($url);
  } else {
    $sheet->setCellValue('G' . $rowIndex, '');
  }

  $sheet->setCellValue('H' . $rowIndex, $row['created_at']);
  $rowIndex++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
$filename = "Lab_Results_Report_" . ($startDate ?? 'ALL') . "-" . ($endDate ?? 'ALL') . ".xlsx";
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
