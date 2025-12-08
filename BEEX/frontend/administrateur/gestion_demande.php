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



<!-- App layout -->
<div class="app container-fluid">

    <!-- Content -->
    <section class="content">
        <div class="header-row">
            <div>
                <div class="page-title">Affectation et Suivi des Demandes</div>
                <div class="page-sub">Gérer et suivre les demandes validées</div>
            </div>

            <div style="display:flex;align-items:center;gap:12px">
                <div style="background:white;padding:8px 12px;border-radius:10px;font-weight:700">Bienvenue, Noura</div>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card green">
                <div class="title">Demandes validées</div>
                <div class="value" id="statValide">0</div>
            </div>
            <div class="stat-card">
                <div class="title">Demandes en cours</div>
                <div class="value" id="statEnCours" style="color:#0b63f0">0</div>
            </div>
            <div class="stat-card red">
                <div class="title">Demandes rejetées</div>
                <div class="value" id="statRejete">0</div>
            </div>
        </div>

        <!-- Filters -->
        <div style="margin-bottom:18px;display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap">
            <div>
                <label class="form-label" style="font-weight:700">Service</label>
                <select id="filterService" class="form-select">
                    <option value="">Tous les services</option>
                    <option>Achat</option><option>DSI</option><option>RH</option><option>Finance</option><option>Marketing</option>
                    <option>Logistique</option><option>Support</option><option>IT</option><option>R&D</option><option>Admin</option>
                </select>
            </div>
            <div>
                <label class="form-label" style="font-weight:700">Statut</label>
                <select id="filterStatut" class="form-select">
                    <option value="">Tous</option><option>Validée</option><option>En cours</option><option>Traitée</option><option>Rejetée</option>
                </select>
            </div>
            <div>
                <label class="form-label" style="font-weight:700">Urgence</label>
                <select id="filterUrgence" class="form-select">
                    <option value="">Toutes</option><option>Urgente</option><option>Moyenne</option><option>Faible</option>
                </select>
            </div>

            <div style="margin-left:auto">
                <button class="btn" style="background:#0b63f0;color:#fff;font-weight:800" onclick="refreshTable()"><i class="bi bi-arrow-clockwise"></i> Rafraîchir</button>
            </div>
        </div>

        <!-- Table card -->
        <div class="table-card">
            <div class="table-title">Liste des demandes</div>
            <div class="table-header">
                <div>ID Demande</div><div>Date</div><div>Demandeur</div><div>Type de besoin</div><div>Service assigné</div><div>Statut</div><div>Urgence</div><div>Actions</div>
            </div>

            <div id="tableBody">
                <!-- rows injected by JS -->
            </div>

            <div class="pagination" id="pagination">
                <button onclick="changePage(-1)" id="prevBtn"><i class="bi bi-chevron-left"></i></button>
                <button class="pageBtn active" data-page="1" onclick="goToPage(1)">1</button>
                <button class="pageBtn" data-page="2" onclick="goToPage(2)">2</button>
                <button onclick="changePage(1)" id="nextBtn"><i class="bi bi-chevron-right"></i></button>
            </div>
            <div style="text-align:center;color:#0b63f0;font-weight:700"><a href="#" onclick="return false">Voir plus</a></div>
        </div>

    </section>
</div>

<!-- Floating button -->
<button class="fab" onclick="alert('Nouvelle demande')"><i class="bi bi-plus-circle"></i> Nouvelle demande</button>

<!-- Modals -->
<div class="modal-custom" id="modalAffecter" aria-hidden="true">
    <div class="modal-panel" role="dialog">
        <div class="modal-header">
            <h5>Affecter un service</h5>
            <button class="close-x" onclick="closeModal('modalAffecter')"><i class="bi bi-x"></i></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Choisir un service</label>
                <select id="selectService" class="form-select">
                    <option value="">-- Sélectionner --</option>
                    <option>Achat</option><option>DSI</option><option>RH</option><option>Finance</option><option>Marketing</option>
                    <option>Logistique</option><option>Support</option><option>IT</option><option>R&D</option><option>Admin</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-light" onclick="closeModal('modalAffecter')">Annuler</button>
            <button class="btn btn-primary" onclick="affecterService()">Affecter</button>
        </div>
    </div>
