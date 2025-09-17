<?php
require __DIR__ . '/../utils/connection.php';
require __DIR__ . '/../utils/Mail.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? null;

if (!$email) {
  http_response_code(400);
  echo json_encode(['error' => 'Email is required.']);
  exit;
}

$stmt = $mysqli->prepare("SELECT id FROM users WHERE email_address = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  http_response_code(404);
  echo json_encode(['error' => 'No account associated with this email.']);
  exit;
}

$user = $result->fetch_assoc();

$token = bin2hex(random_bytes(32));

$update = $mysqli->prepare("UPDATE users SET password_reset_token = ? WHERE id = ?");
$update->bind_param("si", $token, $user['id']);
$update->execute();

$resetLink = "http://127.0.0.1/healthsync/reset-password.php?tid=$token";

$mail = new Mail();
$mail->send(
  $email,
  'Reset Your Password',
  <<<HTML
<!DOCTYPE html>
<html>
  <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
      <tr>
        <td align="center">
          <table width="500" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 6px; padding: 40px; text-align: center;">
            <tr>
              <td style="padding-bottom: 20px;">
                <img src="https://cdn-icons-png.flaticon.com/512/2913/2913465.png" width="80" height="80" alt="Reset Icon" style="display: block; margin: 0 auto;">
              </td>
            </tr>
            <tr>
              <td style="font-size: 18px; color: #333333; padding-bottom: 20px;">
                We received a request to reset your password.<br>
                Click the button below to choose a new password.
              </td>
            </tr>
            <tr>
              <td style="padding-bottom: 30px;">
                <a href="$resetLink"
                  style="display: inline-block; background-color: #27ae60; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-size: 16px;">
                  Reset Password
                </a>
              </td>
            </tr>
            <tr>
              <td style="font-size: 12px; color: #999999;">
                If you didn’t request this, you can safely ignore this email.
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
HTML
);

echo json_encode(['success' => true, 'message' => 'Reset link sent to your email.']);
