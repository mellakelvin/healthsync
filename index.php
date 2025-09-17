<?php
require __DIR__ . '/utils/connection.php';

if (session_status() == PHP_SESSION_NONE) session_start();

$authId = $_SESSION['auth-id'] ?? null;
$roleId = $_SESSION['role-id'] ?? null;

if (!$authId || !$roleId) {
  include 'sign-in.php';
  exit;
}

$stmt = $mysqli->prepare("SELECT status FROM users WHERE id = ?");
$stmt->bind_param("i", $authId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
  session_unset();
  session_destroy();
  include 'sign-in.php';
  exit;
}

switch (strtoupper($user['status'])) {
  case 'PENDING':
    include 'pending.php';
    exit;
  case 'REJECTED':
    include 'rejected.php';
    exit;
  case 'INACTIVE':
    include 'inactive.php';
    exit;
}

if ($roleId === 1 || $roleId === 3) {
  include __DIR__ . '/admin/index.php';
} else if ($roleId === 2) {
  include __DIR__ . '/users/labtech/index.php';
} else if($roleId === 7) {
  include __DIR__ . '/users/dentist/index.php';
}else{
  include __DIR__ . '/users/dashboard.php';
}
