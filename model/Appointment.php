<?php
class Appointment
{
  private mysqli $conn;
  private string $table = 'appointments';

  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }

  public function create(array $data): bool
  {
    $stmt = $this->conn->prepare("
      INSERT INTO $this->table (user_id, type, description, date, time, status)
      VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
      'isssss',
      $data['user_id'],
      $data['type'],
      $data['description'],
      $data['date'],
      $data['time'],
      $data['status']
    );

    return $stmt->execute();
  }

  public function setStatus(int $id, string $status, ?array $file = null, ?string $note = null): bool

  {
    $this->conn->begin_transaction();

    try {
      $stmt = $this->conn->prepare("
      UPDATE {$this->table}
      SET status = ?, note = ?, updated_at = NOW()
      WHERE id = ?
    ");
      $stmt->bind_param('ssi', $status, $note, $id);

      if (!$stmt->execute()) {
        throw new Exception('Failed to update status.');
      }

      if ($status === 'COMPLETED' && $file && $file['error'] === UPLOAD_ERR_OK) {
        require_once __DIR__ . '/../utils/Storage.php';
        require_once __DIR__ . '/LabResult.php';

        $storage = new Storage();
        $url = $storage->save($file);

        $labResult = new LabResult($this->conn);
        if (!$labResult->create($id, $url)) {
          throw new Exception('Failed to create LabResult.');
        }
      }

      $this->conn->commit();
      return true;
    } catch (Exception $e) {
      $this->conn->rollback();
      error_log('setStatus error: ' . $e->getMessage());
      return false;
    }
  }


  public function getAll(?string $type = null, ?string $status = null): array
  {
    $conditions = [];
    $params = [];
    $types = '';

    if ($type) {
      $conditions[] = 'a.type = ?';
      $params[] = $type;
      $types .= 's';
    }

    if ($status) {
      $conditions[] = 'a.status = ?';
      $params[] = $status;
      $types .= 's';
    }

    $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $query = "
    SELECT
      a.*,
      u.id_number,
      u.first_name,
      u.middle_name,
      u.last_name,
      u.gender,
      u.email_address,
      u.phone_number,
      u.address,
      u.role,
      r.name AS role_name,
      u.course,
      c.code AS course_code,
      c.name AS course_name,
      u.year,
      y.name AS year_name,
      CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) AS full_name
    FROM {$this->table} a
    JOIN users u ON u.id = a.user_id
    LEFT JOIN roles r ON u.role = r.id
    LEFT JOIN courses c ON u.course = c.id
    LEFT JOIN year y ON u.year = y.id
    $whereClause
    ORDER BY a.date DESC, a.time DESC
  ";

    $stmt = $this->conn->prepare($query);

    if (!empty($params)) {
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
  }



  public function findByUser(int $userId, ?string $status = null, ?string $type = null): array
  {
    $query = "
        SELECT
            a.*,
            lr.id AS lab_result_id,
            lr.result_url,
            lr.created_at AS lab_result_created_at,
            lr.updated_at AS lab_result_updated_at
        FROM {$this->table} a
        LEFT JOIN lab_results lr ON lr.appointment_id = a.id
        WHERE a.user_id = ?
    ";

    $types = 'i';
    $params = [$userId];

    if ($status !== null) {
      $query .= " AND a.status = ?";
      $types .= 's';
      $params[] = $status;
    }

    if ($type !== null) {
      $query .= " AND a.type = ?";
      $types .= 's';
      $params[] = $type;
    }

    $query .= " ORDER BY a.date DESC, a.time DESC";

    $stmt = $this->conn->prepare($query);

    if ($stmt === false) {
      throw new Exception("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
  }


  public function delete(int $id): bool
  {
    $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
  }

  public function findById(int $id): ?array
  {
    $stmt = $this->conn->prepare("
    SELECT
      a.*,
      lr.result_url AS lab_result_url
    FROM appointments a
    LEFT JOIN lab_results lr ON lr.appointment_id = a.id
    WHERE a.id = ?
    LIMIT 1
  ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc() ?: null;
  }


  public function updateStatus(int $id, string $status): bool
  {
    $stmt = $this->conn->prepare("UPDATE $this->table SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $id);
    return $stmt->execute();
  }

  public function generateReport(?string $type, ?string $status = null, ?string $startDate = null, ?string $endDate = null, ?int $roleId = null): array
  {
    $conditions = [];
    $params = [];
    $types = '';

    if ($type) {
      $conditions[] = "a.type = ?";
      $params[] = $type;
      $types .= "s";
    }

    if ($status) {
      $conditions[] = 'a.status = ?';
      $params[] = $status;
      $types .= 's';
    }

    if ($startDate && $endDate) {
      $conditions[] = 'a.date BETWEEN ? AND ?';
      $params[] = $startDate;
      $params[] = $endDate;
      $types .= 'ss';
    }

    if ($roleId != 0 || $roleId != null) {
      $conditions[] = 'u.role = ?';
      $params[] = $roleId;
      $types .= 'i';
    }

    $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $query = "
    SELECT
      a.id,
      a.type,
      a.date,
      a.time,
      a.status,
      a.description,
      CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) AS full_name,
      u.id_number,
      r.name as role_name,
      u.email_address,
      u.phone_number,
      lr.result_url,
      lr.created_at AS result_created_at,
      lr.updated_at AS result_updated_at
    FROM {$this->table} a
    JOIN users u ON u.id = a.user_id
    LEFT JOIN roles r on u.role = r.id
    LEFT JOIN lab_results lr ON lr.appointment_id = a.id
    $whereClause
    ORDER BY a.date DESC, a.time DESC
  ";

    $stmt = $this->conn->prepare($query);

    if (!empty($params)) {
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
  }
}
