<?php
include __DIR__ . "/../../../backend/demandeur/importer_type_besoins.php";
$typesBesoinObj = new TypeBesoin();
$types_besoin = $typesBesoinObj->getTypesBesoin();
include __DIR__ . "/../../../backend/administrateur/gestion_demandes.php";
$gestionDemandes = new GestionDemandes();
$services = $gestionDemandes->getServices();
$action = $_GET['action'] ?? '';
$idDemande = $_GET['id_dm'] ?? '';
$service = $_GET['service'] ?? '';
$statut = $_GET['statut'] ?? '';

// Traiter l'action AVANT de charger les données
switch ($action) {
    case 'affecter_service':
        if (!empty($service) && !empty($idDemande)) {
            $result = $gestionDemandes->affecterService($idDemande, $service);
            if ($result['success']) {
                header('Location: gestion_demande.php?msg=success');
                exit;
            } else {
                header('Location: gestion_demande.php?msg=error');
                exit;
            }
        }
        break;
       
    case 'modifier_statut':
        if (!empty($statut) && !empty($idDemande)) {
            $result = $gestionDemandes->modifierStatut($idDemande, $statut);
            if ($result['success']) {
                header('Location: gestion_demande.php?msg=success');
                exit;
            } else {
                header('Location: gestion_demande.php?msg=error');
                exit;
            }
        }
        break;
}

// Charger les demandes APRÈS le traitement
$demandes = $gestionDemandes->importerDemandes();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Espace Administration - Demandes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/administrateur assets/gestion_demandes.css" rel="stylesheet">

 
</head>

<body>
    <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-<?= $_GET['msg'] === 'success' ? 'success' : 'danger' ?>" style="position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;">
        <?= $_GET['msg'] === 'success' ? 'Opération effectuée avec succès' : 'Une erreur est survenue' ?>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.alert').style.display = 'none';
        }, 3000);
    </script>
    <?php endif; ?>

    <?php include "header_menu.php" ?>
    
    <main class="main-content">
        <section class="content">
            <div class="header-row">
                <div>
                    <a href="dashboard.php" class="retour_dashboard"><i class="bi bi-arrow-left"></i> Retour à la page d'acceuil</a>
                    <div class="page-title">Affectation et Suivi des Demandes</div>
                    <div class="page-sub">Gérer et suivre les demandes validées</div>
                </div>
            </div>

            <!-- Stats -->
        <!-- Stats -->
<div class="stats-container">
    <div class="stat-card blue">
        <i class="bi bi-file-earmark-text"></i>
        <div class="stat-title">Total des demandes</div>
        <div class="stat-value"></div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon"><i class="bi bi-check-circle-fill text-success"></i></div>
        <div class="stat-title">Demandes Traitées</div>
        <div class="stat-value"></div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-title">Demandes En cours</div>
        <div class="stat-value"></div>
    </div>
