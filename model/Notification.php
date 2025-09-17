<?php

class Notification
{
  private mysqli $conn;
  private string $tableName = 'notifications';

  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }

  public function getNotifications(int $recipient_id): array
  {
    $stmt = $this->conn->prepare("SELECT * FROM {$this->tableName} WHERE recipient_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $recipient_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $notifications = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    return $notifications;
  }

  public function sendNotification(int $recipient_id, ?int $sender_id = null, string $type, string $content, ?string $url = null): bool
  {
    $stmt = $this->conn->prepare(
      "INSERT INTO {$this->tableName} (recipient_id, sender_id, type, content, url) VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
      throw new Exception("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("iisss", $recipient_id, $sender_id, $type, $content, $url);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
  }
  public function markAsRead(int $id)
  {
    $stmt = $this->conn->prepare("UPDATE {$this->tableName} set is_read = 1 where id = ?");

    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
  }
}
