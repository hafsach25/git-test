<?php
require_once  'MailerBase.php';
class DemandeurEmailService extends MailerBase {

    public function envoyerChangementStatut($email, $nom, $statut, $demande) {
        $mailer=new MailerBase();
        $id = $demande['id'];
        $type = $demande['type_besoin'];

        // PERSONNALISATION SELON LE STATUT
        switch ($statut) {

            case "en_attente":
                $subject = "Votre demande N $id a ete creee avec succes";
                $body = "
                
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande concernant <b>$type</b> a été enregistrée avec succès.<br><br>
                    <b>Description :</b> {$demande['description']}<br>
                    <b>Date :</b> {$demande['date_creation']}<br><br>
                    Cordialement,<br>BEEX Support
                ";
                break;

            case "validee":
                $subject = "Votre demande N $id a ete validee";
                $body = "
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande concernant <b>$type</b> a été <b>validée</b> par le responsable.<br><br>
                    Cordialement,<br>BEEX Support
                ";
                break;

            case "rejete":
                $subject = " Votre demande N $id a ete refusee";
                $body = "
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande a été <b>refusée</b>.<br>
    
                    Cordialement,<br>BEEX Support
                ";
                break;

            case "en_cours":
                $subject = " Votre demande N $id a ete affectee a un service";
                $body = "
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande N°$id a ete affectee au service <b>{$demande['service_nom']}</b>.<br><br>
                    Cordialement,<br>BEEX Support
                ";
                break;

            case "traite":
                $subject = " Votre demande N $id est traitee";
                $body = "
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande a été <b>traitée</b> avec succès.<br><br>
                    Cordialement,<br>BEEX Support
                ";
                break;
        }

        return $mailer->sendMail($email, $subject, $body);
    }
    
}
