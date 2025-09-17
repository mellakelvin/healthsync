<?php
class User
{
  private mysqli $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }


  public function getAll(?string $status = null): array
  {
    if ($status !== null) {
      $stmt = $this->conn->prepare("
            SELECT
                users.*,
                roles.name AS role_name,
                courses.code AS course_code,
                courses.name AS course_name,
                year.name AS year_name
            FROM users
            LEFT JOIN roles ON users.role = roles.id
            LEFT JOIN courses ON users.course = courses.id
            LEFT JOIN year ON users.year = year.id
            WHERE users.status = ?
            ORDER BY users.created_at DESC
        ");
      $stmt->bind_param("s", $status);
    } else {
      $stmt = $this->conn->prepare("
            SELECT
                users.*,
                roles.name AS role_name,
                courses.code AS course_code,
                courses.name AS course_name,
                year.name AS year_name
            FROM users
            LEFT JOIN roles ON users.role = roles.id
            LEFT JOIN courses ON users.course = courses.id
            LEFT JOIN year ON users.year = year.id
            ORDER BY users.created_at DESC
        ");
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }


  public function findById(int $id): ?array
  {
    $stmt = $this->conn->prepare("
        SELECT
            users.*,
            roles.name AS role_name,
            courses.name AS course_name,
            courses.code AS course_code,
            year.name AS year_name
        FROM users
        LEFT JOIN roles ON users.role = roles.id
        LEFT JOIN courses ON users.course = courses.id
        LEFT JOIN year ON users.year = year.id
        WHERE users.id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc() ?: null;
  }


  public function findByEmail(string $email)
  {
    $stmt = $this->conn->prepare('SELECT * FROM users WHERE email_address = ?');
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc() ?: null;
  }

  public function getAppointments(int $userId)
  {
    $stmt = $this->conn->prepare("
    SELECT
      a.*,
      lr.result_url as lab_result_url
    FROM appointments a
    LEFT JOIN lab_results lr ON lr.appointment_id = a.id
    WHERE a.user_id = ?
    ORDER BY a.created_at DESC
  ");

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }


  public function updateStatus(int $id, string $status): bool
  {
    $stmt = $this->conn->prepare("UPDATE users SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    return $stmt->execute();
  }

  public function delete(int $id): bool
  {
    $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  public function getUsersByRole(int $roleId, ?string $status = null): array
  {
    if ($status !== null) {
      $stmt = $this->conn->prepare("
            SELECT
                users.*,
                roles.name AS role_name,
                courses.name AS course_name,
                courses.code AS course_code,
                year.name AS year_name
            FROM users
            LEFT JOIN roles ON users.role = roles.id
            LEFT JOIN courses ON users.course = courses.id
            LEFT JOIN year ON users.year = year.id
            WHERE users.role = ? AND users.status = ?
            ORDER BY users.created_at DESC
        ");
      $stmt->bind_param("is", $roleId, $status);
    } else {
      $stmt = $this->conn->prepare("
            SELECT
                users.*,
                roles.name AS role_name,
                courses.name AS course_name,
                courses.code AS course_code,
                year.name AS year_name
            FROM users
            LEFT JOIN roles ON users.role = roles.id
            LEFT JOIN courses ON users.course = courses.id
            LEFT JOIN year ON users.year = year.id
            WHERE users.role = ?
            ORDER BY users.created_at DESC
        ");
      $stmt->bind_param("i", $roleId);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function update(
    int $id,
    string $firstName,
    string $middleName,
    string $lastName,
    string $address,
    string $phoneNumber,
    string $gender,
    int $year,
    int $course,
    ?string $password = null,
    ?string $imagePath = null
  ): bool {
    $fields = "first_name = ?, middle_name = ?, last_name = ?, address = ?, phone_number = ?, gender = ?, year = ?, course = ?, updated_at = NOW()";
    $types = "ssssssii";
    $params = [$firstName, $middleName, $lastName, $address, $phoneNumber, $gender, $year, $course];

    // Add password if provided
    if ($password) {
      $fields .= ", password = ?";
      $types .= "s";
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
      $params[] = $hashedPassword;
    }

    // Add image if provided
    if ($imagePath) {
      $fields .= ", image_url = ?";
      $types .= "s";
      $params[] = $imagePath;
    }

    $stmt = $this->conn->prepare("UPDATE users SET $fields WHERE id = ?");
    $types .= "i";
    $params[] = $id;

    $stmt->bind_param($types, ...$params);

    return $stmt->execute();
  }
}
