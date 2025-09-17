<?php
require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../model/Appointment.php';
require_once __DIR__ . '/../../vendor/autoload.php';

if (session_status() == PHP_SESSION_NONE) session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

header('Content-Type: application/json');

$roleId = $_SESSION['role-id'];
$userId = $_SESSION['auth-id'];

if (!in_array($roleId, [1, 2, 3])) {
  header("Location: /healthsync");
  exit;
}

$type = $_GET['type'] ?? null;
$status = $_GET['status'] ?? null;
$role = isset($_GET['role']) ? (int)$_GET['role'] : null;
$preset = $_GET['preset'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;
$strPreset = null;
$strType = "All";

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

if ($startDate != null && $endDate != null) {
  $strPreset = " from date " . $startDate . " to " . $endDate;
}

if ($type != null) {
  $strType = ucfirst($type);
}
$appointment = new Appointment($mysqli);
$data = $appointment->generateReport($type, $status, $startDate, $endDate, $role);

$spreadsheet = new Spreadsheet();
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->mergeCells("A1:I1");
$activeWorksheet->setCellValue('A' . 1, $strType . " reports " . $strPreset);
$spreadsheet->getActiveSheet()->getStyle('A1')
  ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$startingIndex = 3;
$activeWorksheet->setCellValue('A' . 2, "ID Number");
$activeWorksheet->setCellValue('B' . 2, "Full Name");
$activeWorksheet->setCellValue('C' . 2, "Email Address");
$activeWorksheet->setCellValue('D' . 2, "Phone Number");
$activeWorksheet->setCellValue('E' . 2, "Role");
$activeWorksheet->setCellValue('F' . 2, "Appointment Date");
$activeWorksheet->setCellValue('G' . 2, "Appointment Time");
$activeWorksheet->setCellValue('H' . 2, "Status");
$activeWorksheet->setCellValue('I' . 2, "Description");
$activeWorksheet->setCellValue('J' . 2, "Lab Result");
$activeWorksheet->setCellValue('K' . 2, "Result Created At");

foreach ($data as $key => $value) {
  $activeWorksheet->setCellValue('A' . $startingIndex, $value['id_number']);
  $activeWorksheet->setCellValue('B' . $startingIndex, $value['full_name']);
  $activeWorksheet->setCellValue('C' . $startingIndex, $value['email_address']);
  $activeWorksheet->setCellValue('D' . $startingIndex, $value['phone_number']);
  $activeWorksheet->setCellValue('E' . $startingIndex, ucwords($value['role_name']));
  $activeWorksheet->setCellValue('F' . $startingIndex, $value['date']);
  $activeWorksheet->setCellValue('G' . $startingIndex, $value['time']);
  $activeWorksheet->setCellValue('H' . $startingIndex, $value['status']);
  switch ($value['status']) {
    case 'COMPLETED':
      $spreadsheet->getActiveSheet()->getStyle('H' . $startingIndex)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FF9effa6');
      break;
    case 'PENDING':
      $spreadsheet->getActiveSheet()->getStyle('H' . $startingIndex)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFf9ff9e');
      break;
    case 'CANCELLED':
      $spreadsheet->getActiveSheet()->getStyle('H' . $startingIndex)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFff9e9e');
      break;
    case 'CONFIRMED':
      $spreadsheet->getActiveSheet()->getStyle('H' . $startingIndex)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FF9ec3ff');
      break;
    default:
      break;
  }
  $activeWorksheet->setCellValue('I' . $startingIndex, $value['description']);
  $activeWorksheet->setCellValue(
    'J' . $startingIndex,
    isset($value['result_url']) && $value['result_url']
      ? "http://127.0.0.1/" . $value['result_url']
      : ""
  );

  $activeWorksheet->setCellValue('K' . $startingIndex, $value['result_created_at']);
  $startingIndex++;
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=Appointment_Report_$startDate-$endDate.xlsx");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
