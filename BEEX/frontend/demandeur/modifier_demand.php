<?php
session_start();
require_once __DIR__ . '/../../../backend/authentification/database.php';
require_once __DIR__ . '/../../../backend/demandeur_traitm/change_modifi.php';

$db = new Database();
$connexion = $db->pdo;
require_once __DIR__ . "/../../../backend/demandeur/importer_type_besoins.php";
$typeBesoin = new TypeBesoin();
$types_besoin = $typeBesoin->getTypesBesoin();
// Vérifier connexion
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../BEEX/frontend/authentification/login.php");
    exit;
}

$id_dm = $_GET['id'] ?? null;
if (!$id_dm) {
    die("Demande introuvable.");
}

$demandeObj = new changDeman();
$demande = $demandeObj->getDemandeById($id_dm);

if (!$demande) {
    die("Demande non trouvée.");
}

// Pré-remplissage
$type_besoin_actuel = $demande['type_besoin'] ?? '';
$description = $demande['description'] ?? '';
$urgence = $demande['urgence'] ?? '';
$date_limite = $demande['date_limite'] ?? '';
$attachments = $demande['attachments'] ?? '';
$previousPage = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
//gestion de piece jointe
$uploadDir = __DIR__ . '/../../../backend/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$newFile = null; // par défaut aucun fichier
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
    $filename = basename($_FILES['attachment']['name']); // nom original + extension
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
        $newFile = $filename; // on met à jour la colonne dans la base
    }
}
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_besoin_new = $_POST['type_besoin'] ?? null;
    $description_new = $_POST['description'] ?? '';
    $urgence_new = $_POST['urgence'] ?? '';
    $date_limite_new = $_POST['date_limite'] ?? '';

    if (!$type_besoin_new) {
        die("Veuillez sélectionner un type de besoin.");
    }

    $filename = $attachments; // conserver fichier existant

  

    // Mise à jour de la demande
    $demandeObj->updateDemande($id_dm, $type_besoin_new, $description_new, $urgence_new, $date_limite_new,  $newFile);

    // Message de succès via session
    echo "<script>
            alert('Les modifications ont été enregistrées avec succès !');
            window.location.href ='dashboard.php';
          </script>";
    exit;

}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la demande</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/inf_demandeur.css">
</head>

<body>
    <?php include("header_menu.php"); ?>

    <div class="cote">
        <a href="<?= htmlspecialchars($previousPage) ?>" class="retour_dashboard"><i class="bi bi-arrow-left"></i>
            Retour à la page d'accueil</a>
        <h2>Modifier la demande {<?= htmlspecialchars($id_dm) ?>}</h2>
    </div>

    <div class="main-content">
        <div class="container my-2">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm p-4">
                        <form method="post" action="" enctype="multipart/form-data">
                            <!-- Type de besoin -->
                            <div class="mb-4">
                                <label for="type_besoin" class="form-label">Type de besoin <span
                                        class="required-indicator">*</span></label>
                                <select class="form-select filter-select" id="type_besoin" name="type_besoin" required>
                                    <option value="">Sélectionner un type</option>
                                    <?php foreach ($types_besoin as $type): ?>
                                    <option value="<?= htmlspecialchars($type['nom_tb'], ENT_QUOTES) ?>"
                                        <?= ($type['nom_tb'] === $type_besoin_actuel) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($type['nom_tb']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label">Description <span
                                        class="required-indicator">*</span></label>
                                <textarea id="description" name="description" class="form-control" rows="6"
                                    required><?= htmlspecialchars($description) ?></textarea>
                                <div class="form-text-muted">Décrivez votre besoin de manière claire et détaillée</div>
                            </div>

                            <!-- Urgence -->
                            <div class="mb-4">
                                <label for="urgence" class="form-label">Urgence <span
                                        class="required-indicator">*</span></label>
                                <select id="urgence" name="urgence" class="form-control" required>
                                    <option value="">Sélectionnez le niveau d'urgence</option>
                                    <option value="faible" <?= $urgence == 'faible' ? 'selected' : '' ?>>Faible</option>
                                    <option value="normale" <?= $urgence == 'normale' ? 'selected' : '' ?>>Normale
                                    </option>
                                    <option value="haute" <?= $urgence == 'haute' ? 'selected' : '' ?>>Haute</option>
                                    <option value="critique" <?= $urgence == 'critique' ? 'selected' : '' ?>>Critique
                                    </option>
                                </select>
                            </div>

                            <!-- Date limite -->
                            <div class="mb-4">
                                <label for="date_limite" class="form-label">Date limite <span
                                        class="required-indicator">*</span></label>
                                <input type="date" id="date_limite" name="date_limite" class="form-control"
                                    value="<?= $date_limite ?>" required>
                            </div>

                            <input type="file" id="attachment" name="attachment" class="form-control">
                            <div class="form-text-muted mb-4">
                                Pièce jointe actuelle :
                                <?php if ($attachments): ?>
                                <a href="../../../backend/uploads/<?= htmlspecialchars($attachments) ?>" download="<?= htmlspecialchars($attachments) ?>">
                                    <?= htmlspecialchars($attachments) ?></a>
                                <?php else: ?>
                                Aucune pièce jointe.
                                <?php endif; ?>
                            </div>

                            <!-- Boutons -->
                            <div class="btn-container mt-3 d-flex gap-3">
                                <button type="reset" class="btn-reinit">Réinitialiser</button>
                                <a href="<?= htmlspecialchars($previousPage) ?>"
                                    class="btn-cancel text-decoration-none">Annuler</a>
                                <button type="submit" class="btn-save">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</body>

</html>