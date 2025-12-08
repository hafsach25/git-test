<?php 
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    public function sendCode($email, $code) {
        $mail = new PHPMailer(true);

         try {
            // Activer le mode debug pour voir les logs SMTP (optionnel)
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'html';

            // Paramètres SMTP Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'choupipuce1000@gmail.com'; // TON EMAIL Gmail
            $mail->Password   = 'yvfttxazixhdwgaj'; // MOT DE PASSE D'APPLICATION
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
            $mail->Port       = 587;

            // Expéditeur et destinataire
            $mail->setFrom('choupipuce1000@gmail.com', 'BEEX'); // Expéditeur = ton Gmail
            $mail->addAddress($email); // Destinataire

            // Contenu du mail
            $mail->isHTML(true);
            $mail->Subject = 'Code de reinitialisation BEEX';
            $mail->Body    = "Votre code de confirmation est : <b>$code</b>";

            // Envoi
            $mail->send();
            echo "Email envoyé avec succès à $email";
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        }
    }
}



?>
