<?php 
session_start();
include __DIR__ . "/../../../backend/demandeur/importer_demandes.php"; 
include __DIR__ . "/../../../backend/authentification/database.php";
$demandes = $_SESSION['imported_demandes'] ?? []; 

// Récupération des types de besoin pour le filtre
$db = new Database();
$query = $db->getConnection()->query("SELECT DISTINCT type_besoin FROM votre_table_besoin");
$types_besoin = $query->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BEEX Demandeur - Mes demandes</title>
    <link rel="stylesheet" href="../../assets/demandeur assets/dashboard.css">
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include "header_menu.php"; ?>

    <main class="main-content">
        <!-- Filtres -->
        <div class="filters-card">
            <h5>🔍 Filtres de recherche</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Type de besoin</label>
                    <select class="form-select filter-select" id="filterType">
                        <option value="">Tous les types</option>
                        <?php foreach ($types_besoin as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Statut</label>
                    <select class="form-select filter-select" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="en_cours">En cours</option>
                        <option value="validee">Validée</option>
                        <option value="rejete">Rejetée</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Période</label>
                    <select class="form-select filter-select" id="filterPeriod">
                        <option value="">Toute la période</option>
                        <option value="7days">Derniers 7 jours</option>
                        <option value="1month">Dernier mois</option>
                        <option value="3months">Derniers 3 mois</option>
                        <option value="6months">Derniers 6 mois</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- En-tête -->
        <div class="page-header">
            <h1 class="page-title">Mes demandes</h1>
        </div>

        <?php if (empty($demandes)): ?>
            <div class="alert alert-info">Aucune demande trouvée.</div>
        <?php else: ?>
            <!-- Table -->
            <div class="table-section">
                <h3>Dernières demandes</h3>
                <table class="table-beex">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type de besoin</th>
                            <th>Date de création</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Généré par JavaScript -->
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav aria-label="Pagination des demandes">
                    <ul class="pagination pagination-beex" id="pagination"></ul>
                </nav>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // Le reste de votre script JavaScript reste inchangé
    </script>
</body>
</html>
