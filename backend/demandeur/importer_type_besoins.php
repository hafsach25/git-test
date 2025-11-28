<?php
require_once __DIR__ . "/../authentification/database.php";

class TypeBesoin {

    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    
    public function getTypesBesoin() {
        $sql = "SELECT DISTINCT nom_tb FROM type_besoin ORDER BY nom_tb ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
