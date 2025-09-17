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
if (!in_array($roleId, [1, 2])) {
  header("Location: /healthsync");
  exit;
}

$roleFilter = $_GET['role'] ?? null;
$statusFilter = $_GET['status'] ?? null;
$excludeRolesParam = $_GET['exclude_roles'] ?? null;
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
  $strPreset = "from date $startDate to $endDate";
}

$excludeRoles = [];
if ($excludeRolesParam) {
  $excludeRoles = array_map('intval', explode(',', $excludeRolesParam));
}

$sql = "
  SELECT
    u.id_number,
    CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) AS full_name,
    u.email_address,
    u.phone_number,
    u.gender,
    c.name AS course_name,
    y.name AS year_name,
    r.name AS role_name,
    r.id AS role_id,
    u.status,
    u.created_at
  FROM users u
  LEFT JOIN roles r ON u.role = r.id
  LEFT JOIN courses c ON u.course = c.id
  LEFT JOIN year y ON u.year = y.id
  WHERE 1=1
";

$params = [];
$types = '';

if ($roleFilter) {
  $sql .= " AND u.role = ?";
  $params[] = $roleFilter;
  $types .= 'i';
}

if (!empty($excludeRoles)) {
  $placeholders = implode(',', array_fill(0, count($excludeRoles), '?'));
  $sql .= " AND u.role NOT IN ($placeholders)";
  $params = array_merge($params, $excludeRoles);
  $types .= str_repeat('i', count($excludeRoles));
}

if ($statusFilter) {
  $sql .= " AND u.status = ?";
  $params[] = $statusFilter;
  $types .= 's';
}

if ($startDate && $endDate) {
  $sql .= " AND DATE(u.created_at) BETWEEN ? AND ?";
  $params[] = $startDate;
  $params[] = $endDate;
  $types .= 'ss';
}

$stmt = $mysqli->prepare($sql);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

foreach (range('A', 'H') as $col) {
  $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->mergeCells('A1:H1');
$sheet->setCellValue('A1', "User Report $strPreset");
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->fromArray([
  'ID Number',
  'Full Name',
  'Email Address',
  'Phone Number',
  'Gender',
  'Course',
  'Year',
  'Role'
], null, 'A2');

$rowIndex = 3;
foreach ($users as $user) {
  $role = strtolower($user['role_name']);
  $course = null;
  $year = null;

  if ($role === 'student') {
    $course = $user['course_name'];
    $year = $user['year_name'];
  } elseif (str_starts_with($role, 'employee')) {
    $course = $user['course_name'];
  }

  $sheet->setCellValue('A' . $rowIndex, $user['id_number']);
  $sheet->setCellValue('B' . $rowIndex, $user['full_name']);
  $sheet->setCellValue('C' . $rowIndex, $user['email_address']);
  $sheet->setCellValue('D' . $rowIndex, $user['phone_number']);
  $sheet->setCellValue('E' . $rowIndex, ucfirst(strtolower($user['gender'])));
  $sheet->setCellValue('F' . $rowIndex, $course);
  $sheet->setCellValue('G' . $rowIndex, $year);
  $sheet->setCellValue('H' . $rowIndex, ucfirst($user['role_name']));
  $rowIndex++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
$filename = "User_Report_" . ($startDate ?? 'ALL') . "-" . ($endDate ?? 'ALL') . ".xlsx";
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
