<?php

include __DIR__ . '/../authentification/database.php';



class DemandeImporter
{
    private PDO $pdo;
    private ?int $userId;

    public function __construct(Database $db, array $session)
    {
        $this->pdo = $db->pdo;
        $this->userId = $session['user_id'] ?? null;
    }

    public function fetchDemandesForCurrentUser()
    {
        if ($this->userId === null) {
            return [];
        }

        $sql = "
            SELECT 
                d.id_dm,
                t.nom_tb AS type_besoin,
                d.date_creation_dm,
                d.status,
                s.nom_service,
                d.description_dm,
                d.date_limite_dm,
                d.urgence_dm,
                d.transfere
            FROM demande d
            JOIN type_besoin t ON d.id_typedebesoin = t.id_tb
            JOIN service s ON d.id_service = s.id_service
            WHERE d.id_demandeur = :id_demandeur
            ORDER BY d.date_creation_dm DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_demandeur' => $this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

/* usage */
$db = new Database();

$importer = new DemandeImporter($db, $_SESSION);
$results = $importer->fetchDemandesForCurrentUser();

// Sauvegarde des résultats dans la session
$_SESSION['imported_demandes'] = $results;

