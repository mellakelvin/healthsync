<?php
require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../../model/MonthlyComplaint.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$month = $_GET['month'] ?? date('Y-m');
$model = new MonthlyComplaint($mysqli);
$data = $model->getAll($month);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("MCC");

$headers = [];
if (!empty($data)) {
  $headers = array_keys($data[0]);
} else {
  $headers = ['department'];
}

$endCol = chr(65 + count($headers) - 1);
$sheet->mergeCells("A1:{$endCol}1");
$sheet->setCellValue('A1', "Monthly Chief Complaints – $month");
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$col = 'A';
foreach ($headers as $header) {
  $sheet->setCellValue($col . '2', ucfirst($header));
  $sheet->getColumnDimension($col)->setAutoSize(true);
  $col++;
}

$rowIndex = 3;
foreach ($data as $row) {
  $col = 'A';
  foreach ($headers as $header) {
    $sheet->setCellValue($col . $rowIndex, $row[$header]);
    $col++;
  }
  $rowIndex++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=MCC_Report_$month.xlsx");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
