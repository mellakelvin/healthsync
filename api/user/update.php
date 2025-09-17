<?php
session_start();
require __DIR__ . "/../../utils/connection.php";
require __DIR__ . "/../../model/User.php";
require __DIR__ . "/../../utils/Storage.php";

$userModel = new User($mysqli);
$userId = $_SESSION['auth-id'] ?? null;

if (!$userId) {
  header('Content-Type: application/json');
  http_response_code(401);
  echo json_encode(['success' => false, 'error' => 'Unauthorized']);
  exit;
}

$firstName = trim($_POST['first_name'] ?? '');
$middleName = trim($_POST['middle_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phoneNumber = trim($_POST['phone_number'] ?? '');
$gender = $_POST['gender'] ?? '';
$year = intval($_POST['year'] ?? 1);
$course = intval($_POST['course'] ?? 1);
$password = $_POST['password'] ?? null;
$imagePath = null;

if (!$firstName || !$lastName || !$address || !$phoneNumber || !$gender) {
  header('Content-Type: application/json');
  http_response_code(400);
  echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
  exit;
}

if (!empty($_FILES['image']['name'])) {
  try {
    $storage = new Storage();
    $imagePath = $storage->save($_FILES['image']);
  } catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
  }
}

try {
  $updated = $userModel->update(
    $userId,
    $firstName,
    $middleName,
    $lastName,
    $address,
    $phoneNumber,
    $gender,
    $year,
    $course,
    $password,
    $imagePath
  );

  header('Content-Type: application/json');
  if ($updated) {
    echo json_encode(['success' => true]);
  } else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update profile.']);
  }
} catch (Exception $e) {
  header('Content-Type: application/json');
  http_response_code(500);
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
