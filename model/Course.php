<?php

class Courses
{
  private mysqli $conn;
  private string $table = 'courses';

  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }

  public function getAll(): array
  {
    $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
    $result = $this->conn->query($sql);

    return $result->fetch_all(MYSQLI_ASSOC);
  }
}
