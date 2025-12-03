<?php 
session_start();
include __DIR__ . "/../../../backend/validateur/historique.php";
$historiqueValidateur = new HistoriqueValidateur();
$idValidateur = $_SESSION['user_id'] ?? null;
$demandes = $historiqueValidateur->getHistoriqueDemandes($idValidateur);
include __DIR__ . "/../../../backend/demandeur/importer_type_besoins.php";
$typesBesoinObj = new TypeBesoin();
$types_besoin = $typesBesoinObj->getTypesBesoin();
include __DIR__."/../../../backend/validateur/dashboard.php";
$dsh=new DashboardStats();
$demandesTransferees=$dsh-> getDemandesTransfereAuValidateurCourant($idValidateur);


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BEEX Demandeur - Mes demandes</title>
    <link rel="stylesheet" href="../../assets/validateur assets/historique.css">
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
                    <select class="form-select filter-select" id="filterType" name="type_besoin">
                        <option value="">Séléctionner un type</option>
                        <?php foreach ($types_besoin as $type): ?>
                            <option value="<?= htmlspecialchars($type['nom_tb']) ?>"><?= htmlspecialchars($type['nom_tb']) ?></option>
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
                <div class="col-md-4 mb-3">
    <label class="form-label fw-bold">Rechercher un demandeur</label>
    <input type="text" id="filterSearch" class="form-control" placeholder="Nom complet...">
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
                            <th>Demandeur</th>
                            <th>Urgence</th>
                            <th>Type de besoin</th>
                            <th>Date de création</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Rempli par JS -->
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav aria-label="Pagination des demandes">
                    <ul class="pagination pagination-beex" id="pagination"></ul>
                </nav>
            </div>
        <?php endif; ?>
        <?php if (!empty($demandesTransferees)): ?>
        <!-- DEMANDES TRANSFÉRÉES AU VALIDATEUR COURANT --> 
        <div class="table-section" style="margin-top: 30px">
            <h3>Demandes transférées au validateur</h3>
            <table class="table-beex">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Demandeur</th>
                        <th>Envoyé par</th>
                        <th>Urgence</th>
                        <th>Date de création</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($demandesTransferees as $demande): ?>
                    <tr>
                        <td><?= htmlspecialchars($demande['id_dm']) ?></td>
                        <td><?= htmlspecialchars($demande['demandeur_name']) ?></td>
                        <td><?= htmlspecialchars($demande['valideur_envoyeur_name']) ?></td>
                        <td>
                            <?php 
        switch ($demande['urgence']) {
            case 'faible':
                $badge = 'badge-faible';
                $txt = 'Faible';
                break;

            case 'normale':
                $badge = 'badge-normale';
                $txt = 'Normale';
                break;

            case 'haute':
                $badge = 'badge-haute';
                $txt = 'Haute';
                break;

            case 'critique':
                $badge = 'badge-critique';
                $txt = 'Critique';
                break;

            default:
                $badge = 'badge-default';
                $txt = htmlspecialchars($demande['urgence']);
        }
    ?>
                            <span class="badge-urgence <?= $badge ?>"><?= $txt ?></span>

                        </td>
                        <td><?= htmlspecialchars($demande['date_creation_dm']) ?></td>
                        <td>
                            <?php 
                            switch ($demande['statut']) {
                                case 'en_attente':
                                    $badge = 'badge-en-attente'; $txt = 'En attente'; break;
                                case 'en_cours':
                                    $badge = 'badge-en-cours'; $txt = 'En cours'; break;
                                case 'validee':
                                    $badge = 'badge-validee'; $txt = 'Validée'; break;
                                case 'traite':
                                    $badge = 'badge-traite'; $txt = 'Traité'; break;
                                case 'rejete':
                                    $badge = 'badge-rejetee'; $txt = 'Rejetée'; break;
                                default:
                                    $badge = 'badge-default'; 
                                    $txt = htmlspecialchars($demande['statut']);
                            }
                            ?>
                            <span class="badge-status <?= $badge ?>"><?= $txt ?></span>
                        </td>
                        <td>
                            <?php if($demande['statut']==='en_attente'): ?>

                            <button type="submit" class="action-btn btn-accept"
                                id="<?= htmlspecialchars($demande['id_dm']) ?>">Valider</button>


                            <button type="submit" class="action-btn btn-reject"
                                id="<?= htmlspecialchars($demande['id_dm']) ?>">Rejeter</button>
                           
                            <?php else: ?>
                            <button class="action-btn btn-accept disabled-btn" disabled>Valider</button>
                            <button class="action-btn btn-reject disabled-btn" disabled>Rejeter</button>
                            <?php endif; ?>
                            <button class="action-btn btn-detail"
                                id="<?= htmlspecialchars($demande['id_dm']) ?>">Détails</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </main>

    <script>
    // Mapping des statuts
    const STATUS_MAP = {
        en_attente: { badge: "badge-attente", txt: "En attente" },
        en_cours: { badge: "badge-en-cours", txt: "En cours" },
        validee: { badge: "badge-validee", txt: "Validée" },
        traite: { badge: "badge-traite", txt: "Traité" },
        rejete: { badge: "badge-rejetee", txt: "Rejetée" }
    };

    // Données PHP -> JS
    let demandes = <?= json_encode($demandes, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?> || [];
    let filteredDemandes = [...demandes];

    const ITEMS_PER_PAGE = 8;
    let currentPage = 1;

    function parseDate(dateStr) {
        // Ajuste si ton format est "YYYY-MM-DD" ou "YYYY-MM-DD H:i:s"
        return new Date(dateStr);
    }

    // Rendu du tableau (remplit tbody)
    function renderTable() {
        const tableBody = document.getElementById("tableBody");
        tableBody.innerHTML = "";

        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        const end = start + ITEMS_PER_PAGE;
        const pageData = filteredDemandes.slice(start, end);

        pageData.forEach(d => {
            const tr = document.createElement("tr");

            // Échapper les valeurs (simple) -- on suppose que d.* sont sûrs via json_encode mais on nettoie basic
            const id = String(d.id_dm ?? '');
            const demandeur = String(d.demandeur_name ?? '');
            const urgenceHtml = renderUrgence(d.urgence ?? '');
            const typeBesoin = String(d.type_besoin ?? '');
            const dateCreation = String(d.date_creation_dm ?? '');
            const statutHtml = renderStatus(d.statut ?? '');
    let actionsHtml = (parseInt(d.transfere) === 0)      
    ? (d.statut === "en_attente"
        ? `<button type="button" class="action-btn btn-accept" data-id="${id}">Valider</button>
           <button type="button" class="action-btn btn-reject" data-id="${id}">Rejeter</button>`
        : `<button type="button" class="action-btn btn-accept disabled-btn" disabled>Valider</button>
           <button type="button" class="action-btn btn-reject disabled-btn" disabled>Rejeter</button>`)
    : `<span class="text-muted">Transférée à : ${d.recepteur_name ?? ''}</span>`;
    actionsHtml += `<button type="button" class="action-btn btn-detail" data-id="${id}">Détails</button>`;

            tr.innerHTML = `
                <td>${id}</td>
                <td>${demandeur}</td>
                <td>${urgenceHtml}</td>
                <td>${typeBesoin}</td>
                <td>${dateCreation}</td>
                <td>${statutHtml}</td>
                <td>${actionsHtml}
                </td>
            `;

            tableBody.appendChild(tr);
        });

        // Attacher écouteurs aux nouveaux boutons
        attachRowEventListeners();
    }

    function renderUrgence(urgence) {
        const map = {
            faible: { badge: "badge-faible", txt: "Faible" },
            normale: { badge: "badge-normale", txt: "Normale" },
            haute: { badge: "badge-haute", txt: "Haute" },
            critique: { badge: "badge-critique", txt: "Critique" }
        };
        const m = map[urgence] ?? { badge: "badge-default", txt: urgence || "" };
        return `<span class="badge-urgence ${m.badge}">${m.txt}</span>`;
    }

    function renderStatus(statut) {
        const m = STATUS_MAP[statut] ?? { badge: "badge-default", txt: statut ?? "" };
        return `<span class="badge-status ${m.badge}">${m.txt}</span>`;
    }

    // Pagination
    function renderPagination() {
        const totalPages = Math.max(1, Math.ceil(filteredDemandes.length / ITEMS_PER_PAGE));
        const pag = document.getElementById("pagination");
        pag.innerHTML = "";

        const prev = document.createElement("li");
        prev.className = `page-item ${currentPage === 1 ? "disabled" : ""}`;
        prev.innerHTML = `<a class="page-link" href="#">«</a>`;
        prev.onclick = (e) => { e.preventDefault(); goToPage(currentPage - 1); };
        pag.appendChild(prev);

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement("li");
            li.className = `page-item ${i === currentPage ? "active" : ""}`;
            li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            li.onclick = (e) => { e.preventDefault(); goToPage(i); };
            pag.appendChild(li);
        }

        const next = document.createElement("li");
        next.className = `page-item ${currentPage === totalPages ? "disabled" : ""}`;
        next.innerHTML = `<a class="page-link" href="#">»</a>`;
        next.onclick = (e) => { e.preventDefault(); goToPage(currentPage + 1); };
        pag.appendChild(next);
    }

    function goToPage(page) {
        const totalPages = Math.max(1, Math.ceil(filteredDemandes.length / ITEMS_PER_PAGE));
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderTable();
        renderPagination();
    }

    function applyFilters() {
    const type = document.getElementById("filterType").value;
    const statut = document.getElementById("filterStatus").value;
    const period = document.getElementById("filterPeriod").value;
    const search = document.getElementById("filterSearch").value.toLowerCase().trim();

    filteredDemandes = demandes.filter(d => {
        // Filtre type
        if (type && String(d.type_besoin ?? '') !== type) return false;

        // Filtre statut
        if (statut && String(d.statut ?? '') !== statut) return false;

        // 🔍 Filtre recherche par nom
        if (search && !String(d.demandeur_name ?? '').toLowerCase().includes(search)) return false;

        // Filtre période
        if (period) {
            const now = new Date();
            const date = parseDate(d.date_creation_dm ?? '');
            if (isNaN(date)) return false;
            const diff = now - date;

            if (period === "7days" && diff > 7*24*60*60*1000) return false;
            if (period === "1month" && diff > 30*24*60*60*1000) return false;
            if (period === "3months" && diff > 90*24*60*60*1000) return false;
            if (period === "6months" && diff > 180*24*60*60*1000) return false;
        }

        return true;
    });

    currentPage = 1;
    renderTable();
    renderPagination();
}


    // Attacher les événements des boutons dans les lignes (après rendu)
    function attachRowEventListeners() {
        // Valider / Rejeter
        document.querySelectorAll('.btn-accept, .btn-reject').forEach(btn => {
            // retirer anciens handlers s'il y en avait (sécurité)
            btn.onclick = null;
            if (btn.disabled) return;
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const isAccept = this.classList.contains('btn-accept');
                if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) return;
                // redirection (ou tu peux remplacer par AJAX)
                window.location.href = `../../../backend/validateur/update_status.php?id_dm=${encodeURIComponent(id)}&action=${isAccept ? 'validee' : 'rejete'}`;
            });
        });

        // Détails
        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.onclick = null;
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                window.location.href = `detail_demande.php?id=${encodeURIComponent(id)}`;
            });
        });
    }

    // Écouteurs de filtres
    document.getElementById("filterType").addEventListener("change", applyFilters);
    document.getElementById("filterStatus").addEventListener("change", applyFilters);
    document.getElementById("filterPeriod").addEventListener("change", applyFilters);
    document.getElementById("filterSearch").addEventListener("input", applyFilters);


    // Init
    renderTable();
    renderPagination();
    </script>

</body>

</html>