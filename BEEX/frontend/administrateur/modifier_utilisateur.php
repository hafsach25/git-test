<?php
include __DIR__ . "/../../../backend/administrateur/gestion_utilisateurs.php";
require_once __DIR__ . "/../../../backend/authentification/database.php";
$gestionUtilisateurs = new GestionUtilisateurs();

$idUser = $_GET['id'] ?? '';
$typeUser = $_GET['type'] ?? '';

if (empty($idUser) || empty($typeUser)) {
    header('Location: gestion_utilisateur.php?msg=error&error_msg=Paramètres manquants');
    exit;
}

// Traiter la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $nomComplet = trim($nom . ' ' . $prenom);
    
    $id_validateur = $_POST['id_validateur'] ?? null;
    $id_dep = $_POST['id_dep'] ?? null;
    
    // Si c'est un demandeur et qu'un validateur est sélectionné, récupérer automatiquement son département
    if ($typeUser === 'Demandeur' && !empty($id_validateur)) {
        $id_dep = $gestionUtilisateurs->getDepartementValidateur($id_validateur);
    }
    
    $data = [
        'nom' => $nomComplet,
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'id_validateur' => $id_validateur,
        'id_dep' => $id_dep,
        'poste' => $_POST['poste'] ?? null
    ];
    
    $result = $gestionUtilisateurs->modifierUtilisateur($idUser, $typeUser, $data);
    if ($result['success']) {
        header('Location: gestion_utilisateur.php?msg=success');
        exit;
    } else {
        $errorMsg = $result['message'];
    }
}

// Charger les données de l'utilisateur
$user = $gestionUtilisateurs->getUserById($idUser, $typeUser);

if (!$user) {
    header('Location: gestion_utilisateur.php?msg=error&error_msg=Utilisateur non trouvé');
    exit;
}

// Séparer nom et prénom
$nomPrenom = explode(' ', $user['nom_prenom'], 2);
$prenom = $nomPrenom[1] ?? '';
$nom = $nomPrenom[0] ?? $user['nom_prenom'];

$chefs = $gestionUtilisateurs->getChefs();
$equipes = $gestionUtilisateurs->getEquipes();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Espace Administration - Modifier l'Utilisateur</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/administrateur assets/gestion_utilisateurs.css" rel="stylesheet">
</head>

<body>
    <?php if (isset($errorMsg)): ?>
    <div class="alert alert-danger" style="position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;">
        <?= htmlspecialchars($errorMsg) ?>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.alert').style.display = 'none';
        }, 3000);
    </script>
    <?php endif; ?>

    <?php include "header_menu.php" ?>
    
    <main class="main-content">
        <section class="content">
            <div class="header-row">
                <div>
                    <a href="dashboard.php" class="retour_dashboard"><i class="bi bi-arrow-left"></i> Retour à la page d'acceuil</a>
                    <div class="page-title">Modifier l'utilisateur</div>
                    <div class="page-sub">Modifier les informations de l'utilisateur</div>
                </div>
                <a href="gestion_utilisateur.php" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <div class="form-card">
                <form method="POST" action="modifier_utilisateur.php?id=<?= htmlspecialchars($idUser) ?>&type=<?= htmlspecialchars($typeUser) ?>">
                    <div class="form-section">
                        <h5 class="form-section-title"><i class="bi bi-person"></i> Informations Personnelles</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-person"></i> Nom</label>
                                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-person"></i> Prénom</label>
                                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($prenom) ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-person-badge"></i> Type d'utilisateur</label>
                            <select id="selectType" name="type_user" class="form-select" required disabled>
                                <option value="Demandeur" <?= $typeUser === 'Demandeur' ? 'selected' : '' ?>>Demandeur</option>
                                <option value="Chef" <?= $typeUser === 'Chef' ? 'selected' : '' ?>>Chef</option>
                            </select>
                            <small class="form-text text-muted">Le type d'utilisateur ne peut pas être modifié</small>
                        </div>
                        
        

                    <!-- Section Chef (pour Demandeur) -->
                    <?php if ($typeUser === 'Demandeur'): ?>
                    <div id="sectionChef" class="form-section">
                        <h5 class="form-section-title"><i class="bi bi-person-check"></i> Chef Assigné</h5>
                        
                        <div class="chef-list">
                            <?php foreach ($chefs as $chef): ?>
                            <div class="chef-item">
                                <input type="radio" name="id_validateur" id="chef_<?= $chef['id'] ?>" value="<?= $chef['id'] ?>" 
                                       class="form-check-input" <?= isset($user['id_validateur']) && $user['id_validateur'] == $chef['id'] ? 'checked' : '' ?>>
                                <label for="chef_<?= $chef['id'] ?>" class="form-check-label">
                                    <?= htmlspecialchars($chef['nom']) ?> - <?= htmlspecialchars($chef['email']) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Section Poste (pour Demandeur) -->
                    <div id="sectionPoste" class="form-section">
                        <h5 class="form-section-title"><i class="bi bi-briefcase"></i> Poste</h5>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-briefcase"></i> Poste</label>
                            <input type="text" name="poste" class="form-control" 
                                   value="<?= htmlspecialchars($user['poste'] ?? '') ?>" 
                                   placeholder="Ex: Développeur Fullstack">
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Section Équipe (pour Chef) -->
                    <?php if ($typeUser === 'Chef'): ?>
                    <div id="sectionEquipe" class="form-section">
                        <h5 class="form-section-title"><i class="bi bi-people"></i> Équipe Assignée</h5>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-building"></i> Département</label>
                            <select name="id_dep" id="selectEquipe" class="form-select">
                                <option value="">-- Sélectionner un département --</option>
                                <?php foreach ($equipes as $equipe): ?>
                                <option value="<?= $equipe['id'] ?>" 
                                        <?= isset($user['id_dep']) && $user['id_dep'] == $equipe['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($equipe['nom']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="form-actions">
                        <a href="gestion_utilisateur.php" class="btn btn-light">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="../../jquery/jquery-3.7.1.min.js"></script>
    <script>
        function copyPassword() {
            const passwordField = document.getElementById('inputPassword');
            if (passwordField.value) {
                passwordField.select();
                document.execCommand('copy');
                alert('Mot de passe copié dans le presse-papiers');
            } else {
                alert('Aucun mot de passe à copier');
            }
        }

        function resetPassword() {
            if (confirm('Voulez-vous générer un nouveau mot de passe ?')) {
                const newPassword = Math.random().toString(36).slice(-8) + Math.random().toString(36).slice(-8);
                document.getElementById('inputPassword').value = newPassword;
            }
        }
    </script>
</body>

</html>

