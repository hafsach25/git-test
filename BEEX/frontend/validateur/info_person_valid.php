<?php
session_start();
require_once __DIR__ . '/../../../backend/authentification/database.php';
require_once __DIR__ . '/../../../backend/validateur/recup_infos.php';


// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../../BEEX/frontend/authentification/login.php');
    exit;
}
$db = new Database();
$conn = $db->pdo;

// Page précédente
$previousPage = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';

// ID utilisateur connecté
$id_actuel = $_SESSION['user_id'] ?? 0;
$infosValidateur = new InfosValidateur($id_actuel);
// Récupérer les infos
$infos = $infosValidateur->getInfos();
if (!$infos) {
    die("Utilisateur introuvable.");
}


// Changer le mot de passe


$id_user = $_SESSION['user_id']?? null; 
if (isset($_POST['save_password'])) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Les mots de passe ne correspondent pas !');</script>";
    } elseif (empty($new_password)) {
        echo "<script>alert('Veuillez entrer un mot de passe.');</script>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $id_user = (int)$_SESSION['user_id']; // s'assurer que c'est un entier
        $stmt = $conn->prepare("UPDATE validateur SET mdps_v = :mdp WHERE id_v = :id");
        $success = $stmt->execute([
            ':mdp' => $hashed_password,
            ':id' => $id_user
        ]);

        if ($success) {
            echo "<script>alert('Mot de passe modifié avec succès !');</script>";
        } else {
            echo "<script>alert('Erreur lors de la modification.');</script>";
        }
    }
}

if (isset($_POST['revenir_bord'])){
   
    // rediriger vers le dashboard du demandeur
    header("Location: dashboard.php");
    exit;



}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes informations – Validateur</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/inf_demandeur.css">
</head>

<body>
    <?php include("header_menu.php"); ?>

    <div class="cote">
        <a href="dashboard.php" class="retour_dashboard"><i class="bi bi-arrow-left"></i> Retour à la page d'accueil</a>
        <h2>Mes informations</h2>
    </div>

    <div class="main-content">
        <div class="container my-2">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm p-4">
                        <form action="" method="post">

                            <!-- Nom -->
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control"
                                    value="<?= htmlspecialchars($infos['nom_complet_v']) ?>" readonly>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control"
                                    value="<?= htmlspecialchars($infos['email_v']) ?>" readonly>
                            </div>

                            <!-- Département -->
                            <div class="mb-3">
                                <label class="form-label">Département</label>
                                <input type="text" class="form-control"
                                    value="<?= htmlspecialchars($infos['nom_dep'] ?? '-') ?>" readonly>
                            </div>

                            <div class="my-4">
                                <h4>Changer le mot de passe</h4>
                            </div>

                            <div class="mb-3 position-relative">
                                <label class="form-label">Nouveau mot de passe :</label>
                                <input type="password" id="new_password" name="new_password" class="form-control"
                                    placeholder="Entrez un nouveau mot de passe">
                                <i class="bi bi-eye-slash" id="toggleNew"
                                    style="position:absolute; right:10px; top:38px; cursor:pointer; font-size:20px"></i>
                            </div>

                            <div class="mb-3 position-relative">
                                <label class="form-label">Confirmer le mot de passe :</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    class="form-control" placeholder="Confirmez le mot de passe">
                                <i class="bi bi-eye-slash" id="toggleConfirm"
                                    style="position:absolute; right:10px; top:38px; cursor:pointer; font-size:20px"></i>
                            </div>

                            <div class="btn-container">
                                <button type="submit" class="btn-cancel mt-3" name="revenir_bord">Annuler</button>
                                <button type="submit" class="btn-save mt-3" name="save_password">Enregistrer

                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        }
    }
    document.getElementById("toggleNew").addEventListener("click", () => togglePassword("new_password", "toggleNew"));
    document.getElementById("toggleConfirm").addEventListener("click", () => togglePassword("confirm_password",
        "toggleConfirm"));
    </script>

</body>

</html>