<?php
include __DIR__ . '/../authentification/database.php';

class Detail_demand {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getDemandeById($id) {

        $sql = "SELECT d.*, 
                dem.nom_complet_d AS demandeur,
                dep.nom_dep AS departement
                FROM demandes d
                LEFT JOIN demandeur dem ON d.id_demandeur = dem.id_d
                LEFT JOIN departement dep ON dem.id_dep = dep.id_dep
                WHERE d.id_demande = ?";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
