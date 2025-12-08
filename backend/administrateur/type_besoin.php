<?php
if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

require_once __DIR__ . "/../authentification/database.php";
class TypeBesoin {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->pdo; 
    }

    
    public function getAllTypesBesoin(): array {
        $stmt = $this->db->prepare("SELECT * FROM type_besoin");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}