<?php
if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
require_once __DIR__ . "/../authentification/database.php";
class DetailDmd {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->pdo; 
    }

    
    public function getDetails(int $id): ?array {
        $stmt = $this->db->prepare("SELECT
                d.urgence_dm,
                d.id_demandeur,
                d.date_creation_dm,
                d.status,
                d.description_dm,
                d.piece_jointe_dm AS fichier,
                d.typedebesoin AS type_besoin,
                dem.nom_complet_d AS demandeur,
                dep.nom_dep AS departement
            FROM demande d
            LEFT JOIN demandeur dem ON d.id_demandeur = dem.id_d
            LEFT JOIN departement dep ON dem.id_dep = dep.id_dep
            WHERE d.id_dm = :id_dm
            LIMIT 1");
        $stmt->execute(['id_dm' => $id]);
        $demande = $stmt->fetch(PDO::FETCH_ASSOC);


        return $demande ?: null;
    }
}
?>