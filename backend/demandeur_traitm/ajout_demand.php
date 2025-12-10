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
// Récupérer le validateur assigné
$query = $this->pdo->prepare("SELECT id_validateur FROM demandeur WHERE id_d = :id_demandeur");
$query->execute([':id_demandeur' => $id_demandeur]);
$id_validateur = $query->fetchColumn();

// Vérifier s'il existe un transfert actif
$stmt = $this->pdo->prepare("
    SELECT id_validateur_recepteur 
    FROM transfer 
    WHERE id_validateur_createur = :id_validateur 
      AND NOW() BETWEEN date_debut_tr AND date_fin_tr
    LIMIT 1
");
$stmt->execute([':id_validateur' => $id_validateur]);
$validateur_recepteur = $stmt->fetchColumn();

if ($validateur_recepteur) {
    $id_validateur = $validateur_recepteur;
    $transfere = 1;
} else {
    $transfere = 0;
}

//inserer la demande dans table demande
$sql = "INSERT INTO demande 
        (id_demandeur, typedebesoin, description_dm, urgence_dm, date_limite_dm, 
         piece_jointe_dm, status, date_creation_dm, id_validateur, transfere)
        VALUES
        (:id_demandeur, :type_besoin, :description, :urgence, :date_limite, 
         :attachement, 'en_attente', NOW(), :id_validateur, :transfere)";

$stmt = $this->pdo->prepare($sql);
$stmt->execute([
    ':id_demandeur' => $id_demandeur,
    ':type_besoin'  => $type_besoin,
    ':description'  => $description,
    ':urgence'      => $urgence,
    ':date_limite'  => $date_limite,
    ':attachement'  => $attachmentName,
    ':id_validateur' => $id_validateur,
    ':transfere' => $transfere
]);

/* =======================================================
       🔔 ENVOI EMAIL AU DEMANDEUR
    ======================================================= */
     $stmt = $this->pdo->prepare("SELECT id_dm FROM demande WHERE id_demandeur = :id_demandeur ORDER BY date_creation_dm DESC LIMIT 1");
    $stmt->execute([':id_demandeur' => $id_demandeur]);
    $lastDemandId = $stmt->fetchColumn();
    // On récupère infos demandeur
    $q1 = $this->pdo->prepare("SELECT nom_complet_d, email_d FROM demandeur WHERE id_d = :id");
    $q1->execute([':id' => $id_demandeur]);
    $demandeur = $q1->fetch(PDO::FETCH_ASSOC);

    // Préparation du tableau contenant les infos de la demande
    $demandeData = [
        "id" => $lastDemandId,
        "type_besoin" => $type_besoin,
        "description" => $description,
        "date_creation" => date("Y-m-d H:i:s")
    ];

    require_once __DIR__ . "/../Notifications/DemandeurEmailService.php";
    $mailDemandeur = new DemandeurEmailService();
    $mailDemandeur->envoyerChangementStatut(
        $demandeur['email_d'],
        $demandeur['nom_complet_d'],
        "en_attente",
        $demandeData
    );


    /* =======================================================
       🔔 ENVOI EMAIL AU VALIDATEUR
    ======================================================= */

    // Récupérer le validateur assigné automatiquement
    $query=$this->pdo->prepare("select id_validateur from demandeur where id_d=:id_demandeur ");
    $query->execute([':id_demandeur' => $id_demandeur]);
    $id_validateur = $query->fetchColumn();
    $q2 = $this->pdo->prepare("SELECT id_v, nom_complet_v, email_v FROM validateur where id_v=:id_validateur ");
    $q2->execute([':id_validateur' => $id_validateur]);
    $validateur = $q2->fetch(PDO::FETCH_ASSOC);

    require_once __DIR__ . "/../Notifications/ValidateurEmailService.php";
    $mailValidateur = new ValidateurEmailService();
   
    $mailValidateur->envoyerNouvelleDemande(
        $validateur['email_v'],
        $validateur['nom_complet_v'],
        [
            "id" => $lastDemandId ,
            "nom_demandeur" => $demandeur['nom_complet_d'],
            "type_besoin" => $type_besoin,
            "urgence" => $urgence,
            "description" => $description
        ]
    );

        return $this->pdo->lastInsertId();
    }
}
