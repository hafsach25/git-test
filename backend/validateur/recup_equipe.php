<?php
//require_once __DIR__ . '/../authentification/database.php';

class Validateur {
    private $pdo;
    private $id;

    public function __construct($id_validateur) {
        $db = new Database();
        $this->pdo = $db->pdo;
        $this->id = $id_validateur;
    }

    /**
     * Récupérer les demandeurs supervisés par ce validateur
     * @return array
     */
    public function getDemandeursSupervises() {
        $sql = "SELECT d.id_d, d.nom_complet_d, d.email_d,
                       (SELECT COUNT(*) FROM demande dm WHERE dm.id_demandeur = d.id_d) AS nb_demandes
                FROM demandeur d
                WHERE d.id_validateur = :id_validateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_validateur' => $this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }}