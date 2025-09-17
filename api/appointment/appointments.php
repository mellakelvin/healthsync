<?php
require '../../utils/connection.php';
require '../../utils/response.php';
require '../../model/User.php';
header('Content-Type: application/json');

$userId = $_SESSION['auth-id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($userId !== null) {
    $conn = new User($mysqli);
    $appointments = $conn->getAppointments($userId);
    response($appointments);
    exit;
  }
  response([], 404);
}
