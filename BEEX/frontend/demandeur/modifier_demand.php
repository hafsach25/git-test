<?php
session_start();
include __DIR__ . '/../../../backend/authentification/database.php';// inclure la connexion à la BD

// Supposons que tu passes un id_demande pour modifier
$id_demande = $_GET['id'] ?? 0;

// Récupérer les informations depuis la BD
$query = $connexion->prepare("SELECT * FROM demande WHERE id_dm = ?");
$query->execute([$id_demande]);
$demande = $query->fetch(PDO::FETCH_ASSOC);

$type_besoin = $demande['type_besoin'] ?? '';
$description = $demande['description'] ?? '';
$urgence = $demande['urgence'] ?? '';
$date_limite = $demande['date_limite'] ?? '';
// Pour les fichiers, tu peux afficher un lien ou un nom si nécessaire
$attachments = $demande['attachments'] ?? '';
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>informations personnelles </title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/inf_demandeur.css">
</head>

<body>
    <?php
include ("header_menu.php") ?>
    <div class="cote">
        <a href="dashboard.php" class="retour_dashboard">← Retour à la page d'acceuil</a>
        <h2>Modifier une demande</h2>
    </div>
    <div class="main-content">
        <div class="form-wrapper d-flex justify-content-center">
            <div class="card shadow-sm p-4" >
                <form>
                    <!-- Type de besoin -->
                    <div class="mb-4">
                        <label for="type_besoin" class="form-label">
                            Type de besoin
                            <span class="required-indicator">*</span>
                        </label>
<select id="type_besoin" name="type_besoin" class="form-control" required>
    <option value="" <?= $type_besoin == '' ? 'selected' : '' ?>>Sélectionnez un type</option>
    <option value="infrastructure" <?= $type_besoin == 'infrastructure' ? 'selected' : '' ?>>Infrastructure IT</option>
    <option value="logiciels" <?= $type_besoin == 'logiciels' ? 'selected' : '' ?>>Logiciels</option>
    <option value="formation" <?= $type_besoin == 'formation' ? 'selected' : '' ?>>Formation</option>
    <option value="equipement" <?= $type_besoin == 'equipement' ? 'selected' : '' ?>>Équipement</option>
    <option value="services" <?= $type_besoin == 'services' ? 'selected' : '' ?>>Services externes</option>
    <option value="autre" <?= $type_besoin == 'autre' ? 'selected' : '' ?>>Autre</option>
</select>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">
                            Description
                            <span class="required-indicator">*</span>
                        </label>
<textarea id="description" name="description" class="form-control" rows="6" required><?= htmlspecialchars($description) ?></textarea>
                        <div class="form-text-muted">Décrivez votre besoin de manière claire et détaillée</div>
                    </div>

                    <!-- Urgence -->
                    <div class="mb-4">
                        <label for="urgence" class="form-label">
                            Urgence
                            <span class="required-indicator">*</span>
                        </label>
<select id="urgence" name="urgence" class="form-control" required>
    <option value="" <?= $urgence == '' ? 'selected' : '' ?>>Sélectionnez le niveau d'urgence</option>
    <option value="faible" <?= $urgence == 'faible' ? 'selected' : '' ?>>Faible</option>
    <option value="normale" <?= $urgence == 'normale' ? 'selected' : '' ?>>Normale</option>
    <option value="haute" <?= $urgence == 'haute' ? 'selected' : '' ?>>Haute</option>
    <option value="critique" <?= $urgence == 'critique' ? 'selected' : '' ?>>Critique</option>
</select>
                    </div>

                    <!-- Date limite -->
                    <div class="mb-4">
                        <label for="date_limite" class="form-label">
                            Date limite
                            <span class="required-indicator">*</span>
                        </label>
                        <input type="date" id="date_limite" name="date_limite" class="form-control" value="<?= $date_limite ?>" required>

                        <div class="form-text-muted">Sélectionnez la date avant laquelle le besoin doit être satisfait
                        </div>
                    </div>

                    <!-- Pièces jointes -->
                    <!--<div class="mb-4">
                        <label for="attachments" class="form-label">
                            Pièces jointes
                        </label>
                        <div class="file-input-wrapper">
                            <input type="file" id="attachments" name="attachments" class="form-control input-beex"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png" multiple>
                        </div>
                        <div class="form-text-muted">Formats acceptés : PDF, Word, Excel, Images (JPG, PNG)</div>
                    </div>-->
                    <?php if ($attachments) : ?>
    <p>Fichiers existants : <a href="../../uploads/<?= $attachments ?>" target="_blank"><?= $attachments ?></a></p>
<?php endif; ?>
<input type="file" id="attachments" name="attachments" class="form-control" multiple>

                    <!-- Form Actions -->
                    <div class="btn-container">
                        <button type="button" class="btn-reinit">Reinitialiser</button>
                        <button type="button" class="btn-cancel">Annuler</button>
                        <button type="submit" class="btn-save">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>