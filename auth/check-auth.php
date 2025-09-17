<?php
if (session_status() == PHP_SESSION_NONE) session_start();

$authSession = $_SESSION['auth-id'] ?? null;

if ($authSession === null) {
  http_response_code(403);
  header('Content-Type: application/json');
  echo json_encode(['message' => 'not-authenticated']);
  exit;
}

http_response_code(200);
header('Content-Type: application/json');
echo json_encode(['message' => 'authenticated', 'auth-id' => $authSession]);
exit;
