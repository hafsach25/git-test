<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }   
require_once __DIR__  . '/../authentification/database.php';



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
                d.typedebesoin AS type_besoin,
                d.date_creation_dm,
                d.status,
                d.description_dm,
                d.date_limite_dm,
                d.urgence_dm,
                d.transfere
            FROM demande d
            WHERE d.id_demandeur = :id_demandeur
            ORDER BY d.id_dm DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_demandeur' => $this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}




