<?php
require __DIR__ . '/../../utils/connection.php';
require __DIR__ .  '/../../model/MonthlyComplaint.php';
header('Content-Type: application/json');

$complaintModel = new MonthlyComplaint($mysqli);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $month = $_GET['month'] ?? date('Y-m');
  echo json_encode($complaintModel->getAll($month));
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents("php://input"), true);
  $department = $data['department'];
  $month = $data['month'];
  $illness = $data['illness'];
  $value = intval($data['value']);

  if ($complaintModel->update($department, $month, $illness, $value)) {
    echo json_encode(['success' => true]);
  } else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Update failed']);
  }
  exit;
}
