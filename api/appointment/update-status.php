<?php
require '../../utils/connection.php';
require '../../utils/response.php';
require '../../utils/Storage.php';
require '../../model/Appointment.php';
require '../../model/Notification.php';
require '../../model/User.php';

header('Content-Type: application/json');
session_start();

$roleId = $_SESSION['role-id'] ?? null;
$senderId = $_SESSION['auth-id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !in_array($roleId, [1, 2, 3, 7])) {
  response(['error' => 'Unauthorized'], 403);
}

$isJson = strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;

$id = null;
$status = null;
$note = null;
$file = $_FILES['file'] ?? null;

if ($isJson) {
  $input = json_decode(file_get_contents('php://input'), true);
  $id = $input['id'] ?? null;
  $status = $input['status'] ?? null;
  $note = (isset($input['status']) && $input['status'] === 'COMPLETED') ? ($input['note'] ?? null) : null;
} else {
  $id = $_POST['id'] ?? null;
  $status = $_POST['status'] ?? null;
  $note = (isset($_POST['status']) && $_POST['status'] === 'COMPLETED') ? ($_POST['note'] ?? null) : null;
}

if (!$id || !$status) {
  response(['error' => 'Missing id or status'], 400);
}

$validStatuses = ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'];
if (!in_array($status, $validStatuses)) {
  response(['error' => 'Invalid status value'], 422);
}

$appointmentModel = new Appointment($mysqli);

$success = $appointmentModel->setStatus((int)$id, $status, $file, $note);

if ($success) {
  $stmt = $mysqli->prepare("SELECT user_id FROM appointments WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $result = $stmt->get_result()->fetch_assoc();
  $userId = $result['user_id'] ?? null;
  $stmt->close();

  if ($userId) {
    $message = "Your appointment has been $status.";
    $url = "/users/appointment/view.php?id=" . $id;
    $notificationModel = new Notification($mysqli);
    $notificationModel->sendNotification($userId, $senderId, 'APPOINTMENT', $message, $url);
  }

  response(['success' => true, 'id' => $id, 'new_status' => $status]);
} else {
  response(['error' => 'Failed to update status'], 500);
}
