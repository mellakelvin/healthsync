<?php
require __DIR__ . '/../utils/Storage.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

$isJson = str_starts_with($contentType, 'application/json');
$isMultipart = str_starts_with($contentType, 'multipart/form-data');

$id = null;
$status = null;
$file = $_FILES['file'] ?? null;

if ($isJson) {
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['id'] ?? null;
  $status = $data['status'] ?? null;
} elseif ($isMultipart) {
  $id = $_POST['id'] ?? null;
  $status = $_POST['status'] ?? null;
}

if (!$id || !$status) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing required fields']);
  exit;
}

$result = [
  'id' => $id,
  'status' => $status,
];

if ($file && $file['error'] === UPLOAD_ERR_OK) {
  try {
    $storage = new Storage();
    $url = $storage->save($file);
    $result['file_url'] = $url;
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'File upload failed: ' . $e->getMessage()]);
    exit;
  }
}

echo json_encode(['success' => true, 'data' => $result]);
