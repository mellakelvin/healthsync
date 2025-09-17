<?php

class Equipment
{
  private mysqli $conn;
  private string $table = 'equipments';

  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }

  public function getAll(string $type = null): array
  {
    if ($type !== null) {
      $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE type = ? ORDER BY created_at DESC");
      $stmt->bind_param("s", $type);
      $stmt->execute();
      $result = $stmt->get_result();
    } else {
      $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
      $result = $this->conn->query($sql);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function findById(int $id): ?array
  {
    $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    $stmt->close();
    return $item ?: null;
  }

  public function create(string $name, string $type, int $stocks, ?string $image = null): bool
  {
    $stmt = $this->conn->prepare("
      INSERT INTO {$this->table} (name, type, stocks, image)
      VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssis", $name, $type, $stocks, $image);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
  }

  public function update(int $id, string $name, string $type, int $stocks, ?string $image): bool
  {
    $stmt = $this->conn->prepare("
      UPDATE {$this->table}
      SET name = ?, type = ?, stocks = ?, image = ?, updated_at = CURRENT_TIMESTAMP
      WHERE id = ?
    ");
    $stmt->bind_param("ssisi", $name, $type, $stocks, $image, $id);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
  }

  public function delete(int $id): bool
  {
    $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
  }

  public function updateStocks(int $id, int $newStocks): bool
  {
    $stmt = $this->conn->prepare("UPDATE {$this->table} SET stocks = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("ii", $newStocks, $id);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
  }
}
