<?php
require_once __DIR__ . '/../authentification/database.php';


class Demandeur {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Récupérer infos demandeur par email
    public function getByEmail($email) {
        $sql = "SELECT d.nom_complet_d, d.email_d, d.poste_d, dep.nom_dep, v.nom_complet_v as chef
                FROM demandeur d
                LEFT JOIN departement dep ON d.id_dep = dep.id_dep
                LEFT JOIN validateur v ON d.id_validateur = v.id_v
                WHERE d.email_d = ?";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

