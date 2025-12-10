<?php 
require_once __DIR__ .'/../authentification/database.php';
require_once __DIR__ . "/../Notifications/DemandeurEmailService.php";
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } 
class GestionDemandes {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }
   public function importerDemandes(): array {

    // Statuts autorisés
    $statuts = ['validee', 'en_cours', 'traite'];

    $stmt = $this->pdo->prepare("
        SELECT
            d.id_dm AS id,
            d.typedebesoin AS type_besoin,
            d.status AS statut,
            d.date_creation_dm AS date_creation,
            d.date_limite_dm AS date_limite,
            d.urgence_dm AS urgence,
            d.description_dm AS description,
            d.id_service AS service_id,
            s.nom_service AS service_nom,
            d.piece_jointe_dm AS fichier,
            d.transfere AS est_transfere,

            -- Demandeur
            dem.id_d AS demandeur_id,
            dem.nom_complet_d AS demandeur_nom,
            dem.email_d AS demandeur_email,
            dep.nom_dep AS demandeur_dep,

            -- Validateur
            val.id_v AS validateur_id,
            val.nom_complet_v AS validateur_nom,
            val.email_v AS validateur_email

        FROM demande d
        LEFT JOIN demandeur dem ON d.id_demandeur = dem.id_d
        LEFT JOIN departement dep ON dem.id_dep = dep.id_dep
        LEFT JOIN validateur val ON d.id_validateur = val.id_v
        LEFT JOIN service s ON d.id_service = s.id_service
        WHERE d.status IN ('validee', 'en_cours', 'traite')
        ORDER BY d.date_creation_dm DESC
    ");

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function affecterService($idDemande, $serviceId) {
    try {
        $sql = "UPDATE demande 
                SET id_service = :service, 
                    status = 'en_cours' 
                WHERE id_dm = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':service' => $serviceId,
            ':id' => $idDemande
        ]);
         // 2️⃣ Récupération des infos du demandeur + service
        $sqlInfo = "
            SELECT 
                dem.email_d AS email,
                dem.nom_complet_d AS nom,
                d.typedebesoin AS type_besoin,
                s.nom_service AS service_nom
            FROM demande d
            JOIN demandeur dem ON d.id_demandeur = dem.id_d
            LEFT JOIN service s ON d.id_service = s.id_service
            WHERE d.id_dm = :id
        ";
        $stmtInfo = $this->pdo->prepare($sqlInfo);
        $stmtInfo->execute([':id' => $idDemande]);
        $info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        // 3️⃣ Envoi email
        if ($info && !empty($info['email'])) {
          try{ 
            $mailer = new DemandeurEmailService();
            $mailer->envoyerChangementStatut(
                $info['email'],
                $info['nom'],
                "en_cours",
                [
                    "id" => $idDemande,
                    "type_besoin" => $info['type_besoin'],
                    "service_nom" => $info['service_nom']
                ]
            );            } catch (Exception $e) {
                // ⚠️ Log de l'erreur mais on ne bloque pas
                error_log("Erreur email pour demande $idDemande : " . $e->getMessage());
            }
        }

        return [
            'success' => true, 
            'message' => 'Service affecté, statut mis à jour et email envoyé ✔'
        ];

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
    }
}



    
    public function modifierStatut($idDemande, $nouveauStatut) {
        try {
            $sql = "UPDATE demande SET status = :statut WHERE id_dm = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':statut' => $nouveauStatut,
                ':id' => $idDemande
            ]);
            // 2️⃣ Récupérer les informations du demandeur
            $sqlInfo = "
            SELECT dem.email_d AS email, dem.nom_complet_d AS nom, d.typedebesoin AS type
            FROM demande d
            JOIN demandeur dem ON d.id_demandeur = dem.id_d
            WHERE d.id_dm = :id
            ";
            $stmtInfo = $this->pdo->prepare($sqlInfo);
            $stmtInfo->execute([':id' => $idDemande]);
            $info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

            if ($info && !empty($info['email'])) {
             $email = $info['email'];
             $nom = $info['nom'];
             $type = $info['type'];

            // 3️⃣ Envoi email avec classe PHPMailer
  
            
            $mailer = new DemandeurEmailService();
            $mailer->envoyerChangementStatut(
     $info['email'],
       $info['nom'],
    $nouveauStatut,
    [
             "id" => $idDemande,
            "type_besoin" => $info['type'],
            "description" => "", // Vous pouvez ajouter la description si nécessaire
            "date_creation" => "" // Vous pouvez ajouter la date de création si nécessaire
        ]
    );
        }

        return ['success' => true, 'message' => 'Statut mis à jour et email envoyé'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
    }
}
   
    public function getServices() {
    $stmt = $this->pdo->prepare("SELECT id_service, nom_service FROM service ORDER BY nom_service");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

	
}


?>