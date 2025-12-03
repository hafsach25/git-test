<?php
//require_once __DIR__ . '/../authentification/database.php';

class TransfertDemande{
    private $pdo;
    private $id;

    public function __construct($id_validateur) {
        $db = new Database();
        $this->pdo = $db->pdo;
        $this->id = $id_validateur;
    }

    // Récupérer tous les autres validateurs (pour transfert)
    public function getAutresValidateurs() {
        $sql = "SELECT id_v, nom_complet_v FROM validateur WHERE id_v != :id_actuel";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_actuel' => $this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Enregistrer un transfert
    public function transfererDemande($id_recepteur, $date_debut, $date_fin, $raison) {
        $sql = "INSERT INTO transfer 
                (id_validateur_createur, id_validateur_recepteur, date_debut_tr, date_fin_tr, raison_tr) 
                VALUES (:id_createur, :id_recepteur, :date_debut, :date_fin, :raison)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_createur' => $this->id,
            ':id_recepteur' => $id_recepteur,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin,
            ':raison' => $raison
        ]);
    }
}
