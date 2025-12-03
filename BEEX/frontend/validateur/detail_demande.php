<?php
session_start();
include_once __DIR__ . '/../../../backend/validateur/detaildmd.php';
$detailDmd = new DetailDmd();
$id_dm = $_GET['id'] ?? null;
$demande = $detailDmd->getDetails((int)$id_dm);
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails demandes</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <link rel="stylesheet" href="../../assets/validateur assets/detaildmd.css">
</head>

<body>
    <?php
include ("header_menu.php") ?>
    <div class="cote">
        <a href="dashboard.php" class="retour_dashboard"><i class="bi bi-arrow-left"></i> Retour à la page d'accueil</a>
        <h2>détails de la demande {<?= htmlspecialchars($id_dm ) ?>}</h2>
    </div>
        <div class="main-content1">
            <div class="form-wrapper d-flex justify-content-center">
                <div class="card shadow-sm p-4">
                    <h2>informations générales</h2>
                    <div class="content-inter">
                        <div class="content-inter-left">
                            <p><strong>Demandeur :</strong> <span
                                    class="value"><?= htmlspecialchars($demande['demandeur']) ?></span></p>
                                    <button type="button" class="btn btn-secondary btn-sm mb-2" id="<?= htmlspecialchars($demande['id_demandeur']); ?>">Voir les details du demandeur</button>
                            <p><strong>Type de besoin :</strong><span class="value">
                                    <?= htmlspecialchars($demande['type_besoin']) ?></span></p>
                            <p><strong>Urgence :</strong> <?php 
        switch ($demande['urgence_dm']) {
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
                $txt = htmlspecialchars($demande['urgence_dm']);
        }
    ?>
    <span class="badge-urgence <?= $badge ?>"><?= $txt ?></span></p>
                        </div>
                        <div class="content-inter-right">
                            <p><strong>Date de création :</strong> <span
                                    class="value"><?= htmlspecialchars($demande['date_creation_dm']) ?></span></p>
                            <p><strong>Departement:</strong><span class="value">
                                    <?= htmlspecialchars($demande['departement']) ?></span></p>
                            <p><strong>Statut actuel :</strong> <?php 
                            switch ($demande['status']) {
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
                                    $txt = htmlspecialchars($demande['status']);
                            }
                        ?>
                            <span class="badge-status <?= $badge ?>"><?= $txt ?></span></p>
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
                        <a href="../../../backend/uploads/<?= htmlspecialchars($demande['fichier']) ?>" download>
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
       <?php if($demande['status']==='en_attente'): ?>

    <button type="button" class="action-btn btn-accept" id="<?= htmlspecialchars($id_dm) ?>">Valider</button>


    <button type="button" class="action-btn btn-reject" id="<?= htmlspecialchars($id_dm) ?>">Rejeter</button>

<?php else: ?>
<button class="action-btn btn-accept disabled-btn" disabled>Valider</button>
<button class="action-btn btn-reject disabled-btn" disabled>Rejeter</button>
<?php endif; ?>
    </div>
</div>
<script>
   //btn rejeter et valider
$('.btn-accept, .btn-reject').on('click', function() {
        if (confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
            const isAccept = $(this).hasClass('btn-accept');
            const demandeId = $(this).attr('id');
            window.location.href = '../../../backend/validateur/update_status.php?id_dm=' + demandeId + '&action=' + (isAccept ? 'validee' : 'rejete');

        }
    });
$('.btn-secondary').on('click', function() {
        const demandeurId = $(this).attr('id');
        window.location.href = 'details_demandeur.php?id=' + demandeurId;
    });
</script>