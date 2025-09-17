<?php
require '../utils/response.php';
if (session_status() == PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  session_destroy();
  header("Location: /healthsync/");
  response(['message' => 'success']);
}
