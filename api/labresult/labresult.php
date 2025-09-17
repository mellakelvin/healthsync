<?php
require __DIR__ . "/../../utils/connection.php";
require __DIR__ . '/../../model/LabResult.php';
require __DIR__ . '/../../utils/response.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$labResultModel = new LabResult($mysqli);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  $id = $_GET['id'] ?? null;
  $appointmentId = $_GET['appointment_id'] ?? null;

  if ($id) {
    $result = $labResultModel->findById((int)$id);
    return response($result ?: ['error' => 'Not found'], $result ? 200 : 404);
  }

  if ($appointmentId) {
    $result = $labResultModel->findByAppointmentId((int)$appointmentId);
    return response($result ?: ['error' => 'Not found'], $result ? 200 : 404);
  }

  $results = $labResultModel->getAll();
  return response($results);
}

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if ($method === 'POST') {
  $appointmentId = (int)($data['appointment_id'] ?? 0);
  $resultUrl = trim($data['result_url'] ?? '');
  if (!$appointmentId || !$resultUrl) {
    return response(['error' => 'Missing required fields'], 400);
  }

  $success = $labResultModel->create($appointmentId, $resultUrl);

  return response(['success' => $success], $success ? 201 : 500);
}

if ($method === 'PUT') {
  $id = (int)($data['id'] ?? 0);
  $resultUrl = trim($data['result_url'] ?? '');

  if (!$id || !$resultUrl) {
    return response(['error' => 'Missing required fields'], 400);
  }

  $success = $labResultModel->update($id, $resultUrl);
  return response(['success' => $success], $success ? 200 : 500);
}

if ($method === 'DELETE') {
  $id = (int)($data['id'] ?? 0);

  if (!$id) {
    return response(['error' => 'Missing id'], 400);
  }

  $success = $labResultModel->delete($id);
  return response(['success' => $success], $success ? 200 : 500);
}

return response(['error' => 'Method not allowed'], 405);
