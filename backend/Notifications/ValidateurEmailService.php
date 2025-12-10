<?php
class ValidateurEmailService extends MailerBase {

    public function envoyerNouvelleDemande($validateurEmail, $validateurNom, $demande) {

        $id = $demande['id'];

        $subject = "Nouvelle demande assignee  N $id";

        $body = "
            Bonjour <b>$validateurNom</b>,<br><br>
            Une nouvelle demande vous a été attribuée :<br><br>

            <b>Demande N°</b> : $id<br>
            <b>Demandeur :</b> {$demande['nom_demandeur']}<br>
            <b>Type de besoin :</b> {$demande['type_besoin']}<br>
            <b>Urgence :</b> {$demande['urgence']}<br>
            <b>Description :</b> {$demande['description']}<br><br>

            Merci de procéder à sa validation.<br><br>
            Cordialement,<br>BEEX Support
        ";

        return $this->sendMail($validateurEmail, $subject, $body);
    }

    public function envoyerTransfert($validateurEmail, $validateurNom, $demande) {

        $id = $demande['id'];

       
        $subject = "Transfert automatique – Validateur indisponible";

        $body = "
            Bonjour <b>$nomDest</b>,<br><br>

            Le validateur <b>$nomSource</b> est actuellement indisponible.<br>
            Toutes ses demandes en attente ont été automatiquement transférées vers votre espace.<br><br>

            Merci d'assurer le suivi durant cette période.<br><br>
            Cordialement,<br>
            <b>BEEX – Système de gestion des demandes</b>
        ";

        return $this->sendMail($validateurEmail, $subject, $body);
    }
    public function nouvelleDemande($email, $nomValidateur, $demande, $transfert = 0) {

        if ($transfert == 1) {
            $subject = " Nouvelle demande transférée – N°{$demande['id']}";
            $intro = "Une nouvelle demande vous a été transférée.";
        } else {
            $subject = "Nouvelle demande – N°{$demande['id']}";
            $intro = "Une nouvelle demande vous a été assignée.";
        }

        $body = "
            Bonjour <b>$nomValidateur</b>,<br><br>
            $intro<br><br>

            <b>Numéro :</b> {$demande['id']}<br>
            <b>Demandeur :</b> {$demande['demandeur']}<br>
            <b>Type :</b> {$demande['type']}<br>
            <b>Urgence :</b> {$demande['urgence']}<br><br>

            Merci de procéder à la validation.<br><br>
            Cordialement,<br><b>BEEX</b>
        ";

        return $this->sendMail($email, $subject, $body);
    }
}
