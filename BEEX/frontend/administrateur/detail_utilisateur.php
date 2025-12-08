<?php
include __DIR__ . "/../../../backend/administrateur/gestion_utilisateurs.php";
$gestionUtilisateurs = new GestionUtilisateurs();

$idUser = $_GET['id'] ?? '';
$typeUser = $_GET['type'] ?? '';

if (empty($idUser) || empty($typeUser)) {
    header('Location: gestion_utilisateur.php');
    exit;
}

$user = $gestionUtilisateurs->getUserById($idUser, $typeUser);

if (!$user) {
    header('Location: gestion_utilisateur.php?msg=error&error_msg=Utilisateur non trouvé');
    exit;
}

// Si c'est un validateur, récupérer son équipe
$equipe = [];
if ($typeUser === 'Chef') {
    $equipe = $gestionUtilisateurs->getEquipeValidateur($idUser);
}

// Séparer nom et prénom
$nomPrenom = explode(' ', $user['nom_prenom'], 2);
$prenom = $nomPrenom[1] ?? '';
$nom = $nomPrenom[0] ?? $user['nom_prenom'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Espace Administration - Détails Utilisateur</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/administrateur assets/gestion_utilisateurs.css" rel="stylesheet">
</head>

<body>
    <?php include "header_menu.php" ?>
    
    <main class="main-content">
        <section class="content">
            <div class="header-row">
                <div>
                    <a href="dashboard.php" class="retour_dashboard"><i class="bi bi-arrow-left"></i> Retour à la page d'acceuil</a>
                    <div class="page-title">Détails de l'Utilisateur</div>
                    <div class="page-sub">Informations complètes de l'utilisateur</div>
                </div>
                <div>
                    <a href="gestion_utilisateur.php" class="btn btn-light me-2">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                    <a href="modifier_utilisateur.php?id=<?= htmlspecialchars($idUser) ?>&type=<?= htmlspecialchars($typeUser) ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="detail-title-section">
                        <h3><?= htmlspecialchars($user['nom_prenom']) ?></h3>
                        <span class="badge-pill <?= $typeUser === 'Chef' ? 'badge-chef' : 'badge-demandeur' ?>">
                            <?= htmlspecialchars($typeUser) ?>
                        </span>
                    </div>
                </div>

                <div class="detail-content">
                    <div class="detail-section">
                        <h5 class="detail-section-title"><i class="bi bi-info-circle"></i> Informations Personnelles</h5>
                        
                        <div class="detail-row">
                            <div class="detail-label"><i class="bi bi-person"></i> Nom :</div>
                            <div class="detail-value"><?= htmlspecialchars($nom) ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label"><i class="bi bi-person"></i> Prénom :</div>
                            <div class="detail-value"><?= htmlspecialchars($prenom) ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label"><i class="bi bi-envelope"></i> Email :</div>
                            <div class="detail-value"><?= htmlspecialchars($user['email']) ?></div>
                        </div>
                        
                        <?php if ($typeUser === 'Demandeur' && isset($user['poste'])): ?>
                        <div class="detail-row">
                            <div class="detail-label"><i class="bi bi-briefcase"></i> Poste :</div>
                            <div class="detail-value"><?= htmlspecialchars($user['poste']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($typeUser === 'Demandeur'): ?>
                    <div class="detail-section">
                        <h5 class="detail-section-title"><i class="bi bi-person-check"></i> Chef Assigné</h5>
                        
                        <?php if (isset($user['chef_nom'])): ?>
                        <div class="detail-row">
                            <div class="detail-label"><i class="bi bi-person-check"></i> Chef :</div>
                            <div class="detail-value">
                                <strong><?= htmlspecialchars($user['chef_nom']) ?></strong>
                                <?php if (isset($user['chef_email'])): ?>
                                <br><small style="color:#6b7280;"><i class="bi bi-envelope"></i> <?= htmlspecialchars($user['chef_email']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="detail-row">
                            <div class="detail-label">Chef :</div>
                            <div class="detail-value"><em style="color:#999;">Non assigné</em></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($user['departement'])): ?>
                    <div class="detail-section">
                        <h5 class="detail-section-title"><i class="bi bi-building"></i> Département</h5>
                        
                        <div class="detail-row">
                            <div class="detail-label"><i class="bi bi-building"></i> Département :</div>
                            <div class="detail-value"><?= htmlspecialchars($user['departement']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($typeUser === 'Chef'): ?>
                    <div class="detail-section">
                        <h5 class="detail-section-title"><i class="bi bi-people"></i> Équipe</h5>
                        
                        <?php if (!empty($equipe)): ?>
                            <div class="equipe-members-list">
                                <?php foreach ($equipe as $membre): ?>
                                <div class="equipe-member-item">
                                    <div class="member-info">
                                        <div class="member-name">
                                            <i class="bi bi-person"></i>
                                            <strong><?= htmlspecialchars($membre['nom']) ?></strong>
                                        </div>
                                        <div class="member-email">
                                            <i class="bi bi-envelope"></i>
                                            <?= htmlspecialchars($membre['email']) ?>
                                        </div>
                                        <?php if (!empty($membre['poste'])): ?>
                                        <div class="member-poste">
                                            <i class="bi bi-briefcase"></i>
                                            <?= htmlspecialchars($membre['poste']) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <a href="detail_utilisateur.php?id=<?= $membre['id'] ?>&type=Demandeur" class="btn-view-member" title="Voir détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="detail-row">
                                <div class="detail-label">Membres :</div>
                                <div class="detail-value"><em style="color:#999;">Aucun membre dans cette équipe</em></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    </div>
                </div>

                <div class="detail-actions">
                    <a href="gestion_utilisateur.php" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                    <a href="modifier_utilisateur.php?id=<?= htmlspecialchars($idUser) ?>&type=<?= htmlspecialchars($typeUser) ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                </div>
            </div>
        </section>
    </main>

    
   
</body>

</html>

