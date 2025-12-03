<?php


class Demandeur {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    // Récupérer infos personnelles
    public function getInfos($id_demandeur) {
        $sql = "SELECT id_d, nom_complet_d, email_d, poste_d, id_dep 
                FROM demandeur 
                WHERE id_d = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id_demandeur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les demandes
    public function getDemandes($id_demandeur) {
        $sql = "SELECT 
                    dm.id_dm,
                    dm.typedebesoin,
                    dm.status,
                    dm.date_creation_dm,
                    dm.date_limite_dm,
                    dm.urgence_dm,
                    dm.description_dm,
                    s.nom_service,
                    dm.piece_jointe_dm
                FROM demande dm
                LEFT JOIN service s ON dm.id_service = s.id_service
                WHERE dm.id_demandeur = :id
                ORDER BY dm.date_creation_dm DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id_demandeur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