</div>


            <!-- Filtres -->
            <div class="filters-card">
                <h5><i class="bi bi-search"></i> Filtres de recherche</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Type de besoin</label>
                        <select class="form-select filter-select" id="filterType" name="type_besoin">
                            <option value="">Sélectionner un type</option>
                            <?php foreach ($types_besoin as $type): ?>
                            <option value="<?= htmlspecialchars($type['nom_tb']) ?>">
                                <?= htmlspecialchars($type['nom_tb']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Statut</label>
                        <select class="form-select filter-select" id="filterStatus">
                            <option value="">Tous les statuts</option>
                            <option value="traite">Traitée</option>
                            <option value="en_cours">En cours</option>
                            <option value="validee">Validée</option>
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
                        <label class="form-label fw-bold">Urgence</label>
                        <select class="form-select filter-select" id="filterUrgence">
                            <option value="">Tous</option>
                            <option value="faible">Faible</option>
                            <option value="normale">Normale</option>
                            <option value="haute">Haute</option>
                            <option value="critique">Critique</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Service</label>
                        <select class="form-select filter-select" id="filterService">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['id_service'] ?>">
                                    <?= htmlspecialchars($service['nom_service']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Rechercher un demandeur</label>
                        <input type="text" id="filterSearch" class="form-control" placeholder="Nom complet...">
                    </div>
                </div>
            </div>
            
            <!-- Table card -->
            <div class="table-card">
                <div class="table-title">Liste des demandes</div>
                <div class="table-header">
                    <div>ID Demande</div>
                    <div>Date de création</div>
                    <div>Demandeur</div>
                    <div>Type de besoin</div>
                    <div>Service assigné</div>
                    <div>Statut</div>
                    <div>Urgence</div>
                    <div>Actions</div>
                </div>

                <div id="tableBody">
                    <!-- rows injected by JS -->
                </div>

                <div class="pagination" id="pagination">
                    <nav aria-label="Pagination des demandes">
                        <ul class="pagination pagination-beex" id="paginationList"></ul>
                    </nav>
                </div>
            </div>
        </section>
    </main>

    <!-- Modals -->
    <div class="modal-custom" id="modalAffecter" aria-hidden="true">
        <div class="modal-panel" role="dialog">
            <div class="modal-header">
                <h5><i class="bi bi-arrow-right-square-fill"></i> Affecter un service</h5>
                <button class="close-x" onclick="closeModal('modalAffecter')" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-building"></i> Choisir un service</label>
                    <select id="selectService" class="form-select">
                        <option value="">-- Sélectionner un service --</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['id_service'] ?>">
                                <?= htmlspecialchars($service['nom_service']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" onclick="closeModal('modalAffecter')"><i class="bi bi-x-circle"></i> Annuler</button>
                <button class="btn btn-primary" onclick="affecterService()"><i class="bi bi-check-circle"></i> Affecter</button>
            </div>
        </div>
    </div>

    <div class="modal-custom" id="modalStatut" aria-hidden="true">
        <div class="modal-panel" role="dialog">
            <div class="modal-header">
                <h5><i class="bi bi-pencil-square"></i> Modifier le statut</h5>
                <button class="close-x" onclick="closeModal('modalStatut')" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-info-circle"></i> Nouveau statut</label>
                    <select id="selectStatut" class="form-select">
                        <option value="traite" selected>Traitée</option>
                    </select>
                </div>
               
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" onclick="closeModal('modalStatut')"><i class="bi bi-x-circle"></i> Annuler</button>
                <button class="btn btn-primary" onclick="modifierStatut()"><i class="bi bi-check-circle"></i> Mettre à jour</button>
            </div>
        </div>
    </div>

    <div class="modal-custom" id="modalDetails" aria-hidden="true">
        <div class="modal-panel" role="dialog">
            <button class="close-x" onclick="closeModal('modalDetails')" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
            <div class="modal-body" id="detailsContent" style="padding:0;"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
let allData = <?= json_encode($demandes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

const itemsPerPage = 4;
let currentPage = 1;
let editingId = null;

// ========== FONCTION CENTRALISÉE POUR LES FILTRES ==========
function getFilteredData() {
    const filterType = $('#filterType').val();
    const filterStatut = $('#filterStatus').val();
    const filterPeriod = $('#filterPeriod').val();
    const filterUrgence = $('#filterUrgence').val();
    const filterService = $('#filterService').val();
    const filterSearch = $('#filterSearch').val()?.toLowerCase() || '';

    return allData.filter(d => {
        if (filterType && d.type_besoin !== filterType) return false;
        if (filterStatut && d.statut !== filterStatut) return false;
        if (filterUrgence && d.urgence !== filterUrgence) return false;
        if (filterService && d.service_id != filterService) return false;
        if (filterSearch && !d.demandeur_nom.toLowerCase().includes(filterSearch)) return false;
        return true;
    });
}

// ========== BADGE FUNCTIONS ==========
function badgeStatut(statut) {
    if (!statut) return '';
    statut = statut.toLowerCase();
    if (statut.includes('validee')) return '<span class="badge-pill badge-validee">Validée</span>';
    if (statut.includes('en_cours')) return '<span class="badge-pill badge-en-cours">En cours</span>';
    if (statut.includes('traite')) return '<span class="badge-pill badge-traite">Traitée</span>';
    return `<span class="badge-pill">${statut}</span>`;
}

function badgeUrgence(urgence) {
    if (!urgence) return '';
    urgence = urgence.toLowerCase();
    switch (urgence) {
        case 'faible':
            return '<span class="badge-urgence badge-faible">Faible</span>';
        case 'normale':
            return '<span class="badge-urgence badge-normale">Normale</span>';
        case 'haute':
            return '<span class="badge-urgence badge-haute">Haute</span>';
        case 'critique':
            return '<span class="badge-urgence badge-critique">Critique</span>';
        default:
            return `<span class="badge-urgence">${urgence}</span>`;
    }
}

// ========== STATISTIQUES ==========
function renderStats() {
    const filtered = getFilteredData();
    const total = filtered.length;
    const encours = filtered.filter(d => d.statut === 'en_cours').length;
    const traitee = filtered.filter(d => d.statut === 'traite').length;

    $('.stat-card.blue .stat-value').text(total);
    $('.stat-card.orange .stat-value').text(encours);
    $('.stat-card.green .stat-value').text(traitee);
}

// ========== VÉRIFICATION DE L'ÉTAT ==========
function canAffecter(statut) {
    return statut !== 'en_cours' && statut !== 'traite';
}

function canModifier(statut) {
    return statut !== 'traite';
}

// ========== TABLEAU ==========
function renderTable() {
    const filtered = getFilteredData();
    const maxPage = Math.max(1, Math.ceil(filtered.length / itemsPerPage));
   
    if (currentPage > maxPage) currentPage = 1;

    const start = (currentPage - 1) * itemsPerPage;
    const pageData = filtered.slice(start, start + itemsPerPage);

    let html = pageData.map(d => {
        const canAffect = canAffecter(d.statut);
        const canMod = canModifier(d.statut);
        
        return `
        <div class="table-row" data-id="${d.id}">
            <div class="cell"><strong>${d.id}</strong></div>
            <div class="cell">${d.date_creation}</div>
            <div class="cell">${d.demandeur_nom}</div>
            <div class="cell">${d.type_besoin}</div>
            <div class="cell">${d.service_nom || 'Non affecté'}</div>
            <div class="cell">${badgeStatut(d.statut)}</div>
            <div class="cell">${badgeUrgence(d.urgence)}</div>
            <div class="cell actions">
                <button class="btn-action" onclick="openAffecterModal('${d.id}')" ${!canAffect ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}>
                    <i class="bi bi-arrow-right-square"></i> Affecter
                </button>
                <button class="btn-action secondary" onclick="openStatutModal('${d.id}')" ${!canMod ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}>
                    <i class="bi bi-pencil"></i> Modifier
                </button>
                <button class="btn-action link" onclick="viewDetails('${d.id}')">
                    <i class="bi bi-eye"></i> Détails
                </button>
            </div>
        </div>
    `}).join('');

    if (!html) html = '<div style="padding:20px;text-align:center;color:var(--muted)">Aucune demande trouvée</div>';
    $('#tableBody').html(html);
   
    renderPagination();
}

// ========== PAGINATION ==========
function renderPagination() {
    const filtered = getFilteredData();
    const totalPages = Math.max(1, Math.ceil(filtered.length / itemsPerPage));
    const $paginationList = $('#paginationList');
    $paginationList.empty();

    if (currentPage === 1) {
        $paginationList.append(`
            <li class="page-item disabled">
                <span class="page-link">«</span>
            </li>
        `);
    } else {
        $paginationList.append(`
            <li class="page-item">
                <a class="page-link" href="#" data-page="${currentPage - 1}">«</a>
            </li>
        `);
    }

    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            $paginationList.append(`
                <li class="page-item active" aria-current="page">
                    <span class="page-link">${i}</span>
                </li>
            `);
        } else {
            $paginationList.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }
    }

    if (currentPage === totalPages) {
        $paginationList.append(`
            <li class="page-item disabled">
                <span class="page-link">»</span>
            </li>
        `);
    } else {
        $paginationList.append(`
            <li class="page-item">
                <a class="page-link" href="#" data-page="${currentPage + 1}">»</a>
            </li>
        `);
    }

    $paginationList.find('a.page-link').on('click', (e) => {
        e.preventDefault();
        const pageNum = parseInt($(e.target).data('page'));
        goToPage(pageNum);
    });
}

function goToPage(page) {
    const filtered = getFilteredData();
    const totalPages = Math.max(1, Math.ceil(filtered.length / itemsPerPage));
   
    if (page < 1 || page > totalPages) return;
   
    currentPage = page;
    renderTable();
}

// ========== MODALS ==========
function openAffecterModal(id) {
    console.log('openAffecterModal appelée pour ID:', id);
    const d = allData.find(x => Number(x.id) === Number(id)); // convertir id en nombre

    console.log('Demande trouvée:', d);
    
    if (!d) {
        console.error('Demande non trouvée');
        return;
    }
    
    if (!canAffecter(d.statut)) {
        console.warn('Impossible d\'affecter - statut:', d.statut);
        alert('Cette demande ne peut plus être affectée (statut: ' + d.statut + ')');
        return;
    }
    
    editingId = id;
    $('#selectService').val('');
    console.log('Ouverture de la modal modalAffecter');
    openModal('modalAffecter');
}

function openStatutModal(id) {
    console.log('openStatutModal appelée pour ID:', id);
    const d = allData.find(x => Number(x.id) === Number(id)); // convertir id en nombre

    console.log('Demande trouvée:', d);
    
    if (!d) {
        console.error('Demande non trouvée');
        return;
    }
    
    if (!canModifier(d.statut)) {
        console.warn('Impossible de modifier - statut:', d.statut);
        alert('Cette demande ne peut plus être modifiée (statut: ' + d.statut + ')');
        return;
    }
    
    editingId = id;
    $('#selectStatut').val('traite');
    $('#commentaire').val('');
    console.log('Ouverture de la modal modalStatut');
    openModal('modalStatut');
}

function viewDetails(id) {
    console.log('viewDetails appelée pour ID:', id);
    const d = allData.find(x => Number(x.id) === Number(id)); // convertir id en nombre

    console.log('Demande trouvée:', d);
    
    if (!d) {
        console.error('Demande non trouvée');
        return;
    }
    
    const html = `
        <div class="details-header">
            <h4><i class="bi bi-file-text"></i> Demande #${d.id}</h4>
            <div class="subtitle"><i class="bi bi-calendar3"></i> Créée le ${d.date_creation}</div>
        </div>
        
        <div class="details-content">
            <div class="detail-section">
                <h5><i class="bi bi-info-circle-fill"></i> Informations Générales</h5>
                <div class="detail-row">
                    <div class="detail-label"><i class="bi bi-tag"></i> Type de besoin :</div>
                    <div class="detail-value">${d.type_besoin}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="bi bi-flag"></i> Statut :</div>
                    <div class="detail-value">${badgeStatut(d.statut)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="bi bi-exclamation-triangle"></i> Urgence :</div>
                    <div class="detail-value">${badgeUrgence(d.urgence)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="bi bi-building"></i> Service assigné :</div>
                    <div class="detail-value">${d.service_nom || '<em style="color:#999;">Non affecté</em>'}</div>
                </div>
            </div>
            
            <div class="detail-section">
                <h5><i class="bi bi-card-text"></i> Description</h5>
                <div style="padding:12px 0;line-height:1.7;color:#374151;">
                    ${d.description || '<em style="color:#999;">Aucune description fournie</em>'}
                </div>
            </div>
            
            <div class="detail-section">
                <h5><i class="bi bi-people-fill"></i> Intervenants</h5>
                <div class="detail-row">
                    <div class="detail-label"><i class="bi bi-person-circle"></i> Demandeur :</div>
                    <div class="detail-value">
                        <strong>${d.demandeur_nom}</strong>
                        ${d.demandeur_email ? `<br><small style="color:#6b7280;"><i class="bi bi-envelope"></i> ${d.demandeur_email}</small>` : ''}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="bi bi-person-check-fill"></i> Validateur :</div>
                    <div class="detail-value">
                        <strong>${d.validateur_nom || '<em style="color:#999;">Non disponible</em>'}</strong>
                        ${d.validateur_email ? `<br><small style="color:#6b7280;"><i class="bi bi-envelope"></i> ${d.validateur_email}</small>` : ''}
                    </div>
                </div>
            </div>
            
            
        </div>
    `;
    
    $('#detailsContent').html(html);
    console.log('Ouverture de la modal modalDetails');
    openModal('modalDetails');
}

function voirDetailsDemandeur(id) {
    if (!id) {
        alert('Identifiant du demandeur non disponible');
        return;
    }
    // Redirection vers la page de détails du demandeur
    window.location.href = `details_demandeur.php?id=${id}`;
}

function voirDetailsValidateur(id) {
    if (!id) {
        alert('Identifiant du validateur non disponible');
        return;
    }
    // Redirection vers la page de détails du validateur
    window.location.href = `details_validateur.php?id=${id}`;
}

function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) {
        console.error('Modal not found:', id);
        return;
    }
    
    // Empêcher le scroll du body
    document.body.style.overflow = 'hidden';
    
    // Ajouter la classe show et forcer l'affichage
    modal.classList.add('show');
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
    
    // Fermer en cliquant sur le fond (mais pas sur le panel)
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeModal(id);
        }
    };
    
    // Empêcher la propagation des clics sur le panel
    const panel = modal.querySelector('.modal-panel');
    if (panel) {
        panel.onclick = function(e) {
            e.stopPropagation();
        };
    }
    
    console.log('Modal opened:', id);
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (!modal) {
        console.error('Modal not found:', id);
        return;
    }
    
    // Retirer la classe show et cacher la modal
    modal.classList.remove('show');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    
    // Réactiver le scroll du body
    document.body.style.overflow = '';
    
    // Nettoyer les event listeners
    modal.onclick = null;
    const panel = modal.querySelector('.modal-panel');
    if (panel) {
        panel.onclick = null;
    }
    
    console.log('Modal closed:', id);
}

// ========== ACTIONS ==========
function affecterService() {
    const svc = $('#selectService').val();
    if (!svc) {
        alert('Veuillez sélectionner un service');
        return;
    }
    if (!confirm('Êtes-vous sûr de vouloir affecter cette demande ?')) return;

    window.location.href = `gestion_demande.php?action=affecter_service&id_dm=${editingId}&service=${encodeURIComponent(svc)}`;
}

function modifierStatut() {
    const stat = $('#selectStatut').val();
    if (!stat) {
        alert('Veuillez sélectionner un statut');
        return;
    }
    if (!confirm('Êtes-vous sûr de vouloir modifier le statut ?')) return;

    window.location.href = `gestion_demande.php?action=modifier_statut&id_dm=${editingId}&statut=${encodeURIComponent(stat)}`;
}

// ========== INITIALISATION ==========
$(document).ready(function() {
    renderStats();
    renderTable();

    $('#filterType, #filterStatus, #filterPeriod, #filterUrgence, #filterService, #filterSearch').on('change keyup', function() {
        currentPage = 1;
        renderStats();
        renderTable();
    });
});
    </script>
</body>

</html>