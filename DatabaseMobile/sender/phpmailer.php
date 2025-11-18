<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class EmailSender {    
    
 private $smtpHost = 'smtp.gmail.com'; 
 private $smtpUsername = 'elayangdesa@gmail.com'; 
 private $smtpPassword = 'vfzb crye ldcn yfaw'; 
 private $smtpPort = 587; 
 private $fromEmail = 'elayangdesa@gmail.com'; 
 private $fromName = 'Si-kunir';

    public function generateOTP($length = 6) {
        $otp = '';
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[rand(0, $charactersLength - 1)];
        }
        return $otp;
    }

    public function sendEmail($email, $type, $otp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $this->smtpPort;

            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($email);
            $mail->Subject = "Kode OTP Anda adalah: $otp";

            if ($type === "Register") {
                $mail->Body = 'Gunakan kode otp berikut untuk memverifikasi akun anda: ' . $otp;
            } else if ($type === "Lupa Password") {
                $mail->Body = 'Gunakan kode otp berikut untuk mengganti password anda: ' . $otp;
            }

            // kirim email
            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

?>