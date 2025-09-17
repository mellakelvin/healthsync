<?php
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '12M');

require __DIR__ . '/../utils/connection.php';
require __DIR__ . '/../utils/response.php';
require __DIR__ . '/../model/Notification.php';
require __DIR__ . '/../model/User.php';
require __DIR__ . '/../utils/Storage.php';
$userModel = new User($mysqli);
$notificationModel =  new Notification($mysqli);
$doctors = $userModel->getUsersByRole(1);

$data = $_POST;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($data['first_name'], $data['last_name'], $data['email_address'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
  }
  $imageUrl = null;
  if (
    isset($_FILES['image_url']) &&
    $_FILES['image_url']['error'] === UPLOAD_ERR_OK
  ) {
    $storage = new Storage();
    $url = $storage->save($_FILES['image_url']);
    $imageUrl = $url;
  }

  $stmt = $mysqli->prepare('INSERT INTO users (
        id_number,
        first_name,
        middle_name,
        last_name,
        address,
        phone_number,
        year,
        course,
        gender,
        email_address,
        password,
        role,
        image_url
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
  $data['year'] = (!isset($data['year']) || $data['year'] === '' || $data['year'] === 'NaN') ? null : (int)$data['year'];

  $stmt->bind_param(
    'ssssssissssis',
    $data['id_number'],
    $data['first_name'],
    $data['middle_name'],
    $data['last_name'],
    $data['address'],
    $data['phone_number'],
    $data['year'],
    $data['course'],
    $data['gender'],
    $data['email_address'],
    $data['password'],
    $data['role'],
    $imageUrl
  );
  $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

  try {
    if ($stmt->execute()) {
      $_SESSION['role-id'] = $data['role'];
      $_SESSION['auth-id'] = $stmt->insert_id;
      foreach ($doctors as $a) {
        $notificationModel->sendNotification($a['id'], null, 'NEW USER', 'New user request pending verification.');
      }
      response(['success' => true, 'user_id' => $stmt->insert_id]);
    } else {
      throw new Exception($stmt->error);
    }
  } catch (Exception $e) {
    $message = $e->getMessage();

    if (strpos($message, 'Duplicate entry') !== false) {
      if (strpos($message, 'email') !== false) {
        response(['error' => 'Email already exists.'], 409);
      } elseif (strpos($message, 'id_number') !== false) {
        response(['error' => 'ID number already exists.'], 409);
      } else {
        response(['error' => 'Duplicate entry.'], 409);
      }
    } else {
      response(['error' => $message], 500);
    }
  }
  $stmt->close();
  $mysqli->close();
}
