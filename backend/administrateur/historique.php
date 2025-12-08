<?php
if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

include __DIR__ . "/../authentification/database.php";
class HistoriqueAdministrateur {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->pdo; 
    }
    public function getTotalDemandesFiltres(
    int $idAdministrateur,
    string $typeFilter = '',
    string $statusFilter = '',
    string $periodFilter = '',
    string $searchFilter = ''
): int {
    $sql = "SELECT COUNT(*) FROM demande d
            INNER JOIN demandeur u ON d.id_demandeur = u.id_d
            LEFT JOIN transfer t ON t.id_validateur_createur = u.id_validateur
            LEFT JOIN validateur v ON t.id_validateur_recepteur = v.id_v
            WHERE 1=1";

    $params = [];

    if ($typeFilter !== '') {
        $sql .= " AND d.typedebesoin = :type";
        $params[':type'] = $typeFilter;
    }
    if ($statusFilter !== '') {
        $sql .= " AND d.status = :status";
        $params[':status'] = $statusFilter;
    }
    if ($searchFilter !== '') {
        $sql .= " AND u.nom_complet_d LIKE :search";
        $params[':search'] = "%$searchFilter%";
    }
    if ($periodFilter !== '') {
        switch ($periodFilter) {
            case '7days':
                $sql .= " AND d.date_creation_dm >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                break;
            case '1month':
                $sql .= " AND d.date_creation_dm >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                break;
            case '3months':
                $sql .= " AND d.date_creation_dm >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
                break;
            case '6months':
                $sql .= " AND d.date_creation_dm >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
                break;
        }
    }

    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, PDO::PARAM_STR);
    }
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}


    
    public function getHistorique(int $idAdministrateur, $start = 0, $perPage = 10): array {
        $stmt = $this->db->prepare("SELECT
                d.id_dm,
                d.transfere,
                v.nom_complet_v AS recepteur_name,
                u.nom_complet_d AS demandeur_name,
                d.typedebesoin AS type_besoin,
                d.urgence_dm AS urgence,
                d.date_creation_dm,
                d.status AS statut
            FROM demande d
            INNER JOIN demandeur u ON d.id_demandeur = u.id_d
            Left JOIN transfer t ON t.id_validateur_createur=u.id_validateur
            LEFT JOIN validateur v ON t.id_validateur_recepteur = v.id_v
            ORDER BY d.date_creation_dm DESC
            LIMIT :start, :perPage
        ");
        
            // Bind des valeurs correctement pour LIMIT
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalDemandes(int $idAdministrateur): int {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM demande d
        INNER JOIN demandeur u ON d.id_demandeur = u.id_d
        WHERE u.id_validateur = :idv
    ");
    $stmt->execute(['idv' => $idAdministrateur]);
    return (int) $stmt->fetchColumn();
}

    // Nouvelle fonction pour récupérer les demandes avec filtres et pagination
    public function getHistoriqueFiltres(
        int $idAdministrateur,
        int $start = 0,
        int $perPage = 10,
        string $typeFilter = '',
        string $statusFilter = '',
        string $periodFilter = '',
        string $searchFilter = ''
    ): array {
        $sql = "SELECT
                    d.id_dm,
                    d.transfere,
                    v.nom_complet_v AS recepteur_name,
                    u.nom_complet_d AS demandeur_name,
                    d.typedebesoin AS type_besoin,
                    d.urgence_dm AS urgence,
                    d.date_creation_dm,
                    d.status AS statut
                FROM demande d
                INNER JOIN demandeur u ON d.id_demandeur = u.id_d
                LEFT JOIN transfer t ON t.id_validateur_createur = u.id_validateur
                LEFT JOIN validateur v ON t.id_validateur_recepteur = v.id_v
                WHERE 1=1";

        $params = [];

        if ($typeFilter !== '') {
            $sql .= " AND d.typedebesoin = :type";
            $params[':type'] = $typeFilter;
        }
        if ($statusFilter !== '') {
            $sql .= " AND d.status = :status";
            $params[':status'] = $statusFilter;
        }
        if ($searchFilter !== '') {
            $sql .= " AND u.nom_complet_d LIKE :search";
            $params[':search'] = "%$searchFilter%";
        }
        if ($periodFilter !== '') {
            switch ($periodFilter) {
                case '7days':
                    $sql .= " AND d.date_creation_dm >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                    break;
                case '1month':
                    $sql .= " AND d.date_creation_dm >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                    break;
                case '3months':
                    $sql .= " AND d.date_creation_dm >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
                    break;
                case '6months':
                    $sql .= " AND d.date_creation_dm >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
                    break;
            }
        }

        $sql .= " ORDER BY d.date_creation_dm DESC LIMIT :start, :perPage";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val,PDO::PARAM_STR);
        }
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


