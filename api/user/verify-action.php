<?php
require '../../utils/connection.php';
require '../../utils/response.php';
require '../../model/User.php';

header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();

$roleId = $_SESSION['role-id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $roleId !== 1) {
  response(['error' => 'Forbidden'], 403);
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

$userId = $data['id'] ?? null;
$action = $data['action'] ?? null;

if (!$userId || !in_array($action, ['accept', 'reject'])) {
  response(['error' => 'Invalid request'], 400);
}

$status = $action === 'accept' ? 'ACTIVE' : 'REJECTED';

$userModel = new User($mysqli);
$success = $userModel->updateStatus((int)$userId, $status);

if ($success) {
  response(['message' => "User marked as {$status}"]);
} else {
  response(['error' => 'Failed to update user'], 500);
}
