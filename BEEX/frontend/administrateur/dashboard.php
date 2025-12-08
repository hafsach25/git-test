<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once __DIR__ . "/../../../backend/administrateur/dashboard.php";
$dashboard = new AdminDashboard();

$total = $dashboard->getTotalDemandes();
$enCours = $dashboard->getEnCours();
$traite= $dashboard->getTraite();
$valid=$dashboard->getValidee();


//Calcul des pourcentages  et Gestion si aucune demande
if ($total > 0) {
    $perc_enCours = round($enCours / $total * 100);
    $perc_traite= round($traite / $total * 100);
    $perc_valide=round($valid/$total*100);
} else {
    $perc_enCours = $perc_traite = $perc_valide = 0;
}

//top 5 chefs
$topChefs=$dashboard->getTopChefs(); //c’est un tableau de tableaux associatifs,[['nom'=>..., 'total'=>...],...]
$max = max(array_column($topChefs, 'total')); //extrait toutes les valeurs de la colonne 'total' et retourne un tableau simple :[4,6,2,...] PUIS fais max
$max = $max ?: 1; // éviter division par 0

//demandes par service
$services = $dashboard->getDemandesParService();//c’est un tableau de tableaux associatifs,[['service'=>..., 'total'=>...],...]
$max = 0;
foreach($services as $s) {
    if($s['total'] > $max) $max = $s['total'];
}


//dernieres 5 demandes
$dernieresDemandes = $dashboard->getDernieresDemandes();


// Couleurs pour chaque barre
$colors = ['#01BD96', '#FFA500', '#FF4757', '#6C5CE7', '#00BFFF', '#FF69B4', '#FFD700', '#8A2BE2'];
//derniers infos demandes
//$derniers=$dashboard->getDernieresInfosDemandes();

?>



<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Administrateur – BEEX</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/administrateur assets/dashboard.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>


<body>
    <?php include_once __DIR__ . '/header_menu.php'; ?>
    <div class="main-content">
        <div class="page-header">
            <?php if (!empty($_SESSION['message'])) {
                 echo '<div class="alert alert-info">' . htmlspecialchars($_SESSION['message']) . '</div>';
                 unset($_SESSION['message']);
                  }
            ?>
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? '') ?></p>
        </div>
        <!-- STAT CARDS -->
        <div class="stats-container">
            <div class="stat-card blue">
                <i class="bi bi-file-earmark-text"></i>
                <div class="stat-title">Total des demandes</div>
                <div class="stat-value"><?= $total ?></div>

            </div>
            <div class="stat-card green">
                <div class="stat-icon  "><i class="bi bi-check-circle-fill text-success  "></i></div>
                <div class="stat-title">Demandes Traitées</div>
                <div class="stat-value"><?= $traite ?></div>

            </div>

            <div class="stat-card orange ">
                <i class="bi bi-hourglass-split"></i>
                <div class="stat-title"> Demandes En cours</div>
                <div class="stat-value"><?= $enCours ?></div>

            </div>
        </div>

        <!-- GRAPH PIE -->
        <div class="graph-card">
            <h3>Répartition des demandes par statut</h3>

            <div class="pie-chart" style="
               background: conic-gradient(
               #01BD96 0% <?= $perc_traite ?>%,                          /* Traitées */
               #FFA500 <?= $perc_traite ?>% <?= $perc_traite + $perc_enCours ?>%, /* En cours */
               #007bff <?= $perc_traite + $perc_enCours ?>% 100%          /* Finalisées */      );">
            </div>

            <!-- Légende -->
            <div class="pie-legend">
                
                <span class="legend-box blue"></span>
                <b><?= $perc_valide ?>%</b> reçues
                <span class="legend-box green"></span>
                <b><?= $perc_traite ?>%</b> Traitées
                <span class="legend-box orange"></span>
                <b><?= $perc_enCours ?>%</b> En cours


            </div>

        </div>

        <!--top 5 chefs-->
        <div class="graph-card">
            <h3> Les 5 Chefs les plus actifs </h3>
            <?php foreach($topChefs as $chef): 
                 $percent = round($chef['total'] / $max * 100); ?>
            <div class="bar-row">
                <div class="bar-label"><?= htmlspecialchars($chef['nom']) ?></div>

                <!-- Conteneur barre avec tooltip -->
                <div class="bar-content" data-value="<?= $percent ?>%">
                    <div class="bar" style="--final-width: <?= $percent ?>%;"></div>
                </div>

                <!-- numero affiche -->
                <div class="bar-num"><?= $percent ?>%</div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- top demandes par service -->
        <div class="graph-card">
            <h3> Répartition des demandes par service </h3>
            <div class="bar-container-v">
                <?php foreach($services as $s): ?>
                <div class="bar-c">
                    <!-- Barre verticale : hauteur proportionnelle au nombre de demandes -->
                     <?= $s['total'] ?>
                    <div class="bar-v"
                        style="--final-height: <?= $s['total'] * 10?>px; height:<?= $s['total'] * 10 ?>px;">
                        <!-- final-height :une animation avec transition (barre qui grandit progressivement) -->

                        
                    </div>


                    <!-- Nom du service -->
                    <div><?= htmlspecialchars($s['service']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>



        <!-- TABLE -->
        <div class="table-section">
            <h3>Demandes récentes </h3>
            <?php if (empty($dernieresDemandes)): ?>
            <div class="alert alert-info">Aucune demande trouvée.</div>
            <?php else: ?>
            <table class="table-beex">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Demandeur</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dernieresDemandes as $demande): ?>
                    <tr>
                        <td>#<?= isset($demande['id']) ? $demande['id'] : 'N/A' ?></td>
                        <td><?= isset($demande['demandeur']) ? htmlspecialchars($demande['demandeur']) : 'N/A' ?></td>
                        <td><?= isset($demande['type']) ? htmlspecialchars($demande['type']) : 'N/A' ?></td>
                        <td><?= isset($demande['date_creation']) ? $demande['date_creation'] : 'N/A' ?></td>
                        <td>
                            <?php 
                            $status = trim(strtolower($demande['status']));// enlever les espaces et tout mettre en minuscule
                            switch ($status) {
                                case 'en_cours':
                                    $badge='badge-en-cours';
                                    $statut = 'En Cours'; 
                                    break;
                                case 'validee':
                                    $badge = 'badge-validee';
                                    $statut = 'Validée'; 
                                    break;
                                case 'traite':
                                    $badge = 'badge-traite';
                                    $statut = 'Traitée'; 
                                    break;
                                default:
                                    $badge = 'badge-default';
                                    $statut = htmlspecialchars($demande['status']);
                            }

                    ?>
                            <span class="badge-status <?= $badge ?>"><?= $statut ?></span>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="voir-btn">
                <button class="voir_but"><a href="gestion_demande.php"><u>Voir plus</u></a></button>
            </div>
            <?php endif; ?>

        </div>


    </div>

    <script>
    document.querySelectorAll('.bar').forEach(bar => {
        const finalWidth = bar.style.getPropertyValue('--final-width');
        setTimeout(() => {
            bar.style.width = finalWidth;
        }, 100);
    });
    </script>

</body>

</html>