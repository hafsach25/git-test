<?php
require_once __DIR__ .'/../authentification/database.php';
require_once __DIR__ . '/EmailService.php';
 if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
class ResetPassword {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Retourne la colonne email selon la table
     */
    private function getEmailColumn(string $table): string {
        return match($table) {
            'demandeur' => 'email_d',
            'validateur' => 'email_v',
            'administrateur' => 'email_ad',
            default => ''
        };
    }

    /**
     * Trouve un utilisateur par email dans une table spécifique
     */
    public function findUserByEmailAndTable(string $email, string $table) {
        $allowedTables = ['demandeur', 'validateur', 'administrateur'];
        if (!in_array($table, $allowedTables)) {
            return null;
        }

        $emailColumn = $this->getEmailColumn($table);
        if (!$emailColumn) return null;

        $query = $this->db->pdo->prepare("SELECT * FROM $table WHERE $emailColumn = ?");
        $query->execute([$email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);
        

        return $user ?: null;
    }

    /**
     * Envoie un code de réinitialisation
     */
    public function sendResetCode(string $email, string $table) {
        $user = $this->findUserByEmailAndTable($email, $table);

        if (!$user) {
            return ['status' => 'error', 'message' => "Email introuvable "];
        }

        $code = random_int(100000, 999999);

        $_SESSION['reset_code']  = $code;
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_table'] = $table;

        $mailer = new EmailService();
        $mailer->sendCode($email, $code);
        

        return ['status' => 'success', 'message' => 'Code envoyé', 'table' => $table];
    }

  

    public function updatePassword(string $email, string $table, string $newPassword) {

    // Sécurité : liste blanche des tables
    $allowedTables = ['demandeur', 'validateur', 'administrateur'];
    if (!in_array($table, $allowedTables)) {
        return ['status' => 'error', 'message' => 'Table non autorisée'];
    }

    $passwordColumn = match($table) {
        'demandeur' => 'mdps_d',
        'validateur' => 'mdps_v',
        'administrateur' => 'mdps_ad',
    };

    $emailColumn = match($table) {
        'demandeur' => 'email_d',
        'validateur' => 'email_v',
        'administrateur' => 'email_ad',
    };

    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

    $query = $this->db->pdo->prepare("UPDATE $table SET $passwordColumn = ? WHERE $emailColumn = ?");
    $query->execute([$hashed, $email]);

    return ['status' => 'success', 'message' => 'Mot de passe modifié'];
}

}
?>
