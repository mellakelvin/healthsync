<?php
require_once __DIR__ . '/../../utils/connection.php';

$type = $_GET['type'] ?? null;

$where = "1";
$params = [];
$types = "";

if ($type) {
  $where .= " AND type = ?";
  $params[] = $type;
  $types .= "s";
}

$sql = "
  SELECT service, COUNT(*) as total
  FROM appointments
  WHERE $where AND service IS NOT NULL AND service != ''
  GROUP BY service
  ORDER BY total DESC
  LIMIT 10
";

$stmt = $mysqli->prepare($sql);
if ($type) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
  $labels[] = $row['service'];
  $data[] = (int)$row['total'];
}

echo json_encode([
  'labels' => $labels,
  'data' => $data
]);
