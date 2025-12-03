<?php
session_start();
require_once __DIR__ . '/../../../backend/demandeur_traitm/ajout_demand.php';
require_once __DIR__ . "/../../../backend/authentification/database.php";

// Vérification session
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../BEEX/frontend/authentification/login.php");
    exit;
}

$id_demandeur = $_SESSION['user_id'];
$pdo = (new Database())->pdo;

// Charger les types de besoin
require_once __DIR__ . "/../../../backend/demandeur/importer_type_besoins.php";
$typeBesoin = new TypeBesoin();
$types_besoin = $typeBesoin->getTypesBesoin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Etape 1 : créer la demande
    $demand_obj = new AddDemande();
    $id_demande = $demand_obj->addDemandeById($id_demandeur);

    if ($id_demande) {

        // -------------------
        //   LOGIQUE TRANSFERT
        // -------------------

        // Date de création au format date
        $date_creation = date('Y-m-d');

        // 1. Récupérer le valideur normal du demandeur
        $sql = "SELECT id_validateur FROM demandeur WHERE id_d = :id_demandeur";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_demandeur' => $id_demandeur]);
        $validateur_normal = $stmt->fetchColumn();

        // 2. Vérifier si ce valideur a un transfert actif
        $sqlTransfer = "SELECT id_validateur_recepteur
                        FROM transfer
                        WHERE id_validateur_createur = :validateur
                        AND :date_creation BETWEEN date_debut_tr AND date_fin_tr
                        LIMIT 1";

        $stmtTransfer = $pdo->prepare($sqlTransfer);
        $stmtTransfer->execute([
            ':validateur' => $validateur_normal,
            ':date_creation' => $date_creation
        ]);

        $validateur_recepteur = $stmtTransfer->fetchColumn();
        // Si la demande est transférée, mettre à jour le validateur
if ($validateur_recepteur) {
    $update = "UPDATE demande
               SET id_validateur = :id_validateur, transfere = 1
               WHERE id_dm = :id_dm";
    $stmtUpdate = $pdo->prepare($update);
    $stmtUpdate->execute([
        ':id_validateur' => $validateur_recepteur,
        ':id_dm' => $id_demande
    ]);
}

 
  
  

        // Succès
        $_SESSION['success_message'] = "La demande a été ajoutée avec succès.";
        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Impossible d’ajouter la demande.";
    }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'une demande</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/inf_demandeur.css">
</head>

<body>
    <?php include("header_menu.php"); ?>

    <div class="cote">
        <a href="dashboard.php" class="retour_dashboard"><i class="bi bi-arrow-left"></i> Retour à la page d'accueil</a>
        <h2>Créer une demande</h2>
    </div>

    <div class="main-content">
        <div class="container my-2">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm p-4">

                        <form action="" method="POST" enctype="multipart/form-data">
                            <!-- Type de besoin -->
                            <div class="mb-4">
                                <label for="type_besoin" class="form-label">
                                    Type de besoin <span class="required-indicator">*</span>
                                </label>
                                <select class="form-select filter-select" id="type_besoin" name="type_besoin" required>
                                    <option value="">Séléctionner un type</option>
                                    <?php foreach ($types_besoin as $type): ?>
                                    <option value="<?= htmlspecialchars($type['nom_tb']) ?>">
                                        <?= htmlspecialchars($type['nom_tb']) ?></option>
                                    <?php endforeach; ?>
                                </select>



                                <!-- Description -->
                                <div class="mb-4">
                                    <label for="description" class="form-label">
                                        Description <span class="required-indicator">*</span>
                                    </label>
                                    <textarea id="description" name="description" class="form-control" rows="6"
                                        placeholder="Décrivez votre besoin en détail..." required></textarea>
                                    <div class="form-text-muted">Décrivez votre besoin de manière claire et détaillée
                                    </div>
                                </div>

                                <!-- Urgence -->
                                <div class="mb-4">
                                    <label for="urgence" class="form-label">
                                        Urgence <span class="required-indicator">*</span>
                                    </label>
                                    <select id="urgence" name="urgence" class="form-control" required>
                                        <option value="" selected>Sélectionnez le niveau d'urgence</option>
                                        <option value="faible">Faible</option>
                                        <option value="normale">Normale</option>
                                        <option value="haute">Haute</option>
                                        <option value="critique">Critique</option>
                                    </select>
                                </div>

                                <!-- Date limite -->
                                <div class="mb-4">
                                    <label for="date_limite" class="form-label">
                                        Date limite <span class="required-indicator">*</span>
                                    </label>
                                    <input type="date" id="date_limite" name="date_limite" class="form-control"
                                        required>
                                    <div class="form-text-muted">Sélectionnez la date avant laquelle le besoin doit être
                                        satisfait
                                    </div>
                                </div>

                                <!-- Pièces jointes -->
                                <div class="mb-4">
                                    <label for="attachments" class="form-label">Pièces jointes</label>
                                    <input type="file" id="attachments" name="attachments[]" class="form-control"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png" multiple>
                                    <div class="form-text-muted">Formats acceptés : PDF, Word, Excel, Images (JPG, PNG)
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="btn-container mt-3 d-flex gap-3">
                                    <a href="dashboard.php" class="btn-cancel text-decoration-none">Annuler</a>
                                    <button type="submit" class="btn-save">Soumettre la demande</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>

</body>

</html>