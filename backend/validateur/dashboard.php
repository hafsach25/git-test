<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } 
require_once __DIR__ .'/../authentification/database.php';

class DashboardStats
{
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    
    public function getStats(int $idValidateur): array
    {
        $stats = [
            'recu' => 0,
            'validees' => 0,
            'rejetees' => 0
        ];
        $sql = "
            SELECT 
                COUNT(*) AS recu,
                SUM(status = 'validee') AS validees,
                SUM(status = 'rejete') AS rejetees
            FROM demande d
            INNER JOIN demandeur u ON d.id_demandeur = u.id_d
            WHERE u.id_validateur = :idv
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idv' => $idValidateur]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        return array_map(fn($v) => $v ?? 0, $stats);
    }

    
    public function getRecentDemandes(int $idValidateur): array
    {
        $sql = "
            SELECT 
                d.id_dm,
                d.transfere,
                v.nom_complet_v AS recepteur_name,
                u.nom_complet_d AS demandeur_name,
                d.urgence_dm AS urgence,
                d.date_creation_dm,
                d.status AS statut
            FROM demande d
            INNER JOIN demandeur u ON d.id_demandeur = u.id_d
            INNER JOIN transfer t ON t.id_validateur_createur=u.id_validateur
            LEFT JOIN validateur v ON t.id_validateur_recepteur = v.id_v
            
            WHERE u.id_validateur = :idv

            ORDER BY d.date_creation_dm DESC
            LIMIT 3
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idv' => $idValidateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3️⃣ Top 10 demandeurs
     */
    public function getTopDemanders(int $idValidateur): array
    {
        $sql = "
            SELECT 
                u.nom_complet_d,
                COUNT(*) AS total
            FROM demande d
            INNER JOIN demandeur u ON d.id_demandeur = u.id_d
            WHERE u.id_validateur = :idv
            GROUP BY u.id_d
            ORDER BY total DESC
            LIMIT 10
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idv' => $idValidateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTauxValidationRejet(int $idValidateur): array
    {
        $sql = "
            SELECT 
                COUNT(*) AS total,
                SUM(status = 'validée') AS validees,
                SUM(status = 'rejete') AS rejetees
            FROM demande d
            INNER JOIN demandeur u ON d.id_demandeur = u.id_d
            WHERE u.id_validateur = :idv
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idv' => $idValidateur]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = $row['total'] ?? 0;
        $validees = $row['validees'] ?? 0;
        $rejetees = $row['rejetees'] ?? 0;

        $tauxValidation = $total > 0 ? round(($validees / $total) * 100) : 0;
        $tauxRejet = $total > 0 ? round(($rejetees / $total) * 100) : 0;

        return [
            'validation' => $tauxValidation,
            'rejet' => $tauxRejet
        ];
    }

  

    // ---------------------------------
    // 3️⃣ Évolution mensuelle des demandes
    // ---------------------------------
    public function getEvolutionMensuelle(int $idValidateur, int $year = null): array
    {
        $year = $year ?? date('Y');

        $sql = "
            SELECT 
                MONTH(date_creation_dm) AS mois,
                COUNT(*) AS total,
                SUM(status = 'validee') AS validees,
                SUM(status = 'rejete') AS rejetees
            FROM demande d
            INNER JOIN demandeur u ON d.id_demandeur = u.id_d
            WHERE u.id_validateur = :idv
              AND YEAR(date_creation_dm) = :year
            GROUP BY MONTH(date_creation_dm)
            ORDER BY mois
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idv' => $idValidateur, 'year' => $year]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Remplir les mois vides
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $found = array_filter($result, fn($r) => $r['mois'] == $m);
            if ($found) {
                $row = array_shift($found);
                $data[$m] = [
                    'total' => (int)$row['total'],
                    'validees' => (int)$row['validees'],
                    'rejetees' => (int)$row['rejetees']
                ];
            } else {
                $data[$m] = ['total' => 0, 'validees' => 0, 'rejetees' => 0];
            }
        }

        return $data;
    }

}
