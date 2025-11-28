<?php
/*session_start();

// Vérifier connexion
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../BEEX/frontend/authentification/login.php");
    exit;
}

include "../../../backend/demandeur_traitm/recup_detail_demand.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "❌ Aucune demande sélectionnée.";
    exit;
}

$demandeur = new Detail_demand();
$demande = $demandeur->getDemandeById($id);

if (!$demande) {
    echo "❌ Demande introuvable.";
    exit;
}*/
session_start();

// Si tu veux vérifier que seul un demandeur peut voir :
if (!isset($_SESSION['email'])) {
    header("Location: ../../../beex/frontend/authentification/login.php");
    exit;
}

include __DIR__ . '/../../../backend/authentification/database.php';

class Detail_demand {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getDemandeById($id) {
$sql = "SELECT
    d.id_dm,
    d.urgence_dm,
    d.date_creation_dm,
    d.status,
    d.description_dm,
    d.piece_jointe_dm,
    tb.nom_tb AS type_besoin,
    dem.nom_complet_d AS demandeur,
    dep.nom_dep AS departement
FROM demande d
LEFT JOIN type_besoin tb ON d.id_typedebesoin = tb.id_tb
LEFT JOIN demandeur dem ON d.id_demandeur = dem.id_d
LEFT JOIN departement dep ON dem.id_dep = dep.id_dep
WHERE d.id_dm = ?";



        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Vérifie si "id" existe dans l’URL
$id = $_GET["id"] ?? null;
if (!$id) {
    die("❌ ID non fourni dans l’URL !");
}

// Appel du modèle
$model = new Detail_demand();
$demande = $model->getDemandeById($id);

if (!$demande) {
    die("❌ Demande introuvable !");
}

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails demandes</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/inf_demandeur.css">
</head>

<body>
    <?php
include ("header_menu.php") ?>
    <div class="cote">
        <a href="dashboard.php" class="retour_dashboard">← Retour à la page d'acceuil</a>
        <h2>détails du demande</h2>
    </div>
    <div class="main-content1">
        <div class="form-wrapper d-flex justify-content-center">
            <div class="card shadow-sm p-4">
                <h2>informations générales</h2>
                <div class="content-inter">
                    <div class="content-inter-left">
                        <p><strong>Demandeur :</strong> <span class="value"><?= htmlspecialchars($demande['demandeur']) ?></span></p>
                    <p><strong>Type de besoin :</strong><span class="value"> <?= htmlspecialchars($demande['type_besoin']) ?></span></p>
                    <p><strong>Urgence :</strong> <span class="value"><?= htmlspecialchars($demande['urgence_dm']) ?></span></p>
                    </div>
                    <div class="content-inter-right">
                    <p><strong>Date de création :</strong> <span class="value"><?= htmlspecialchars($demande['date_creation_dm']) ?></span></p>
                    <p><strong>Departement:</strong><span class="value"> <?= htmlspecialchars($demande['departement']) ?></span></p>
                    <p><strong>Statut actuel :</strong> <span class="value"><?= htmlspecialchars($demande['status']) ?></span></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="main-content1">
        <div class="form-wrapper d-flex justify-content-center">
            <div class="card shadow-sm p-4">
                <h2>Déscription :</h2>
        
                <p><?=nl2br(htmlspecialchars($demande['description_dm']))
                // nl2br affiche texte avec retour a ligne si il est ecor dans bd sous forme des tirets ?></p>
         

</div>
</div>

    </div>
        <div class="main-content1">
        <div class="form-wrapper d-flex justify-content-center">
            <div class="card shadow-sm p-4">
                <h2>pièces jointes :</h2>
                          <?php if (!empty($demande['fichier'])) : ?>
                <a href="../../../uploads/<?= htmlspecialchars($demande['fichier']) ?>" download>
                    Télécharger la pièce jointe
                </a>
            <?php else : ?>
                <p>Aucune pièce jointe.</p>
            <?php endif; ?>
                </div>
</div>

    </div>