</div>

<div class="modal-custom" id="modalStatut" aria-hidden="true">
    <div class="modal-panel" role="dialog">
        <div class="modal-header">
            <h5>Modifier le statut</h5>
            <button class="close-x" onclick="closeModal('modalStatut')"><i class="bi bi-x"></i></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Nouveau statut</label>
                <select id="selectStatut" class="form-select">
                    <option value="">-- Sélectionner --</option>
                    <option>Validée</option><option>En cours</option><option>Traitée</option><option>Rejetée</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Commentaire (optionnel)</label>
                <textarea id="commentaire" class="form-control" rows="3" placeholder="Ajouter un commentaire..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-light" onclick="closeModal('modalStatut')">Annuler</button>
            <button class="btn btn-primary" onclick="modifierStatut()">Mettre à jour</button>
        </div>
    </div>
</div>

<div class="modal-custom" id="modalDetails" aria-hidden="true">
    <div class="modal-panel" role="dialog">
        <div class="modal-header">
            <h5>Détails de la demande</h5>
            <button class="close-x" onclick="closeModal('modalDetails')"><i class="bi bi-x"></i></button>
        </div>
        <div class="modal-body" id="detailsContent"></div>
        <div class="modal-footer">
            <button class="btn btn-light" onclick="closeModal('modalDetails')">Fermer</button>
        </div>
    </div>
</div>

<div class="modal-custom" id="modalHistorique" aria-hidden="true">
    <div class="modal-panel" role="dialog">
        <div class="modal-header">
            <h5>Historique de la demande</h5>
            <button class="close-x" onclick="closeModal('modalHistorique')"><i class="bi bi-x"></i></button>
        </div>
        <div class="modal-body" id="historiqueContent"></div>
        <div class="modal-footer">
            <button class="btn btn-light" onclick="closeModal('modalHistorique')">Fermer</button>
        </div>
    </div>
</div>

