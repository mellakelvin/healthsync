<?php
require '../../utils/connection.php';
require '../../utils/response.php';
require '../../model/Appointment.php';

header('Content-Type: application/json');

$userId = $_SESSION['auth-id'] ?? null;
$roleId = $_SESSION['role-id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !in_array($roleId, [1, 2, 3, 7])) {
  response(['error' => 'Unauthorized'], 403);
}

$input = json_decode(file_get_contents('php://input'), true);
$type = $input['type'] ?? null;
$status = $input['status'] ?? null;

$appointments = (new Appointment($mysqli))->getAll($type, $status);
response($appointments);
