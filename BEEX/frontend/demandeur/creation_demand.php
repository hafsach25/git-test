<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creation du demande</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/inf_demandeur.css">
</head>

<body>
    <?php
       include ("header_menu.php") 
    ?>
    <div class="cote">
        <a href="dashboard.php" class="retour_dashboard">← Retour à la page d'acceuil</a>
        <h2>Creer une demande</h2>
    </div>

    <div class="main-content">
        <div class="form-wrapper d-flex justify-content-center">
            <div class="card shadow-sm p-4">
                <form>
                    <!-- Type de besoin -->
                    <div class="mb-4">
                        <label for="type_besoin" class="form-label">
                            Type de besoin
                            <span class="required-indicator">*</span>
                        </label>
                        <select id="type_besoin" name="type_besoin" class="form-control " required>
                            <option value="" selected>Sélectionnez un type</option>
                            <option value="infrastructure">Infrastructure IT</option>
                            <option value="logiciels">Logiciels</option>
                            <option value="formation">Formation</option>
                            <option value="equipement">Équipement</option>
                            <option value="services">Services externes</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">
                            Description
                            <span class="required-indicator">*</span>
                        </label>
                        <textarea id="description" name="description" class="form-control " rows="6"
                            placeholder="Décrivez votre besoin en détail..." required></textarea>
                        <div class="form-text-muted">Décrivez votre besoin de manière claire et détaillée</div>
                    </div>

                    <!-- Urgence -->
                    <div class="mb-4">
                        <label for="urgence" class="form-label">
                            Urgence
                            <span class="required-indicator">*</span>
                        </label>
                        <select id="urgence" name="urgence" class="form-control " required>
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
                            Date limite
                            <span class="required-indicator">*</span>
                        </label>
                        <input type="date" id="date_limite" name="date_limite" class="form-control" required>
                        <div class="form-text-muted">Sélectionnez la date avant laquelle le besoin doit être satisfait
                        </div>
                    </div>

                    <!-- Pièces jointes -->
                    <div class="mb-4">
                        <label for="attachments" class="form-label">
                            Pièces jointes
                        </label>
                        <div class="file-input-wrapper">
                            <input type="file" id="attachments" name="attachments" class="form-control input-beex"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png" multiple>
                        </div>
                        <div class="form-text-muted">Formats acceptés : PDF, Word, Excel, Images (JPG, PNG)</div>
                    </div>

                    <!-- Form Actions -->
                    <div class="btn-container">
                        <button type="button" class="btn-cancel">Annuler</button>
                        <button type="submit" class="btn-save">Soumettre la demande</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>