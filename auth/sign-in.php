<?php
require "../utils/connection.php";
require '../model/User.php';
require '../utils/response.php';

if (session_status() == PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $rawData = file_get_contents("php://input");
  $data = json_decode($rawData, true);

  $email = trim($data['email-address'] ?? '');
  $password = $data['password'] ?? '';
  try {
    $user = new User($mysqli);
    $auth = $user->findByEmail($email);
    if ($auth !== null && password_verify($password, $auth['password'])) {
      $_SESSION['role-id'] = $auth['role'];
      $_SESSION['auth-id'] = $auth['id'];
    } else {
      response(['message' => 'Invalid credentials'], 401);
    }
  } catch (Exception $e) {
    response(['error' => 'Server error'], 500);
  }
}
