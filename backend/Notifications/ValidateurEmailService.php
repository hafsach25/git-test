<?php
include_once __DIR__ . '/../Notifications/MailerBase.php';
include_once __DIR__ . '/../authentification/database.php';
class ValidateurEmailService extends MailerBase {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Database())->pdo;   // IMPORTANT
    }

    public function envoyerNouvelleDemande($validateurEmail, $validateurNom, $demande, $transfert = 0) {
        $id = $demande['id'];
        // Vérifier si la demande est transférée depuis la BD
        if ($this->isDemandeTransferred($id)) {
            $transfert = 1;
            $validateurEmail = $this->getNewValidateurEmail($id);
            $validateurNom = $this->getNewValidateurNom($id);
        }

        if ($transfert == 1) {
            $subject = "Nouvelle demande transférée  N $id";
            $intro = "Une nouvelle demande vous a été transférée.";
        } else {
            $subject = "Nouvelle demande assignée  N $id";
            $intro = "Une nouvelle demande vous a été attribuée.";
        }

        $body = "
            Bonjour <b>$validateurNom</b>,<br><br>
            $intro<br><br>

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

   private function isDemandeTransferred($demandeId) {
    $query = "SELECT transfere FROM demande WHERE id_dm = ?";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$demandeId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row && $row['transfere'] == 1;
}


   private function getNewValidateurEmail($demandeId) {
    $query = "SELECT v.email_v 
              FROM demande d 
              JOIN validateur v ON d.id_validateur = v.id_v
              WHERE d.id_dm = ?";

    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$demandeId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $row['email_v'] : '';
}



private function getNewValidateurNom($demandeId) {
    $query = "SELECT v.nom_complet_v 
              FROM demande d 
              JOIN validateur v ON d.id_validateur = v.id_v
              WHERE d.id_dm = ?";

    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$demandeId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $row['nom_complet_v'] : '';
}



    public function envoyerNotificationTransfert($validateurEmail, $dateDebut, $dateFin, $raison, $nomValidateurCreateur) {
        $nomSource = $nomValidateurCreateur;
        $subject = "Transfert automatique  Validateur indisponible";

        $body = "
            Bonjour,<br><br>
            Le validateur <b>$nomSource</b> est actuellement indisponible.<br>
            Toutes ses demandes en attente vont etre automatiquement transférées vers votre espace.<br><br>
            <b>Période d'indisponibilité :</b><br>
            Du : $dateDebut<br>
            Au : $dateFin<br><br>

            <b>Raison :</b><br>
            $raison<br><br>

            Merci d'assurer le suivi durant cette période.<br><br>
            Cordialement,<br>
            <b>BEEX – Système de gestion des demandes</b>
        ";

        return $this->sendMail($validateurEmail, $subject, $body);
    }
  
}
