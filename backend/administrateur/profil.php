<?php 
require_once __DIR__ .  '/../authentification/database.php';
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } 
class Profil{
        private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }
        public function getByEmail_admin($email) {
        $sql = "SELECT a.id_ad,a.nom_complet_ad , a.email_ad 
                FROM administrateur a
                WHERE a.email_ad = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
       public function updateProfil($id_user, $nom, $email, $new_password = null) {
        if ($new_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE administrateur SET nom_complet_ad = ?, email_ad = ?, mdps_ad = ? WHERE id_ad = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nom, $email, $hashed_password, $id_user]);
        } else {
            $sql = "UPDATE administrateur SET nom_complet_ad = ?, email_ad = ? WHERE id_ad = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nom, $email, $id_user]);
        }
    }



}





