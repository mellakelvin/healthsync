<?php
require_once __DIR__ . '/../../utils/connection.php';
require_once __DIR__ . '/../../model/Notification.php';

$notificationId = $_GET['notificationId'] ?? null;
$targetUrl = $_GET['target'] ?? null;

header('Content-Type: application/json');

if (!$notificationId || !$targetUrl) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing parameters']);
  exit;
}

$notificationModel = new Notification($mysqli);
$notificationModel->markAsRead($notificationId);

echo json_encode([
  'target' => $targetUrl
]);
exit;
