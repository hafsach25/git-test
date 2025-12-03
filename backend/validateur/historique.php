<?php
if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

include __DIR__ . "/../authentification/database.php";
class HistoriqueValidateur {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->pdo; 
    }

    
    public function getHistoriqueDemandes(int $idValidateur): array {
        $stmt = $this->db->prepare("SELECT
                d.id_dm,
                u.nom_complet_d AS demandeur_name,
                d.typedebesoin AS type_besoin,
                d.urgence_dm AS urgence,
                d.date_creation_dm,
                d.status AS statut
            FROM demande d
            INNER JOIN demandeur u ON d.id_demandeur = u.id_d
            WHERE u.id_validateur = :idv
            ORDER BY d.date_creation_dm DESC
        ");
        $stmt->execute(['idv' => $idValidateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}