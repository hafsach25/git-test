<?php
session_start();
include __DIR__ . "/../../../backend/demandeur/importer_demandes.php";
require_once __DIR__ ."/../../../backend/demandeur/importer_demandes.php"; 
$importer = new DemandeImporter(new Database(), $_SESSION);
$demandes = $importer->fetchDemandesForCurrentUser();
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">'.$_SESSION['success_message'].'</div>';
    unset($_SESSION['success_message']); // supprimer après affichage
}


?>

<head>
    <title>BEEX Demandeur - Tableau de bord</title>
    <link rel="stylesheet" href="../../assets/demandeur assets/dashboard.css">
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

    <?php 
// HEADER ET MENU
require_once __DIR__ ."/header_menu.php"; 

// STAT CARDS
require_once __DIR__ ."/../../../backend/demandeur/stat_cards_process.php"; 
?>

    <main class="main-content">

        <div class="page-header">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? '') ?></p>
        </div>

        <!-- STAT CARDS -->
        <div class="stats-container">
            <div class="stat-card blue">
                <div class="stat-title">Demandes en cours</div>
                <div class="stat-value"><?= $stats['en_cours'] ?></div>
            </div>
            <div class="stat-card green">
                <div class="stat-title">Demandes validées</div>
                <div class="stat-value"><?= $stats['validees'] ?></div>
            </div>
            <div class="stat-card red">
                <div class="stat-title">Demandes rejetées</div>
                <div class="stat-value"><?= $stats['rejetees'] ?></div>
            </div>
        </div>

        <?php 
   

    if (empty($demandes)): ?>
        <p>Aucune demande trouvée.</p>

        <?php 
    else:  
        $resultats = array_slice($demandes, 0, 3);
    ?>

        <div class="table-section">
            <h3>Dernières demandes</h3>
            <table class="table-beex">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type de besoin</th>
                        <th>Date de création</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($resultats as $demande): ?>
                    <tr>
                        <td><strong>#<?= htmlspecialchars($demande['id_dm']) ?></strong></td>

                        <td><?= htmlspecialchars($demande['type_besoin']) ?></td>

                        <td><?= htmlspecialchars($demande['date_creation_dm']) ?></td>

                        <td>
                            <?php 
                            switch ($demande['status']) {
                                case 'en_attente':
                                    $badge = ' .badge-attente'; $txt = 'En attente'; break;
                                case 'en_cours':
                                    $badge = 'badge-en-cours'; $txt = 'En cours'; break;
                                case 'validee':
                                    $badge = 'badge-validee'; $txt = 'Validée'; break;
                                case 'traite':
                                    $badge = 'badge-traite'; $txt = 'Traité'; break;
                                case 'Rejetée':
                                    $badge = 'badge-rejetee'; $txt = 'Rejetée'; break;
                                default:
                                    $badge = 'badge-default'; 
                                    $txt = htmlspecialchars($demande['status']);
                            }
                        ?>
                            <span class="badge-status <?= $badge ?>"><?= $txt ?></span>
                        </td>

                        <td>
                            <a href="detail_demand.php?id=<?= htmlspecialchars($demande['id_dm']) ?>"
                                class="action-link">Voir</a>


                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="mes_demandes.php" class="btn-see-more">Voir plus</a>
        </div>


        <?php endif; ?>
        <a href="creation_demand.php" class="btn-add text-decoration-none"><i class="bi bi-plus"></i> Nouvelle
            demande</a>

    </main>
</body>