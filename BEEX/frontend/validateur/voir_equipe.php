<?php
session_start();
require_once __DIR__ . '/../../../backend/authentification/database.php';
require_once __DIR__ . '/../../../backend/validateur/recup_equipe.php';

// Vérification : seulement les validateurs ont accès
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../BEEX/frontend/authentification/login.php");
    exit;
}
$db = new Database();
$conn = $db->pdo;

$validateur_id = $_SESSION['user_id'];
$validateur_id = $_SESSION['user_id'] ?? 0;
$validateur = new Validateur($validateur_id);

// Récupérer les demandeurs supervisés
$demandeurs = $validateur->getDemandeursSupervises();

// Page précédente
$previousPage = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Équipe des Demandeurs – BEEX</title>

    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/validateur assets/equipe.css">

<body>
    <?php
require_once __DIR__ ."/header_menu.php";?>

    <main class="main-content">
        <div class="cote">
            <a href="dashboard.php" class="retour_dashboard text-decoration-none"><i class="bi bi-arrow-left "></i>
                Retour à la page d'acceuil</a>
        </div>
        <h1 class="page-title">Équipe des Demandeurs</h1>
        <p class="page-subtitle">Liste des demandeurs supervisés par vous</p>
        <div class="table-section">
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Nombre de demandes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Les lignes seront générées par JS -->
                </tbody>
            </table>

            <!-- Pagination -->
            <nav>
                <ul class="pagination pagination-beex" id="pagination" style="justify-content:center;display:flex"></ul>
            </nav>
        </div>
        </table>
        </div>
    </main>
</body>

<script>
// Convertir les demandeurs PHP en JS
let demandeurs = <?= json_encode($demandeurs) ?>;

const ITEMS_PER_PAGE = 5; // Nombre de lignes par page
let currentPage = 1;

// Fonction pour afficher le tableau avec gestion "aucun demandeur"
function renderTable() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    if (demandeurs.length === 0) {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td colspan="5" class="text-center text-danger">
                                Aucun demandeur n’est associé à votre compte.
                            </td>`;
        tbody.appendChild(tr);
        document.getElementById('pagination').innerHTML = ''; // cacher pagination
        return;
    }

    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const end = start + ITEMS_PER_PAGE;
    const page = demandeurs.slice(start, end);

    page.forEach((d, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
                <td>${start + index + 1}</td>
                <td>${d.nom_complet_d}</td>
                <td>${d.email_d}</td>
                <td>${d.nb_demandes}</td>
                <td>
                    <a href="details_demandeur.php?id=${d.id_d}">
                        <button class="btn-detail">Détails</button>
                    </a>
                </td>
            `;
        tbody.appendChild(tr);
    });
}

// Fonction pour afficher la pagination
function renderPagination() {
    if (demandeurs.length === 0) return; // pas de pagination si vide

    const totalPages = Math.ceil(demandeurs.length / ITEMS_PER_PAGE);
    const pag = document.getElementById('pagination');
    pag.innerHTML = '';

    // Bouton Précédent
    const prev = document.createElement('li');
    prev.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prev.innerHTML = `<a class="page-link" href="#">«</a>`;
    prev.onclick = () => goToPage(currentPage - 1);
    pag.appendChild(prev);

    // Boutons numérotés
    for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        li.onclick = () => goToPage(i);
        pag.appendChild(li);
    }

    // Bouton Suivant
    const next = document.createElement('li');
    next.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    next.innerHTML = `<a class="page-link" href="#">»</a>`;
    next.onclick = () => goToPage(currentPage + 1);
    pag.appendChild(next);
}

// Changer de page
function goToPage(page) {
    const totalPages = Math.ceil(demandeurs.length / ITEMS_PER_PAGE);
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        renderTable();
        renderPagination();
    }
}

// Affichage initial
renderTable();
renderPagination();
</script>


</html>