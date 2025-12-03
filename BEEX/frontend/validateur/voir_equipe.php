<?php
session_start();
require_once __DIR__ . '/../../../backend/authentification/database.php';
require_once __DIR__ . '/../../../backend/validateur/recup_equipe.php';

// Vérification : seulement les validateurs ont accès
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../BEEX/frontend/authentification/login.php");
    exit;
}
$db = new Database();
$conn = $db->pdo;

$validateur_id = $_SESSION['user_id'];
$validateur_id = $_SESSION['user_id'] ?? 0;
$validateur = new Validateur($validateur_id);

// Récupérer les demandeurs supervisés
$demandeurs = $validateur->getDemandeursSupervises();

// Page précédente
$previousPage = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Équipe des Demandeurs – BEEX</title>

    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/validateur assets/equipe.css">

<body>
    <?php
require_once __DIR__ ."/header_menu.php";?>

    <main class="main-content">
        <div class="cote">
            <a href="dashboard.php" class="retour_dashboard text-decoration-none"><i class="bi bi-arrow-left "></i>
                Retour à la page d'acceuil</a>
        </div>
        <h1 class="page-title">Équipe des Demandeurs</h1>
        <p class="page-subtitle">Liste des demandeurs supervisés par vous</p>

        <div class="table-section">
            <table class="table table-hover">
                <thead>
                    <tr>

                        <th>Nom</th>
                        <th>Email</th>
                        <th>nombre de demandes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (empty($demandeurs)): ?>
                    <tr>
                        <td colspan="4" class="text-danger text-center">
                            Aucun demandeur n’est associé à votre compte.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($demandeurs as $d): ?>
                    <tr>

                        <td><?= htmlspecialchars($d['nom_complet_d']) ?></td>
                        <td><?= htmlspecialchars($d['email_d']) ?></td>
                        <td><?= $d['nb_demandes'] ?></td>


                        <td>
                            <a href="details_demandeur.php?id=<?= $d['id_d'] ?>">
                                <button class="btn-detail">Détails</button>
                            </a>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </main>
</body>

</html>