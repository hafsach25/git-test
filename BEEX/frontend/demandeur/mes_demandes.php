<?php 
session_start();
require_once __DIR__ ."/../../../backend/demandeur/importer_demandes.php"; 
$demandes = $_SESSION['imported_demandes'] ?? [];
require_once __DIR__ . "/../../../backend/demandeur/importer_type_besoins.php";
$typeBesoin = new TypeBesoin();
$types_besoin = $typeBesoin->getTypesBesoin();
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
            <h5><i class="bi bi-search"></i> Filtres de recherche</h5>
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
    // Objet mappant les statuts aux classes CSS et textes affichés
    const STATUS_MAP = {
        'en_attente': {
            badge: 'badge-attente',
            txt: 'En attente'
        },
        'en_cours': {
            badge: 'badge-en-cours',
            txt: 'En cours'
        },
        'validee': {
            badge: 'badge-validee',
            txt: 'Validée'
        },
        'traite': {
            badge: 'badge-traite',
            txt: 'Traité'
        },
        'rejete': {
            badge: 'badge-rejetee',
            txt: 'Rejetée'
        }
    };

    // Nombre d'éléments à afficher par page
    const ITEMS_PER_PAGE = 8;

    // Convertir les données PHP en objet JavaScript
    let demandes = <?= json_encode($demandes) ?>;

    // Copie des demandes qui sera filtrée
    let filteredDemandes = demandes;

    // Page actuellement affichée
    let currentPage = 1;

    // Fonction pour afficher le tableau avec les demandes de la page actuelle
    function renderTable() {
        // Calculer l'index de départ
        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        // Calculer l'index de fin
        const end = start + ITEMS_PER_PAGE;
        // Extraire les demandes de la page actuelle
        const page = filteredDemandes.slice(start, end);
        // Récupérer l'élément tbody du tableau
        const tbody = document.getElementById('tableBody');

        // Générer les lignes du tableau
        tbody.innerHTML = page.map(d => `
                    <tr>
                        <td><strong>#${d.id_dm}</strong></td>
                        <td>${d.type_besoin}</td>
                        <td>${d.date_creation_dm}</td>
                        <td><span class="badge-status ${(STATUS_MAP[d.status] || {}).badge}">${(STATUS_MAP[d.status] || {}).txt || d.status}</span></td>
                        <td><a href="detail_demand.php?id=${d.id_dm}" class="action-link">Voir</a></td>
                    </tr>
                `).join('');
    }

    // Fonction pour afficher les boutons de pagination
    function renderPagination() {
        // Calculer le nombre total de pages
        const totalPages = Math.ceil(filteredDemandes.length / ITEMS_PER_PAGE);
        // Récupérer l'élément pagination
        const pag = document.getElementById('pagination');
        // Vider le contenu précédent
        pag.innerHTML = '';

        // Créer le bouton "Précédent"
        const prevBtn = document.createElement('li');
        prevBtn.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevBtn.innerHTML = `<a class="page-link" href="#">«</a>`;
        prevBtn.onclick = () => goToPage(currentPage - 1);
        pag.appendChild(prevBtn);

        // Boucler pour créer les boutons numérotés
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            // Marquer la page actuelle comme active
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            li.onclick = () => goToPage(i);
            pag.appendChild(li);
        }

        // Créer le bouton "Suivant"
        const nextBtn = document.createElement('li');
        nextBtn.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextBtn.innerHTML = `<a class="page-link" href="#">»</a>`;
        nextBtn.onclick = () => goToPage(currentPage + 1);
        pag.appendChild(nextBtn);
    }

    // Fonction pour naviguer à une page spécifique
    function goToPage(page) {
        // Calculer le nombre total de pages
        const totalPages = Math.ceil(filteredDemandes.length / ITEMS_PER_PAGE);
        // Vérifier que la page est valide
        if (page >= 1 && page <= totalPages) {
            // Mettre à jour la page actuelle
            currentPage = page;
            // Réafficher le tableau
            renderTable();
            // Réafficher la pagination
            renderPagination();
        }
    }

    // Fonction pour appliquer les filtres
    function applyFilters() {
        // Récupérer la valeur du filtre type
        const typeFilter = document.getElementById('filterType').value;
        // Récupérer la valeur du filtre statut
        const statusFilter = document.getElementById('filterStatus').value;
        // Récupérer la valeur du filtre période
        const periodFilter = document.getElementById('filterPeriod').value;

        // Filtrer les demandes selon les critères
        filteredDemandes = demandes.filter(d => {
            // Si un type est sélectionné et ne correspond pas, exclure
            if (typeFilter && d.type_besoin !== typeFilter) return false;
            // Si un statut est sélectionné et ne correspond pas, exclure
            if (statusFilter && d.status !== statusFilter) return false;
            // Logique pour le filtre période à implémenter
            return true;
        });

        // Retourner à la première page
        currentPage = 1;
        // Réafficher le tableau
        renderTable();
        // Réafficher la pagination
        renderPagination();
    }

    // Ajouter écouteurs d'événements pour les changements de filtres
    document.getElementById('filterType').addEventListener('change', applyFilters);
    document.getElementById('filterStatus').addEventListener('change', applyFilters);
    document.getElementById('filterPeriod').addEventListener('change', applyFilters);

    // Affichage initial du tableau et de la pagination
    renderTable();
    renderPagination();
    </script>
</body>

</html>