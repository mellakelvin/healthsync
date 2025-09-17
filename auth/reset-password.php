<?php
require __DIR__ . '/../utils/connection.php';

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method not allowed']);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'] ?? null;
$newPassword = $input['password'] ?? null;

if (!$token || !$newPassword) {
  http_response_code(400);
  echo json_encode(['error' => 'Token and password are required.']);
  exit;
}

$stmt = $mysqli->prepare("SELECT id FROM users WHERE password_reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  http_response_code(404);
  echo json_encode(['error' => 'Invalid or expired token.']);
  exit;
}

$user = $result->fetch_assoc();

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$update = $mysqli->prepare("UPDATE users SET password = ?, password_reset_token = NULL WHERE id = ?");
$update->bind_param("si", $hashedPassword, $user['id']);

if ($update->execute()) {
  echo json_encode(['success' => true, 'message' => 'Password reset successfully.']);
} else {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to reset password.']);
}
