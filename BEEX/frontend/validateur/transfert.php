<?php
session_start();
require_once __DIR__ . '/../../../backend/validateur/transfert_demande.php';

// Vérifier connexion AVANT d'utiliser la session
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../BEEX/frontend/authentification/login.php");
    exit;
}

require_once __DIR__ . '/../../../backend/authentification/database.php';

$db = new Database();
$connexion = $db->pdo;

// Page précédente
$previousPage = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';

// ID utilisateur connecté
$id_actuel = $_SESSION['user_id'] ?? 0;
$transfertDemande = new TransfertDemande($id_actuel);

// Récupérer les autres validateurs
$validateurs = $transfertDemande->getAutresValidateurs();
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_debut = $_POST['date_debut'] ?? null;
    $date_fin = $_POST['date_fin'] ?? null;
    $raison = $_POST['raison'] ?? '';
    $transfert = $_POST['recepteur'] ?? '';

    if (!$date_debut || !$date_fin || !$transfert) {
        die("Veuillez remplir tous les champs obligatoires.");
    }
    if ($date_fin < $date_debut) {
        die("La date fin ne peut pas être inférieure à la date début.");
    }

    try {
        $transfertDemande->transfererDemande($transfert, $date_debut, $date_fin, $raison);
        echo "<script>
                alert('Le transfert a été envoyé avec succès !');
                window.location.href = 'dashboard.php';
              </script>";
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de l'enregistrement du transfert : " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>transferer la demande</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/inf_demandeur.css">
</head>

<body>
    <?php 
    require_once __DIR__ . '/header_menu.php'; ?>

    <div class="cote">
        <a href="<?= htmlspecialchars($previousPage) ?>" class="retour_dashboard"><i class="bi bi-arrow-left"></i>
            Retour à la page d'accueil</a>
        <h2>Transférer les demandes à un autre validateur</h2>
    </div>

    <div class="main-content">
        <div class="container my-2">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm p-4">
                        <form method="post" action="" enctype="multipart/form-data">

                            <!-- Date début -->
                            <div class="mb-4">
                                <label for="date_debut" class="form-label">Date début<span
                                        class="required-indicator">*</span></label>
                                <input type="date" id="date_debut" name="date_debut" class="form-control" required>
                            </div>

                            <!-- Date fin -->
                            <div class="mb-4">
                                <label for="date_fin" class="form-label">Date fin <span
                                        class="required-indicator">*</span></label>
                                <input type="date" id="date_fin" name="date_fin" class="form-control" required>
                            </div>

                            <!-- Transfert -->
                            <div class="mb-4">
                                <label for="transfert" class="form-label">Transférer à<span
                                        class="required-indicator">*</span></label>
                                <select id="transfert" name="recepteur" class="form-control" required>
                                    <option value="">--Sélectionnez un validateur--</option>
                                    <?php foreach ($validateurs as $v) : ?>
                                    <option value="<?= htmlspecialchars($v['id_v']) ?>">
                                        <?= htmlspecialchars($v['nom_complet_v']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Commentaire -->
                            <div class="mb-3">
                                <label for="comment" class="form-label">Commentaire / raison du transfert</label>
                                <textarea class="form-control" id="comment" rows="3" name="raison"
                                    placeholder="Ex: En congé du 25/11 au 30/11"></textarea>
                            </div>
                            <!-- Boutons -->
                            <div class="btn-container mt-3 d-flex gap-3">
                                <button type="reset" class="btn-reinit">Réinitialiser</button>
                                <a href="<?= htmlspecialchars($previousPage) ?>"
                                    class="btn-cancel text-decoration-none">Annuler</a>
                                <button type="submit" class="btn-save">Transférer les données</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>