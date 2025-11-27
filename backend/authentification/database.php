<?php

class Database {
    private $host = "localhost";
    private $db   = "beex_bd";
    private $user = "root";
    private $pass = "";
    private $charset = "utf8mb4";

    public $pdo;

    public function __construct() {
        $connexion= "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->pdo = new PDO($connexion, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}
