<?php
include __DIR__ . "/../../../backend/administrateur/gestion_utilisateurs.php";
require_once __DIR__ . "/../../../backend/authentification/database.php";
$gestionUtilisateurs = new GestionUtilisateurs();

$action = $_GET['action'] ?? '';

// Traiter la création
if ($action === 'creer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $nomComplet = trim($nom . ' ' . $prenom);
    $typeUser = $_POST['type_user'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $id_validateur = $_POST['id_validateur'] ?? null;
    $id_dep = $_POST['id_dep'] ?? null;
    $poste = $_POST['poste'] ?? null;

    try {
        $db = new Database();
        $pdo = $db->pdo;

        // Si c'est un demandeur et qu'un validateur est sélectionné, récupérer automatiquement son département
        if ($typeUser === 'Demandeur' && !empty($id_validateur)) {
            $id_dep = $gestionUtilisateurs->getDepartementValidateur($id_validateur);
        }

        if ($typeUser === 'Demandeur') {
            $sql = "INSERT INTO demandeur (nom_complet_d, email_d, mdps_d, id_validateur, id_dep, poste_d) 
                    VALUES (:nom, :email, :password, :id_validateur, :id_dep, :poste)";
        } else {
            $sql = "INSERT INTO validateur (nom_complet_v, email_v, mdps_v, id_dep) 
                    VALUES (:nom, :email, :password, :id_dep)";
        }

        $stmt = $pdo->prepare($sql);
        $params = [
            ':nom' => $nomComplet,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':id_dep' => $id_dep ?: null
        ];

        if ($typeUser === 'Demandeur') {
            $params[':id_validateur'] = $id_validateur ?: null;
            $params[':poste'] = $poste ?: null;
        }

        $stmt->execute($params);
        
        header('Location: gestion_utilisateur.php?msg=success');
        exit;
    } catch (PDOException $e) {
        $errorMsg = $e->getMessage();
        header('Location: creation_utilisateur.php?msg=error&error_msg=' . urlencode($errorMsg));
        exit;
    }
}

$chefs = $gestionUtilisateurs->getChefs();
$equipes = $gestionUtilisateurs->getEquipes();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Espace Administration - Créer un Utilisateur</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/administrateur assets/gestion_utilisateurs.css" rel="stylesheet">
</head>

<body>
    <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-<?= $_GET['msg'] === 'success' ? 'success' : 'danger' ?>" style="position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;">
        <?= $_GET['msg'] === 'success' ? 'Utilisateur créé avec succès' : ($_GET['error_msg'] ?? 'Une erreur est survenue') ?>
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
                    <a href="gestion_utilisateur.php" class="retour_dashboard"><i class="bi bi-arrow-left"></i> Retour à la page de gestion des utilisateurs</a>
                    <div class="page-title">Créer un Utilisateur</div>
                    <div class="page-sub">Ajouter un nouvel utilisateur au système</div>
                </div>

            </div>

            <div class="form-card">
                <form method="POST" action="creation_utilisateur.php?action=creer">
                    <div class="form-section">
                        <h5 class="form-section-title"><i class="bi bi-person"></i> Informations Personnelles</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-person"></i> Nom</label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-person"></i> Prénom</label>
                                <input type="text" name="prenom" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-lock"></i> Mot de passe</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                            <small class="form-text text-muted">Minimum 6 caractères</small>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="form-section-title"><i class="bi bi-person-badge"></i> Type d'Utilisateur</h5>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-person-badge"></i> Type d'utilisateur</label>
                            <select id="selectType" name="type_user" class="form-select" required>
                                <option value="">-- Sélectionner un type --</option>
                                <option value="Demandeur">Demandeur</option>
                                <option value="Chef">Chef</option>
                            </select>
                        </div>
                    </div>

                    <!-- Section Chef (pour Demandeur) -->
                    <div id="sectionChef" class="form-section" style="display: none;">
                        <h5 class="form-section-title"><i class="bi bi-person-check"></i> Chef Assigné</h5>
                        
                        <div class="chef-list">
                            <?php foreach ($chefs as $chef): ?>
                            <div class="chef-item">
                                <input type="radio" name="id_validateur" id="chef_<?= $chef['id'] ?>" value="<?= $chef['id'] ?>" class="form-check-input">
                                <label for="chef_<?= $chef['id'] ?>" class="form-check-label">
                                    <?= htmlspecialchars($chef['nom']) ?> - <?= htmlspecialchars($chef['email']) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Section Équipe (pour Chef) -->
                    <div id="sectionEquipe" class="form-section" style="display: none;">
                        <h5 class="form-section-title"><i class="bi bi-people"></i> Équipe Assignée</h5>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-building"></i> Département</label>
                            <select name="id_dep" id="selectEquipe" class="form-select">
                                <option value="">-- Sélectionner un département --</option>
                                <?php foreach ($equipes as $equipe): ?>
                                <option value="<?= $equipe['id'] ?>"><?= htmlspecialchars($equipe['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Section Poste (pour Demandeur) -->
                    <div id="sectionPoste" class="form-section" style="display: none;">
                        <h5 class="form-section-title"><i class="bi bi-briefcase"></i> Poste</h5>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-briefcase"></i> Poste</label>
                            <input type="text" name="poste" class="form-control" placeholder="Ex: Développeur Fullstack">
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="gestion_utilisateur.php" class="btn btn-light">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="../../jquery/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#selectType').on('change', function() {
                const selectedType = $(this).val();
                
                if (selectedType === 'Demandeur') {
                    $('#sectionChef').show();
                    $('#sectionEquipe').hide();
                    $('#sectionPoste').show();
                } else if (selectedType === 'Chef') {
                    $('#sectionChef').hide();
                    $('#sectionEquipe').show();
                    $('#sectionPoste').hide();
                } else {
                    $('#sectionChef').hide();
                    $('#sectionEquipe').hide();
                    $('#sectionPoste').hide();
                }
            });
        });
    </script>
</body>

</html>

