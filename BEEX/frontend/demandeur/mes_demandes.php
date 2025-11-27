<?php session_start();
include __DIR__ . "/../../../backend/demandeur/importer_demandes.php"; 
$demandes = $_SESSION['imported_demandes'] ?? []; 
?>
<head>
    <title>BEEX Demandeur - Tableau de bord</title>
    <link rel="stylesheet" href="../../assets/demandeur assets/dashboard.css"> 
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<?php 

include "header_menu.php";
?>
<main class="main-content">

    <div class="page-header">
        <h1 class="page-title">Mes demandes</h1>


    <?php 

   
    if (empty($demandes)): ?>
        <p>Aucune demande trouvée.</p>

    <?php 
    else:  
       
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
                <?php foreach($demandes as $demande): ?>
                <tr>
                    <td><strong>#<?= htmlspecialchars($demande['id_dm']) ?></strong></td>

                    <td><?= htmlspecialchars($demande['type_besoin']) ?></td>

                    <td><?= htmlspecialchars($demande['date_creation_dm']) ?></td>

                    <td>
                        <?php 
                            switch ($demande['status']) {
                                case 'en_attente':
                                    $badge = 'badge-attente'; $txt = 'En attente'; break;
                                case 'en_cours':
                                    $badge = 'badge-en-cours'; $txt = 'En cours'; break;
                                case 'validee':
                                    $badge = 'badge-validee'; $txt = 'Validée'; break;
                                case 'traite':
                                    $badge = 'badge-traite'; $txt = 'Traité'; break;
                                case 'rejete':
                                    $badge = 'badge-rejetee'; $txt = 'Rejetée'; break;
                                default:
                                    $badge = 'badge-default'; 
                                    $txt = htmlspecialchars($demande['status']);
                            }
                        ?>
                        <span class="badge-status <?= $badge ?>"><?= $txt ?></span>
                    </td>

                    <td>
                        <a href="view_demande.php?id=<?= urlencode($demande['id_dm']) ?>" class="action-link">
                            Voir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    
    <?php endif; ?>

</main>

