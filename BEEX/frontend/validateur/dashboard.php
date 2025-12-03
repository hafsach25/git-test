<?php
session_start();
require_once __DIR__ . '/../../../backend/validateur/update_status.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../../backend/validateur/dashboard.php';

$idValidateur = $_SESSION['user_id'];

// Instanciation de la classe
$dashboard = new DashboardStats(new Database());

// Statistiques classiques
$stats = $dashboard->getStats($idValidateur);
$recentDemandes = $dashboard->getRecentDemandes($idValidateur);
$topDemanders = $dashboard->getTopDemanders($idValidateur);

// 3 nouvelles statistiques
$taux = $dashboard->getTauxValidationRejet($idValidateur);
$evolution = $dashboard->getEvolutionMensuelle($idValidateur, date('Y'));




?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Validateur – BEEX</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/validateur assets/validateur.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <?php include_once __DIR__ . '/header_menu.php'; ?>
    <main class="main-content">

        <div class="page-header">
            <?php if($_GET['message'] ?? false): ?>
            <div class="alert alert-info"><?= htmlspecialchars($_GET['message']) ?></div>
            <?php endif; ?>
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? '') ?></p>
        </div>

        <div class="stats-container">
            <!-- Statistiques principales -->
            <div class="stat-card blue">
                <div class="stat-title">Demandes reçues</div>
                <div class="stat-value"><?= $stats['recu'] ?></div>
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

        <!-- Taux sous les stats principales -->
        <div class="taux-container">
            <div class="stat-card">
                <div class="stat-title">Taux de validation</div>
                <div class="stat-bar">
                    <div class="stat-bar-fill" style="width: <?= $taux['validation'] ?>%; background-color: green;">
                        <?= $taux['validation'] ?>%
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Taux de rejet</div>
                <div class="stat-bar">
                    <div class="stat-bar-fill" style="width: <?= $taux['rejet'] ?>%; background-color: red;">
                        <?= $taux['rejet'] ?>%
                    </div>
                </div>
            </div>
        </div>




        <!-- DERNIÈRES DEMANDES -->
        <div class="table-section">
            <h3>Dernières demandes</h3>
            <?php if (empty($recentDemandes)): ?>
            <div class="alert alert-info">Aucune demande trouvée.</div>
            <?php else: ?>
            <table class="table-beex">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Demandeur</th>
                        <th>Urgence</th>
                        <th>Date de création</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentDemandes as $demande): ?>
                    <tr>
                        <td><?= htmlspecialchars($demande['id_dm']) ?></td>
                        <td><?= htmlspecialchars($demande['demandeur_name']) ?></td>
                        <td>
                            <?php 
        switch ($demande['urgence']) {
            case 'faible':
                $badge = 'badge-faible';
                $txt = 'Faible';
                break;

            case 'normale':
                $badge = 'badge-normale';
                $txt = 'Normale';
                break;

            case 'haute':
                $badge = 'badge-haute';
                $txt = 'Haute';
                break;

            case 'critique':
                $badge = 'badge-critique';
                $txt = 'Critique';
                break;

            default:
                $badge = 'badge-default';
                $txt = htmlspecialchars($demande['urgence']);
        }
    ?>
                            <span class="badge-urgence <?= $badge ?>"><?= $txt ?></span>

                        </td>


                        <td><?= htmlspecialchars($demande['date_creation_dm']) ?></td>
                        <td>
                            <?php 
                            switch ($demande['statut']) {
                                case 'en_attente':
                                    $badge = 'badge-en-attente'; $txt = 'En attente'; break;
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
                                    $txt = htmlspecialchars($demande['statut']);
                            }
                        ?>
                            <span class="badge-status <?= $badge ?>"><?= $txt ?></span>
                        </td>
                        <td>
                            <?php if (intval($demande['transfere']) === 0): ?>
                            <?php if($demande['statut']==='en_attente'): ?>

                            <button type="submit" class="action-btn btn-accept"
                                id="<?= htmlspecialchars($demande['id_dm']) ?>">Valider</button>


                            <button type="submit" class="action-btn btn-reject"
                                id="<?= htmlspecialchars($demande['id_dm']) ?>">Rejeter</button>

                            <?php else: ?>
                            <button class="action-btn btn-accept disabled-btn" disabled>Valider</button>
                            <button class="action-btn btn-reject disabled-btn" disabled>Rejeter</button>
                            <?php endif; ?>
                            <?php else: ?>
                            <span class="text-muted">Transférée à :
                                <?= htmlspecialchars($demande['recepteur_name']) ?></span>
                            <?php endif; ?>
                            <button class="action-btn btn-detail"
                                id="<?= htmlspecialchars($demande['id_dm']) ?>">Détails</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


            <div class="voir-btn">
                <button class="voir_but"><a href="historique.php"><u>Voir plus</u></a></button>
            </div>
        </div>

        <?php endif; ?>

        <!-- TOP 10 DEMANDEURS -->
        <div id="top-demanders">
            <h3>Top 10 demandeurs actifs</h3>
            <?php foreach ($topDemanders as $d): ?>
            <?php
                $percentage = ($topDemanders[0]['total'] > 0)
                                ? round(($d['total'] / $topDemanders[0]['total']) * 100)
                                : 0;
            ?>
            <div class="bar-container">
                <span class="bar-label"><?= htmlspecialchars($d['nom_complet_d']) ?></span>
                <div class="bar" style="width: <?= $percentage ?>%;"></div>
                <span class="bar-value"><?= htmlspecialchars($d['total']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ÉVOLUTION MENSUELLE DES DEMANDES -->
        <div class="table-section">
            <h3>Évolution mensuelle des demandes</h3>
            <canvas id="evolutionChart" height="120"></canvas>
        </div>

    </main>

    <script>
    const ctx = document.getElementById('evolutionChart').getContext('2d');
    const evolutionData = {
        labels: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sep", "Oct", "Nov", "Déc"],
        datasets: [{
                label: 'Total',
                data: [<?= implode(',', array_column($evolution,'total')) ?>],
                borderColor: 'blue',
                backgroundColor: 'rgba(0,0,255,0.1)',
                fill: true
            },
            {
                label: 'Validées',
                data: [<?= implode(',', array_column($evolution,'validees')) ?>],
                borderColor: 'green',
                backgroundColor: 'rgba(0,255,0,0.1)',
                fill: true
            },
            {
                label: 'Rejetées',
                data: [<?= implode(',', array_column($evolution,'rejetees')) ?>],
                borderColor: 'red',
                backgroundColor: 'rgba(255,0,0,0.1)',
                fill: true
            }
        ]
    };
    new Chart(ctx, {
        type: 'line',
        data: evolutionData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
    //btn rejeter et valider

    // Gérer les boutons Valider et Rejeter
    document.querySelectorAll('.btn-accept, .btn-reject').forEach(function(btn) {
        // On ne fait rien si le bouton est désactivé
        if (btn.disabled) return;

        btn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                const isAccept = this.classList.contains('btn-accept');
                const demandeId = this.id;
                // Redirection vers le script PHP de mise à jour
                window.location.href = '../../../backend/validateur/update_status.php?id_dm=' +
                    demandeId + '&action=' + (isAccept ? 'validee' : 'rejete');
            }
        });
    });

    // Gérer les boutons Détails
    document.querySelectorAll('.btn-detail').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const demandeId = this.id;
            window.location.href = 'detail_demande.php?id=' + demandeId;
        });
    });
    </script>

</body>

</html>