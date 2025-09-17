<?php
require '../../utils/connection.php';
require '../../utils/response.php';
require '../../model/User.php';
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) session_start();

$userId = $_SESSION['auth-id'] ?? null;
$roleId = $_SESSION['role-id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $roleId == 1) {
  $rawData = file_get_contents("php://input");
  $data = json_decode($rawData, true);

  $status = $data['status'] ?? null;
  $roles = $data['roles'] ?? [];

  $userModel = new User($mysqli);
  $users = [];

  if (is_array($roles) && count($roles) > 0) {
    foreach ($roles as $rId) {
      if (is_int($rId)) {
        $users = array_merge($users, $userModel->getUsersByRole($rId, $status));
      }
    }
  } else {
    $users = $userModel->getAll($status);
  }

  $users ? response($users) : response([]);
} else {
  response(['error' => 'Unauthorized'], 403);
}