<div class="toast-success" id="toast">Action effectuée</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Data: 8 demandes
    let allData = [
        { id: 'DEM-001', date: '25/11/2025', demandeur: 'Soufiane Ahmed', type: 'Achat matériel', service: 'Non affecté', statut: 'Validée', urgence: 'Urgente', history: [] },
        { id: 'DEM-002', date: '24/11/2025', demandeur: 'Imane Fatima', type: 'Logiciel', service: 'DSI', statut: 'En cours', urgence: 'Moyenne', history: [] },
        { id: 'DEM-003', date: '23/11/2025', demandeur: 'Ahmed Ali', type: 'Formation', service: 'RH', statut: 'Traitée', urgence: 'Faible', history: [] },
        { id: 'DEM-004', date: '22/11/2025', demandeur: 'Karim Salim', type: 'Fournitures', service: 'Admin', statut: 'En cours', urgence: 'Faible', history: [] },
        { id: 'DEM-005', date: '21/11/2025', demandeur: 'Rawan Ali', type: 'Service externe', service: 'Finance', statut: 'Traitée', urgence: 'Urgente', history: [] },
        { id: 'DEM-006', date: '20/11/2025', demandeur: 'Fatima Douaibi', type: 'Logiciel', service: 'Non affecté', statut: 'Validée', urgence: 'Urgente', history: [] },
        { id: 'DEM-007', date: '19/11/2025', demandeur: 'Nasrin Bouamira', type: 'Formation', service: 'RH', statut: 'Validée', urgence: 'Moyenne', history: [] },
        { id: 'DEM-008', date: '18/11/2025', demandeur: 'Karim Salim', type: 'Fournitures', service: 'Admin', statut: 'En cours', urgence: 'Faible', history: [] }
    ];

    const itemsPerPage = 4;
    let currentPage = 1;
    let editingId = null; // for modals

    function badgeStatut(s){
        if(!s) return '';
        s = s.toLowerCase();
        if(s.includes('valid')) return '<span class="badge-pill badge-valid">Validée</span>';
        if(s.includes('en cours')) return '<span class="badge-pill badge-encours">En cours</span>';
        if(s.includes('trait')) return '<span class="badge-pill badge-traitee">Traitée</span>';
        if(s.includes('rejet')) return '<span class="badge-pill badge-rejet">Rejetée</span>';
        return `<span class="badge-pill">${s}</span>`;
    }
    function badgeUrgence(u){
        if(!u) return '';
        u = u.toLowerCase();
        if(u.includes('urg')) return '<span class="badge-pill badge-urgente">Urgente</span>';
        if(u.includes('moy')) return '<span class="badge-pill badge-moyenne">Moyenne</span>';
        if(u.includes('faib')) return '<span class="badge-pill badge-faible">Faible</span>';
        return `<span class="badge-pill">${u}</span>`;
    }

    function renderStats(){
        const valide = allData.filter(d => d.statut === 'Validée').length;
        const encours = allData.filter(d => d.statut === 'En cours').length;
        const rejete = allData.filter(d => d.statut === 'Rejetée').length;
        $('#statValide').text(valide);
        $('#statEnCours').text(encours);
        $('#statRejete').text(rejete);
    }

    function renderTable(){
        const filterService = $('#filterService').val();
        const filterStatut = $('#filterStatut').val();
        const filterUrgence = $('#filterUrgence').val();

        let filtered = allData.filter(d=>{
            if(filterService && d.service !== filterService) return false;
            if(filterStatut && d.statut !== filterStatut) return false;
            if(filterUrgence && d.urgence !== filterUrgence) return false;
            return true;
        });

        const maxPage = Math.max(1, Math.ceil(filtered.length / itemsPerPage));
        if(currentPage > maxPage) currentPage = maxPage;

        const start = (currentPage-1)*itemsPerPage;
        const pageData = filtered.slice(start, start + itemsPerPage);

        let html = pageData.map(d => `
            <div class="table-row" data-id="${d.id}">
                <div class="cell"><strong>${d.id}</strong></div>
                <div class="cell">${d.date}</div>
                <div class="cell">${d.demandeur}</div>
                <div class="cell">${d.type}</div>
                <div class="cell">${d.service}</div>
                <div class="cell">${badgeStatut(d.statut)}</div>
                <div class="cell">${badgeUrgence(d.urgence)}</div>
                <div class="cell actions">
                    <div class="action-buttons">
                        <button class="btn-action" onclick="openAffecterModal('${d.id}')"><i class="bi bi-arrow-right-square"></i> Affecter</button>
                        <button class="btn-action secondary" onclick="openStatutModal('${d.id}')"><i class="bi bi-pencil"></i> Modifier</button>
                        <button class="btn-action link" onclick="viewDetails('${d.id}')"><i class="bi bi-eye"></i> Détails</button>
                        <button class="btn-action link" onclick="viewHistorique('${d.id}')"><i class="bi bi-clock-history"></i> Historique</button>
                    </div>
                </div>
            </div>
        `).join('');
        if(!html) html = '<div style="padding:20px;text-align:center;color:var(--muted)">Aucune demande trouvée</div>';
        $('#tableBody').html(html);

        // update pagination buttons active state
        $('.pageBtn').removeClass('active');
        $(`.pageBtn[data-page="${currentPage}"]`).addClass('active');

        // enable/disable nav
        $('#prevBtn').prop('disabled', currentPage === 1);
        $('#nextBtn').prop('disabled', currentPage === Math.ceil(filtered.length / itemsPerPage) || filtered.length === 0);

        renderStats();
    }

    function goToPage(n){
        currentPage = n;
        renderTable();
    }
    function changePage(dir){
        currentPage += dir;
        if(currentPage < 1) currentPage = 1;
        renderTable();
    }

    // Modal helpers
    function openAffecterModal(id){
        editingId = id;
        $('#selectService').val('');
        openModal('modalAffecter');
    }
    function openStatutModal(id){
        editingId = id;
        $('#selectStatut').val('');
        $('#commentaire').val('');
        openModal('modalStatut');
    }
    function viewDetails(id){
        const d = allData.find(x=>x.id===id);
        if(!d) return;
        const html = `
            <div style="display:flex;gap:18px;flex-direction:column">
                <div><strong>ID:</strong> ${d.id}</div>
                <div><strong>Demandeur:</strong> ${d.demandeur}</div>
                <div><strong>Date:</strong> ${d.date}</div>
                <div><strong>Type:</strong> ${d.type}</div>
                <div><strong>Service assigné:</strong> ${d.service}</div>
                <div><strong>Statut:</strong> ${d.statut}</div>
                <div><strong>Urgence:</strong> ${d.urgence}</div>
            </div>
        `;
        $('#detailsContent').html(html);
        openModal('modalDetails');
    }
    function viewHistorique(id){
        const d = allData.find(x=>x.id===id);
        if(!d) return;
        const hist = d.history && d.history.length ? d.history.slice().reverse() : [{date:'--',action:'Aucune action',user:'-' }];
        const html = hist.map(h=>`<div style="padding:10px;border-left:3px solid #0b63f0;background:#f8fbff;margin-bottom:8px;border-radius:6px"><div style="font-size:13px;color:var(--muted)">${h.date}</div><div style="font-weight:700">${h.action}</div><div style="font-size:13px;color:var(--muted)">Par: ${h.user||'Système'}</div></div>`).join('');
        $('#historiqueContent').html(html);
        openModal('modalHistorique');
    }

    function openModal(id){
        $(`#${id}`).addClass('show').attr('aria-hidden','false');
        // add click overlay to close
        $(`#${id}`).off('click.modal').on('click.modal', function(e){
            if(e.target === this) closeModal(id);
        });
    }
    function closeModal(id){
        $(`#${id}`).removeClass('show').attr('aria-hidden','true');
    }

    // Actions
    function affecterService(){
        const svc = $('#selectService').val();
        if(!svc){ alert('Veuillez sélectionner un service'); return; }
        const item = allData.find(d=>d.id === editingId);
        if(item){
            item.service = svc;
            item.history = item.history || [];
            item.history.push({date: new Date().toLocaleString(), action: `Affecté au service: ${svc}`, user: 'Administrateur'});
            renderToast(`Demande ${editingId} affectée à ${svc}`);
        }
        closeModal('modalAffecter');
        renderTable();
    }

    function modifierStatut(){
        const stat = $('#selectStatut').val();
        const comm = $('#commentaire').val();
        if(!stat){ alert('Veuillez sélectionner un statut'); return; }
        const item = allData.find(d=>d.id === editingId);
        if(item){
            item.statut = stat;
            item.history = item.history || [];
            item.history.push({date: new Date().toLocaleString(), action: `Statut changé en ${stat}${comm?(' - '+comm):''}`, user:'Administrateur'});
            renderToast(`Statut de ${editingId} mis à jour: ${stat}`);
        }
        closeModal('modalStatut');
        renderTable();
    }

    function renderToast(msg){
        $('#toast').text(msg).addClass('show');
        setTimeout(()=>$('#toast').removeClass('show'), 2600);
    }

    function refreshTable(){
        renderTable();
        renderToast('Tableau actualisé');
    }

    // sidebar toggle (for small screens)
    $('#toggleSidebar').on('click', function(){
        $('#sidebarPanel').toggle();
    });

    // init
    $(document).ready(function(){
        renderTable();

        // filters change
        $('#filterService,#filterStatut,#filterUrgence').on('change', function(){
            currentPage = 1;
            renderTable();
        });

        // logout btn
        $('#logoutBtn').on('click', function(){
            alert('Déconnexion...');
        });
    });

</script>

</body>
</html>
