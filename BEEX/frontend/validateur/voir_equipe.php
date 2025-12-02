<?php
session_start();
require_once __DIR__ . '/../../../backend/authentification/database.php';

// Vérification : seulement les validateurs ont accès
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'validateur') {
    header("Location: ../authentification/login.php");
    exit;
}

$validateur_id = $_SESSION['user_id'];

$db = new Database();
$conn = $db->pdo;

// Récupérer les demandeurs supervisés
$sql = "SELECT id_d, nom_complet_d, email_d
        FROM demandeur
        WHERE id_validateur = :id_validateur";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_validateur', $validateur_id, PDO::PARAM_INT);
$stmt->execute();

$demandeurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Équipe des Demandeurs – BEEX</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --beex-blue: #0049F9;
    --beex-green: #01BD96;
    --beex-red: #FF4757;
    --beex-bg: #FBFCF9;
    --beex-dark: black;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:var(--beex-bg);
    margin:0; padding:0;
}

.header{
    background: linear-gradient(145deg, var(--beex-blue), var(--beex-green));
    color:white;
    padding:16px 32px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.header-left{display:flex; align-items:center; gap:12px;}
.header-left img{width:50px;}
.user-avatar{
    width:40px;height:40px;border-radius:50%;
    background:linear-gradient(145deg,var(--beex-blue),var(--beex-green));
    color:white; display:flex;align-items:center;justify-content:center;
    font-weight:bold;
}

.main-content{padding:40px;}
.page-title{font-size:28px; font-weight:700; color:var(--beex-blue); margin-bottom:10px;}
.page-subtitle{color:#555; margin-bottom:30px;}

.table-section{
    background:white; 
    padding:20px; 
    border-radius:12px;
    box-shadow:0 6px 15px rgba(0,0,0,0.1); 
    margin-bottom:30px;
}

.btn-detail{
    padding:5px 10px;
    border:none;
    border-radius:8px;
    color:white;
    background:#555;
    font-weight:bold;
}
</style>
</head>
<body>
        <?php
require_once __DIR__ ."/../demandeur/header_menu.php";?>

<main class="main-content">
    <h1 class="page-title">Équipe des Demandeurs</h1>
    <p class="page-subtitle">Liste des demandeurs supervisés par vous</p>

    <div class="table-section">
        <h3>Liste des demandeurs</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
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
                        <td><?= htmlspecialchars($d['id_d']) ?></td>
                        <td><?= htmlspecialchars($d['nom_complet_d']) ?></td>
                        <td><?= htmlspecialchars($d['email_d']) ?></td>
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
