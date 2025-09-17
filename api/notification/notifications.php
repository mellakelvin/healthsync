<?php

require __DIR__ . '/../../utils/connection.php';
require __DIR__ . '/../../utils/response.php';
require __DIR__ . '/../../model/Notification.php';

$notificationModel = new Notification($mysqli);
$userId = $_SESSION['auth-id'];
$notifications = $notificationModel->getNotifications($userId);
response($notifications);