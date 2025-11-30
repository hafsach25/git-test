<?php
require_once __DIR__ .'/../authentification/database.php';

class Detail_demand{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

public function getDemandeById($id_dm) {
    

    $sql = "SELECT
                d.id_dm,
                d.urgence_dm,
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
            LIMIT 1";

    
   
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->execute(['id_dm' => $id_dm]);
    return $stmt->fetch(PDO::FETCH_ASSOC);

}
}