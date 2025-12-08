<?php 
require_once __DIR__ .'/../authentification/database.php';;
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
        return ['success' => true, 'message' => 'Service affecté et statut mis à jour en "en_cours"'];
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
            return ['success' => true, 'message' => 'Statut mis à jour avec succès'];
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