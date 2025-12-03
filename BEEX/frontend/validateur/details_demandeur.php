<?php
session_start();

require_once __DIR__ . '/../../../backend/authentification/database.php';
require_once __DIR__ . '/../../../backend/validateur/details_demandeurs.php';

// Vérification : seulement les validateurs connectés
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../BEEX/frontend/authentification/login.php");
    exit;
}

$db = new Database();
$pdo = $db->pdo;

// Récupérer ID depuis URL
if (!isset($_GET['id'])) {
    die("ID du demandeur non fourni.");
}
$id_demandeur = intval($_GET['id']);

// Instancier classe Demandeur
$demandeurObj = new Demandeur();
$demandeur = $demandeurObj->getInfos($id_demandeur);
if (!$demandeur) die("Demandeur introuvable.");

$demandes = $demandeurObj->getDemandes($id_demandeur);
if (isset($_POST['revenir_bord'])){
   
    // rediriger vers le dashboard du demandeur
    header("Location: dashboard.php");
    exit;}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détails du Demandeur – BEEX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/validateur assets/details.css">
</head>

<body>

    <?php require_once __DIR__ ."/header_menu.php"; ?>

    <main class="main-content">
        <a href="voir_equipe.php" class="back-link mb-4"><i class="bi bi-arrow-left"></i> Retour à l’équipe</a>

        <!-- INFOS DEMANDEUR -->
        <div class="card-beex mb-4">
            <h3 class="section-title">Informations personnelles du demandeur</h3>
            <div class="row detail-row">
                <div class="col-md-6">
                    <p><strong>Nom complet :</strong> <?= htmlspecialchars($demandeur['nom_complet_d']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($demandeur['email_d']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Poste :</strong> <?= htmlspecialchars($demandeur['poste_d']) ?></p>
                </div>
            </div>
        </div>

        <!-- LISTE DES DEMANDES -->
        <?php if (empty($demandes)): ?>
        <p class="text-danger">Ce demandeur n’a aucune demande.</p>
        <?php else: ?>
        <?php foreach ($demandes as $dm): ?>
        <div class="card-beex mb-4">
            <h3 class="section-title">Demande #<?= $dm['id_dm'] ?> </h3>
            <div class="row detail-row mb-2">
                <div class="col-md-6">
                    <p><strong>Service :</strong> <?= htmlspecialchars($dm['nom_service'] ?? '-') ?></p>
                    <p><strong>Urgence :</strong> <?= htmlspecialchars($dm['urgence_dm']) ?></p>
                    <p><strong>Date limite :</strong> <?= htmlspecialchars($dm['date_limite_dm'] ?? '-') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date de création :</strong> <?= htmlspecialchars($dm['date_creation_dm']) ?></p>
                    <p><strong>Status :</strong> <span
                            style="color:var(--primary-blue); font-weight:bold;"><?= htmlspecialchars($dm['status']) ?></span>
                    </p>
                </div>
            </div>
            <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($dm['description_dm'])) ?></p>

            <!-- Pièces jointes -->

            <!-- Pièces jointes -->
            <?php if (!empty($dm['piece_jointe_dm'])): ?>
            <div class="attachments-section mt-3">
                <p><strong>Pièces jointes :</strong></p>
                <?php
                        $fichiers = explode(',', $dm['piece_jointe_dm']); // séparés les fichiers par virgules
                        foreach ($fichiers as $fichier):
                        ?>
                <!-- Lien cliquable pour ouvrir le fichier dans un nouvel onglet -->
                <a href="<?= htmlspecialchars($fichier) ?>" target="_blank" class="attachment-item">
                    <!-- target="_blank" ouvre le fichier dans un nouvel onglet.-->
                    <!-- Icône pour représenter la pièce jointe -->
                    <span class="attachment-icon"><i class="bi bi-paperclip"></i></span>
                    <!-- Nom du fichier affiché -->
                    <span class="attachment-name"><?= basename($fichier) ?></span>
                    <!--récupère seulement le nom du fichier, pas le chemin complet.-->
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</body>

</html>