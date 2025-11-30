<?php
require_once __DIR__ . "/../authentification/database.php";

class TypeBesoin {

    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    
    public function getTypesBesoin() {
        $sql = "SELECT id_tb, nom_tb from type_besoin";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
