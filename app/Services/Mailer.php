<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host       = $_ENV['SMTP_HOST'];
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $_ENV['SMTP_USER'];
            $this->mail->Password   = $_ENV['SMTP_PASS'];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port       = $_ENV['SMTP_PORT'];
            $this->mail->CharSet    = 'UTF-8';

            // Recipients
            $this->mail->setFrom($_ENV['SMTP_FROM'], 'Divulga Cuidados Meus');
        } catch (Exception $e) {
            // Log error or handle it
        }
    }

    public function send($to, $subject, $body)
    {
        try {
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = strip_tags($body);

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
