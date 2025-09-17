<?php
require __DIR__ . '/../../utils/connection.php';
require __DIR__ . '/../../model/Equipment.php';
require __DIR__ . '/../../utils/response.php';
require __DIR__ . '/../../utils/Storage.php';

header('Content-Type: application/json');
session_start();

$equipment = new Equipment($mysqli);
$storage = new Storage();

$method = $_SERVER['REQUEST_METHOD'];
$roleId = $_SESSION['role-id'] ?? null;
if ($roleId !== 1) exit;

switch ($method) {
  case 'GET':
    if (isset($_GET['id'])) {
      $item = $equipment->findById((int)$_GET['id']);
      if ($item) {
        echo json_encode($item);
      } else {
        http_response_code(404);
        echo json_encode(['error' => 'Item not found']);
      }
    } else {
      $type = $_GET['type'] ?? null;
      echo json_encode($equipment->getAll($type));
    }
    break;

  case 'POST':
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? null;
    $type = $_POST['type'] ?? null;
    $stocks = isset($_POST['stocks']) ? (int)$_POST['stocks'] : null;
    $action = $_POST['action'] ?? null;

    if (!$name || !$type || is_null($stocks)) {
      http_response_code(400);
      echo json_encode(['error' => 'Missing fields']);
      break;
    }

    $image = null;
    try {
      if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $storage->save($_FILES['image']);
      }
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
      break;
    }

    if ($action === 'create') {
      $success = $equipment->create($name, $type, $stocks, $image);
    } else {
      $existing = $equipment->findById((int)$id);
      if (!$existing) {
        http_response_code(404);
        echo json_encode(['error' => 'Item not found']);
        break;
      }

      if (!$image) {
        $image = $existing['image'];
      }

      $success = $equipment->update((int)$id, $name, $type, $stocks, $image);
    }

    echo json_encode(['success' => $success]);
    break;

  case 'DELETE':
    $input = json_decode(file_get_contents('php://input'), true);
    $id = (int) ($input['id'] ?? 0);

    if (!$id) {
      http_response_code(400);
      echo json_encode(['error' => 'Missing id']);
      break;
    }

    $success = $equipment->delete($id);
    echo json_encode(['success' => $success]);
    break;

  default:
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    break;
}
