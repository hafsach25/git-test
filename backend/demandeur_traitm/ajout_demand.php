<?php
require_once __DIR__ .'/../authentification/database.php';

class AddDemande {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addDemandeById($id_demandeur) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Récupérer les valeurs du formulaire
        $type_besoin = $_POST['type_besoin'] ?? null;
        $description = $_POST['description'] ?? null;
        $urgence    = $_POST['urgence'] ?? null;
        $date_limite = $_POST['date_limite'] ?? null;
        
       

        $attachements = [];
        $uploadDir = __DIR__ . '/../uploads/';

        if (isset($_FILES['attachments'])) {
            foreach ($_FILES['attachments']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['attachments']['error'][$key] === 0) {
                    $fileName = basename($_FILES['attachments']['name'][$key]);
                    $targetFile = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $targetFile)) {
                        $attachements[] = $fileName;
                    }
                }
            }
        }
        

        $attachementsJson = !empty($attachements) ? json_encode($attachements) : null;

        // Insert dans la table demande
        $sql = "INSERT INTO demande 
            (id_demandeur, typedebesoin, description_dm, urgence_dm, date_limite_dm, piece_jointe_dm, status, date_creation_dm) 
            VALUES 
            (:id_demandeur, :type_besoin, :description, :urgence, :date_limite, :attachement, 'en_attente', NOW())";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([
            ':id_demandeur' => $id_demandeur,
            ':type_besoin'  => $type_besoin,
            ':description'  => $description,
            ':urgence'      => $urgence,
            ':date_limite'  => $date_limite,
            ':attachement'  => $attachementsJson
        ]);

        return $this->db->pdo->lastInsertId();
    }
}