<?php
require '../../utils/connection.php';
require __DIR__ . '/../../model/Notification.php';
require __DIR__ . '/../../model/User.php';
require __DIR__ . '/../../utils/Storage.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = $_POST;

  $date = $data['date'];
  $time = $data['time'];
  $type = $data['type'];
  $service = $data['service'];
  $description = $data['description'];
  $receipt = $_FILES['receipt'];

  $userId = $_SESSION['auth-id'];
  $receiptUrl = null;
  if ($receipt != null) {
    $storage = new Storage();
    $receiptUrl = $storage->save($receipt);
  }
  $stmt = $mysqli->prepare('INSERT INTO appointments (user_id, type, time, date, service, description, receipt_url) VALUES (?, ?, ?, ?, ?, ?, ?)');
  $stmt->bind_param('issssss', $userId, $type, $time, $date, $service, $description, $receiptUrl);

  $stmt->execute();
  $insertedId = $mysqli->insert_id;
  $stmt->close();

  $notificationModel = new Notification($mysqli);
  $userModel = new User($mysqli);

  $labTech = $userModel->getUsersByRole(2);
  $doctors = $userModel->getUsersByRole(1);
  $nurses = $userModel->getUsersByRole(3);
  $dentists = $userModel->getUsersByRole(7);
  $admin = array_merge($doctors, $nurses);

  if ($type === 'clinic') {
    foreach ($admin as $a) {
      $notificationModel->sendNotification(
        $a['id'],
        $userId,
        'APPOINTMENT',
        'New appointment incoming',
        "./admin/appointments.php?id={$insertedId}"
      );
    }
  } elseif ($type === 'dentist') {
    foreach ($dentists as $d) {
      $notificationModel->sendNotification(
        $d['id'],
        $userId,
        'APPOINTMENT',
        'New dental appointment incoming',
        "./users/dentist/appointments.php?id={$insertedId}"
      );
    }
  } else {
    foreach ($labTech as $l) {
      $notificationModel->sendNotification(
        $l['id'],
        $userId,
        'APPOINTMENT',
        'New appointment incoming',
        "./users/labtech/appointments.php?id={$insertedId}"
      );
    }
  }

}
