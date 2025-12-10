<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class MailerBase {
    protected function sendMail($email, $subject, $htmlContent) {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'choupipuce1000@gmail.com'; 
            $mail->Password   = 'yvfttxazixhdwgaj'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email expéditeur
            $mail->setFrom('choupipuce1000@gmail.com', 'BEEX');

            // Destinataire
            $mail->addAddress($email);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlContent;

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Erreur email : {$mail->ErrorInfo}");
            return false;
        }
    }
}
