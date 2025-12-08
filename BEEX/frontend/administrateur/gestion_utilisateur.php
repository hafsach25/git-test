<?php
include __DIR__ . "/../../../backend/administrateur/gestion_utilisateurs.php";
$gestionUtilisateurs = new GestionUtilisateurs();

$action = $_GET['action'] ?? '';
$idUser = $_GET['id_user'] ?? '';
$typeUser = $_GET['type_user'] ?? '';

// Traiter l'action AVANT de charger les données
switch ($action) {
    case 'get_user':
        if (!empty($idUser) && !empty($typeUser)) {
            header('Content-Type: application/json');
            $user = $gestionUtilisateurs->getUserById($idUser, $typeUser);
            echo json_encode($user);
            exit;
        }
        break;
    
    case 'supprimer':
        if (!empty($idUser) && !empty($typeUser)) {
            $result = $gestionUtilisateurs->supprimerUtilisateur($idUser, $typeUser);
            if ($result['success']) {
                header('Location: gestion_utilisateur.php?msg=success');
                exit;
            } else {
                header('Location: gestion_utilisateur.php?msg=error&error_msg=' . urlencode($result['message']));
                exit;
            }
        }
        break;
}

// Charger les utilisateurs APRÈS le traitement
$utilisateurs = $gestionUtilisateurs->getAllUsers();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Espace Administration - Gestion des Utilisateurs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/administrateur assets/gestion_utilisateurs.css" rel="stylesheet">
</head>

<body>
    <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-<?= $_GET['msg'] === 'success' ? 'success' : 'danger' ?>" style="position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;">
        <?= $_GET['msg'] === 'success' ? 'Opération effectuée avec succès' : ($_GET['error_msg'] ?? 'Une erreur est survenue') ?>
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
                    <div class="page-title">Gestion des Utilisateurs</div>
                    <div class="page-sub">Créer, modifier et gérer les utilisateurs</div>
                </div>
            </div>

            <!-- Barre de recherche -->
            <div class="search-container">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un utilisateur...">
                </div>
            </div>

            <!-- Table card -->
            <div class="table-card">
                <div class="table-header">
                    <div>ID</div>
                    <div>NOM PRÉNOM</div>
                    <div>EMAIL</div>
                    <div>TYPE</div>
                    <div>DÉPARTEMENT/CHEF</div>
                    <div>ACTIONS</div>
                </div>

                <div id="tableBody">
                    <!-- rows injected by JS -->
                </div>

                <div class="pagination" id="pagination">
                    <nav aria-label="Pagination des utilisateurs">
                        <ul class="pagination pagination-beex" id="paginationList"></ul>
                    </nav>
                </div>
            </div>
        </section>
    </main>

    <!-- Bouton flottant Nouvel utilisateur -->
    <a href="creation_utilisateur.php" class="fab-button">
        <i class="bi bi-plus-lg"></i>
        <span>Nouvel utilisateur</span>
    </a>


    <script src="../../jquery/jquery-3.7.1.min.js"></script>
    <script>
        let allData = <?= json_encode($utilisateurs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        const itemsPerPage = 8;
        let currentPage = 1;

        // Fonction pour afficher le badge de type
        function badgeType(type) {
            if (type === 'Chef') {
                return '<span class="badge-pill badge-chef">Chef</span>';
            } else {
                return '<span class="badge-pill badge-demandeur">Demandeur</span>';
            }
        }

        // Fonction pour afficher le département/chef
        function getDepartementChef(user) {
            if (user.type_user === 'Chef') {
                return user.departement ? `<span class="departement-text">${user.departement}</span>` : '<em style="color:#999;">Non assigné</em>';
            } else {
                if (user.chef_nom) {
                    return `<span class="chef-text">Chef: ${user.chef_nom}</span>`;
                } else {
                    return '<em style="color:#999;">Non assigné</em>';
                }
            }
        }

        // Fonction pour filtrer les données
        function getFilteredData() {
            const search = $('#searchInput').val()?.toLowerCase() || '';
            return allData.filter(u => {
                if (search && !u.nom_prenom.toLowerCase().includes(search) && 
                    !u.email.toLowerCase().includes(search) &&
                    !u.id_affichage.toLowerCase().includes(search)) {
                    return false;
                }
                return true;
            });
        }

        // Fonction pour rendre le tableau
        function renderTable() {
            const filtered = getFilteredData();
            const maxPage = Math.max(1, Math.ceil(filtered.length / itemsPerPage));
            
            if (currentPage > maxPage) currentPage = 1;

            const start = (currentPage - 1) * itemsPerPage;
            const pageData = filtered.slice(start, start + itemsPerPage);

            let html = pageData.map(u => {
                return `
                <div class="table-row" data-id="${u.id}" data-type="${u.type_user}">
                    <div class="cell"><strong>${u.id_affichage}</strong></div>
                    <div class="cell">${u.nom_prenom}</div>
                    <div class="cell">${u.email}</div>
                    <div class="cell">${badgeType(u.type_user)}</div>
                    <div class="cell">${getDepartementChef(u)}</div>
                    <div class="cell actions">
                        <button class="btn-action btn-view" onclick="viewDetails('${u.id}', '${u.type_user}')" title="Voir détails">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" onclick="openModifierModal('${u.id}', '${u.type_user}')" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="supprimerUtilisateur('${u.id}', '${u.type_user}')" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `}).join('');

            if (!html) {
                html = '<div style="padding:20px;text-align:center;color:var(--muted)">Aucun utilisateur trouvé</div>';
            }
            
            $('#tableBody').html(html);
            renderPagination();
        }

        // Fonction pour la pagination
        function renderPagination() {
            const filtered = getFilteredData();
            const totalPages = Math.max(1, Math.ceil(filtered.length / itemsPerPage));
            const $paginationList = $('#paginationList');
            $paginationList.empty();

            if (currentPage === 1) {
                $paginationList.append(`<li class="page-item disabled"><span class="page-link">«</span></li>`);
            } else {
                $paginationList.append(`<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">«</a></li>`);
            }

            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    $paginationList.append(`<li class="page-item active"><span class="page-link">${i}</span></li>`);
                } else {
                    $paginationList.append(`<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`);
                }
            }

            if (currentPage === totalPages) {
                $paginationList.append(`<li class="page-item disabled"><span class="page-link">»</span></li>`);
            } else {
                $paginationList.append(`<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">»</a></li>`);
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

        // Fonction pour rediriger vers la page de modification
        function openModifierModal(id, type) {
            window.location.href = `modifier_utilisateur.php?id=${id}&type=${type}`;
        }

        function viewDetails(id, type) {
            window.location.href = `detail_utilisateur.php?id=${id}&type=${type}`;
        }

        function supprimerUtilisateur(id, type) {
            if (!confirm(`Êtes-vous sûr de vouloir supprimer cet utilisateur ?`)) return;
            window.location.href = `gestion_utilisateur.php?action=supprimer&id_user=${id}&type_user=${type}`;
        }


        // Initialisation
        $(document).ready(function() {
            renderTable();

            $('#searchInput').on('keyup', function() {
                currentPage = 1;
                renderTable();
            });
        });
    </script>
</body>

</html>

