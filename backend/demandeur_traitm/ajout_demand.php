<?php
require_once __DIR__ . '/../authentification/database.php';

class AddDemande {

    private $db;
    public $pdo;

    public function __construct() {
        $this->db = new Database();
        $this->pdo = $this->db->pdo;
    }

    public function addDemandeById($id_demandeur) {

        // Récupérer les champs du formulaire
        $type_besoin  = $_POST['type_besoin'] ?? null;
        $description  = $_POST['description'] ?? null;
        $urgence      = $_POST['urgence'] ?? null;
        $date_limite  = $_POST['date_limite'] ?? null;



      $uploadDir = __DIR__ . '/../uploads/';

if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$attachmentName = null;

if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
    $tmpName = $_FILES['attachments']['tmp_name'][0];
    $originalName = $_FILES['attachments']['name'][0]; // GARDER TEL QUEL
    $targetFile = $uploadDir . $originalName;

    // Vérifier le type de fichier (optionnel mais recommandé)
    $allowedTypes = ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmpName);
    finfo_close($finfo);

    if (in_array($mime, $allowedTypes)) {
        if (move_uploaded_file($tmpName, $targetFile)) {
            $attachmentName = $originalName; // nom + extension réel
        }
    } else {
        // Optionnel : gestion des fichiers non autorisés
        $attachmentName = null;
    }
}



        // Insert dans la table demande
        $sql = "INSERT INTO demande 
                (id_demandeur, typedebesoin, description_dm, urgence_dm, date_limite_dm, 
                 piece_jointe_dm, status, date_creation_dm,transfere)
                VALUES
                (:id_demandeur, :type_besoin, :description, :urgence, :date_limite, 
                 :attachement, 'en_attente', NOW(),0)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_demandeur' => $id_demandeur,
            ':type_besoin'  => $type_besoin,
            ':description'  => $description,
            ':urgence'      => $urgence,
            ':date_limite'  => $date_limite,
            ':attachement'  => $attachmentName
        ]);

        return $this->pdo->lastInsertId();
    }
}
