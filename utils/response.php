<?php
function response($val = [], $code = 200)
{
  http_response_code($code);
  header('Content-Type: application/json');
  echo json_encode($val);
  exit;
}
