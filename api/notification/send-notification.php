<?php
require __DIR__ . '/../../utils/connection.php';
require __DIR__ . '/../../utils/response.php';
require __DIR__ . '/../../model/Notification.php';

$roleId = $_SESSION['role-id'];


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  return response(['error' => 'Invalid request method.'], 405);
}

if ($roleId != 1 && $roleId != 2) {
  return response(['error' => 'Forbidden '], 403);
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!$data) {
  return response(['error' => 'Invalid JSON body.'], 400);
}

$required = ['recipient_id', 'sender_id', 'type', 'content'];
foreach ($required as $field) {
  if (empty($data[$field])) {
    return response(['error' => "Missing field: $field"], 400);
  }
}

$notificationModel = new Notification($mysqli);

try {
  $success = $notificationModel->sendNotification(
    (int) $data['recipient_id'],
    (int) $data['sender_id'],
    $data['type'],
    $data['content'],
    $data['url'] ?? null
  );

  if ($success) {
    response(['success' => true, 'message' => 'Notification sent.']);
  } else {
    response(['error' => 'Failed to send notification.'], 500);
  }
} catch (Exception $e) {
  response(['error' => $e->getMessage()], 500);
}
