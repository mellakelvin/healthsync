<?php
require_once __DIR__ . '/../../utils/connection.php';

$range = $_GET['range'] ?? 'daily';
$type = $_GET['type'] ?? null;

$labels = [];
$data = [];

$where = "1";
$params = [];
$types = "";

if ($type) {
  $where .= " AND type = ?";
  $params[] = $type;
  $types .= "s";
}

switch ($range) {
  case 'weekly':
    $sql = "
      SELECT WEEK(date, 1) as week, COUNT(*) as total
      FROM appointments
      WHERE YEAR(date) = YEAR(CURDATE())
        AND MONTH(date) = MONTH(CURDATE())
        AND $where
      GROUP BY week
      ORDER BY week
    ";
    $stmt = $mysqli->prepare($sql);
    if ($type) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
    $data = [0, 0, 0, 0];
    $firstWeekOfMonth = (int)date('W', strtotime(date('Y-m-01')));

    while ($row = $result->fetch_assoc()) {
      $relativeWeek = (int)$row['week'] - $firstWeekOfMonth + 1;
      if ($relativeWeek >= 1 && $relativeWeek <= 4) {
        $data[$relativeWeek - 1] = (int)$row['total'];
      }
    }
    break;

  case 'monthly':
    $sql = "
      SELECT DATE_FORMAT(date, '%Y-%m') as label, COUNT(*) as total
      FROM appointments
      WHERE date >= CURDATE() - INTERVAL 12 MONTH
        AND $where
      GROUP BY label
      ORDER BY label
    ";
    $stmt = $mysqli->prepare($sql);
    if ($type) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    for ($i = 12; $i >= 0; $i--) {
      $date = date('Y-m', strtotime("-$i months"));
      $labels[] = date('M Y', strtotime("$date-01"));
      $data[] = 0;
    }

    while ($row = $result->fetch_assoc()) {
      $key = array_search(date('M Y', strtotime($row['label'] . '-01')), $labels);
      if ($key !== false) {
        $data[$key] = (int)$row['total'];
      }
    }
    break;

  case 'daily':
  default:
    $start = -3;
    $end = 3;
    $labels = [];
    $dataMap = [];

    for ($i = $start; $i <= $end; $i++) {
      $date = date('Y-m-d', strtotime("$i days"));
      $labels[] = date('M j', strtotime($date));
      $dataMap[$date] = 0;
    }

    $sql = "
      SELECT DATE(date) as label, COUNT(*) as total
      FROM appointments
      WHERE date BETWEEN CURDATE() - INTERVAL 3 DAY AND CURDATE() + INTERVAL 3 DAY
        AND $where
      GROUP BY DATE(date)
      ORDER BY DATE(date)
    ";
    $stmt = $mysqli->prepare($sql);
    if ($type) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $rowDate = $row['label'];
      if (isset($dataMap[$rowDate])) {
        $dataMap[$rowDate] = (int)$row['total'];
      }
    }

    $data = array_values($dataMap);
    break;
}

echo json_encode([
  'labels' => $labels,
  'data' => $data
]);
