<?php
class DemandeurEmailService extends MailerBase {

    public function envoyerChangementStatut($email, $nom, $statut, $demande) {

        $id = $demande['id'];
        $type = $demande['type_besoin'];

        // PERSONNALISATION SELON LE STATUT
        switch ($statut) {

            case "en_attente":
                $subject = "🎫 Votre demande N°$id a été créée";
                $body = "
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande concernant <b>$type</b> a été enregistrée avec succès.<br><br>
                    <b>Description :</b> {$demande['description']}<br>
                    <b>Date :</b> {$demande['date_creation']}<br><br>
                    Cordialement,<br>BEEX Support
                ";
                break;

            case "validee":
                $subject = "✔ Votre demande N°$id a été validée";
                $body = "
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande concernant <b>$type</b> a été <b>validée</b> par le responsable.<br><br>
                    Cordialement,<br>BEEX Support
                ";
                break;

            case "rejete":
                $subject = "❌ Votre demande N°$id a été refusée";
                $body = "
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande a été <b>refusée</b>.<br>
                    <b>Motif :</b> {$demande['motif_refus']}<br><br>
                    Cordialement,<br>BEEX Support
                ";
                break;

            case "en_cours":
                $subject = "🏢 Votre demande a été affectée à un service";
                $body = "
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande N°$id a été affectée au service <b>{$demande['service_nom']}</b>.<br><br>
                    Cordialement,<br>BEEX Support
                ";
                break;

            case "traite":
                $subject = "🎉 Votre demande N°$id est traitée";
                $body = "
                    Bonjour <b>$nom</b>,<br><br>
                    Votre demande a été <b>traitée</b> avec succès.<br><br>
                    Cordialement,<br>BEEX Support
                ";
                break;
        }

        return $this->sendMail($email, $subject, $body);
    }
    
}
