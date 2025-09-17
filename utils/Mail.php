<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';


class Mail
{
  private PHPMailer $mail;
  public function __construct()
  {
    try {
      $this->mail = new PHPMailer(true);
      $this->mail->SMTPDebug = 0;
      $this->mail->isSMTP();
      $this->mail->Host       = 'smtp.gmail.com';
      $this->mail->SMTPAuth   = true;
      $this->mail->Username   = 'mellakelvin98@gmail.com';
      $this->mail->Password   = 'dmpz cswp jtmy qrtf';
      $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $this->mail->Port       = 465;
    } catch (Exception $e) {
    }
  }
  public function send(string $to, string $subject, string $body)
  {
    try {
      $this->mail->Priority = 1;
      $this->mail->addCustomHeader('X-MSMail-Priority', 'High');
      $this->mail->addCustomHeader('X-Priority', '1 (Highest)');
      $this->mail->addCustomHeader('Importance', 'High');
      $this->mail->setFrom('mellakelvin98@gmail.com', 'Healthsync');
      $this->mail->addAddress($to);
      $this->mail->isHTML(true);
      $this->mail->Subject = $subject;
      $this->mail->Body    = $body;
      $this->mail->send();
    } catch (Exception $e) {
    }
  }
}
