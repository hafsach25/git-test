<?php
session_start();
// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../../BEEX/frontend/authentification/login.php');
    exit;
}

require_once __DIR__ . '/../../../backend/administrateur/profil.php';


$email = $_SESSION['email'] ?? null;
$profil = new Profil();
$user = $profil->getByEmail_admin($email);
if (!$user) {
    echo "Utilisateur introuvable ou non connecté.";
    exit;
}


$id_user = $_SESSION['user_id'] ?? null;
$message = '';
$error = '';

if (isset($_POST['save_password'])) {
    $nom = $_POST['nom'] ?? null;        // si tu veux permettre la modification du nom
    $email = $_POST['email'] ?? null;    // si tu veux permettre la modification de l'email
    $new_password = $_POST['new_password'] ?? null;
    $confirm_password = $_POST['confirm_password'] ?? null;
    if(empty($nom) || empty($email)) {
    $error = "Le nom et l'email ne peuvent pas être vides !";
}

    elseif (!empty($new_password) && $new_password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas !";
    } else {
        $success = $profil->updateProfil( $id_user, $nom, $email, $new_password ?: null);
        if ($success) {
            
    $message = "Profil modifié avec succès !";
    // Mettre à jour nom et l'email dans la session
$_SESSION['username'] = $nom;

$_SESSION['email'] = $email;

    // Recharger les données depuis la BDD
    $user = $profil->getByEmail_admin($email);


        } else {
            $error = "Erreur lors de la modification.";
        }
    }
}

if (isset($_POST['revenir_bord'])) {
    header("Location: dashboard.php");
    exit;
}

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
        <a href="dashboard.php" class="retour_dashboard"><i class="bi bi-arrow-left"></i> Retour à la page d'acceuil</a>
        <h2>Mes informations</h2>
    </div>
    <div class="main-content">
        <div class="container my-2">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm p-4">
                        <form action="" method="post">
                            <!-- Affichage des messages -->
                            <?php if(!empty($message)): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                            <?php endif; ?>
                            <?php if(!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>
                                                        <div class="my-4">

                                <h4><i class="bi bi-person"></i>    Informations personnelles</h4>
                            </div>
                            <!-- Nom -->
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input name="nom" type="text" class="form-control"
                                    value="<?php echo htmlspecialchars($user['nom_complet_ad']); ?>">
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control"
                                    value="<?php echo htmlspecialchars($user['email_ad']); ?>">
                            </div>
                            <div class="my-4">

                                <h4><i class="bi bi-lock"></i>  Changer le mot de passe</h4>
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
                                <button type="submit" class="btn-save mt-3" name="save_password">Enregistrer</button>
                            </div>
                        </form>
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
            // Quand on clique sur l'icône toggleNew (œil du premier champ)

            document.getElementById("toggleNew").addEventListener("click", function() {
                // On appelle la fonction togglePassword pour :
                // - le champ new_password
                // - l'icône toggleNew
                togglePassword("new_password", "toggleNew");

            });
            document.getElementById("toggleConfirm").addEventListener("click", function() {
                togglePassword("confirm_password", "toggleConfirm");
            });
            </script>

</body>