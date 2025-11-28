<?php
include "database.php";

class Auth {

    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    public function login($email, $password) {

        $roles = [
            [
                "table" => "administrateur",
                "email_col" => "email_ad",
                "pass_col" => "mdps_ad",
                "id_col"    => "id_ad",
                "name_col"  => "nom_complet_ad",
                "dashboard" => "../../BEEX/frontend/administrateur/dashboard.php"
            ],
            [
                "table" => "validateur",
                "email_col" => "email_v",
                "pass_col"  => "mdps_v",
                "id_col"    => "id_v",
                "name_col"  => "nom_complet_v",
                "dashboard" => "../../BEEX/frontend/validateur/dashboard.php"
            ],
            [
                "table" => "demandeur",
                "email_col" => "email_d",
                "pass_col"  => "mdps_d",
                "id_col"    => "id_d",
                "name_col"  => "nom_complet_d",
                "dashboard" => "../../BEEX/frontend/demandeur/dashboard.php"
            ],
        ];

        foreach ($roles as $role) {

            $sql = "SELECT * FROM {$role['table']} WHERE {$role['email_col']} = ?";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([$email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $password === $user[$role['pass_col']]) {
                
                // Création de session
                $_SESSION["logged_in"] = true;
                $_SESSION["role"] = $role["table"];
                $_SESSION["user_id"] = $user[$role['id_col']];
                $_SESSION["username"] = $user[$role['name_col']];
                echo $role["dashboard"];
                return $role["dashboard"];
                echo json_encode($_SESSION);
                
            }
        }

        return false;
    }
}
