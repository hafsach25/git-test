<?php
session_start();
require_once __DIR__ . '/../../../backend/authentification/database.php';
require_once __DIR__ . '/../../../backend/demandeur_traitm/recup_detail_demand.php';
$db = new Database();
$conn = $db->pdo;
// Vérifier connexion
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../BEEX/frontend/authentification/login.php");
    exit;
}

$id_dm = $_GET['id'] ?? null;
if (!$id_dm) {
    echo "Demande introuvable.";
    exit;
}
//on est ici
$demande_obj = new Detail_demand();
$demande = $demande_obj->getDemandeById($id_dm);

$previousPage = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';

if (isset($_POST['modifier'])){

    header("Location: modifier_demand.php?id=".$id_dm);
    exit;
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
        <a href="dashboard.php" class="retour_dashboard"><i class="bi bi-arrow-left"></i> Retour à la page d'accueil</a>
        <h2>détails de la demande {<?= htmlspecialchars($id_dm ) ?>}</h2>
    </div>
    <form method='post' action=''>
        <div class="main-content1">
            <div class="form-wrapper d-flex justify-content-center">
                <div class="card shadow-sm p-4">
                    <h2>informations générales</h2>
                    <div class="content-inter">
                        <div class="content-inter-left">
                            <p><strong>Demandeur :</strong> <span
                                    class="value"><?= htmlspecialchars($demande['demandeur']) ?></span></p>
                            <p><strong>Type de besoin :</strong><span class="value">
                                    <?= htmlspecialchars($demande['type_besoin']) ?></span></p>
                            <p><strong>Urgence :</strong> <span
                                    class="value"><?= htmlspecialchars($demande['urgence_dm']) ?></span></p>
                        </div>
                        <div class="content-inter-right">
                            <p><strong>Date de création :</strong> <span
                                    class="value"><?= htmlspecialchars($demande['date_creation_dm']) ?></span></p>
                            <p><strong>Departement:</strong><span class="value">
                                    <?= htmlspecialchars($demande['departement']) ?></span></p>
                            <p><strong>Statut actuel :</strong> <span
                                    class="value"><?= htmlspecialchars($demande['status']) ?></span></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="main-content1">
            <div class="form-wrapper d-flex justify-content-center">
                <div class="card shadow-sm p-4">
                    <h2>Déscription :</h2>
                    <div class="content-inter">

                        <p><?=nl2br(htmlspecialchars($demande['description_dm']))
                // nl2br affiche texte avec retour a ligne si il est ecor dans bd sous forme des tirets ?></p>

                    </div>
                </div>
            </div>

        </div>
        <div class="main-content1">
            <div class="form-wrapper d-flex justify-content-center">
                <div class="card shadow-sm p-4">
                    <h2>pièces jointes :</h2>
                    <div class="content-inter">
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
        </div>
        <div class='main-content'>
            <div class="form-wrapper d-flex justify-content-center">
                <button type='submit' class='btn-save mt-3' name='modifier'>modifier</button>
            </div>
        </div>

    </form>