<?php require_once  __DIR__ . '/../authentification/database.php';
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } 

class DashboardStatCards {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    public function getStatCards($demandeur_id) {
        $stats = [
            'en_cours' => 0,
            'validees' => 0,
            'rejetees' => 0
        ];

        $sql = "SELECT status, COUNT(*) as count FROM demande WHERE id_demandeur = ? GROUP BY status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$demandeur_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            if ($row['status'] === 'en_cours') {
                $stats['en_cours'] = $row['count'];
            } elseif ($row['status'] === 'validee') {
                $stats['validees'] = $row['count'];
            } elseif ($row['status'] === 'rejete') {
                $stats['rejetees'] = $row['count'];
            }
        }

        return $stats;
    }
}



?>
