<?php
class LabResult
{
  private mysqli $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function findById(int $id): ?array
  {
    $stmt = $this->conn->prepare("SELECT * FROM lab_results WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc() ?: null;
  }

  public function findByAppointmentId(int $appointmentId): ?array
  {
    $stmt = $this->conn->prepare("SELECT * FROM lab_results WHERE appointment_id = ?");
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc() ?: null;
  }

  public function getAll(): array
  {
    $result = $this->conn->query("SELECT * FROM lab_results ORDER BY created_at DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function create(int $appointmentId, string $resultUrl): bool
  {

    $stmt = $this->conn->prepare("
      INSERT INTO lab_results (appointment_id, result_url)
      VALUES (?, ?)
    ");
    $stmt->bind_param("is", $appointmentId, $resultUrl);
    return $stmt->execute();
  }

  public function update(int $id, string $resultUrl): bool
  {
    $stmt = $this->conn->prepare("
      UPDATE lab_results SET receipt_url = ?,  updated_at = NOW() WHERE id = ?
    ");
    $stmt->bind_param("si", $resultUrl, $id);
    return $stmt->execute();
  }

  public function delete(int $id): bool
  {
    $stmt = $this->conn->prepare("DELETE FROM lab_results WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }
}
