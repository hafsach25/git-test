<?php
session_start();

// Ajuste le chemin si nécessaire
require_once  '../../../backend/recuperation mdps/ResetPassword.php';

// INITIALISATIONS (évite les warnings)
$error = '';
$success = '';
$redirect = false;
$newPassword = '';
$confirmPassword = '';
$result = null;

// Vérifier la session de reset
if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_table'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération sécurisée des champs
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    // Validations
    if ($newPassword !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas !";
    } elseif (strlen($newPassword) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $email = $_SESSION['reset_email'];
        $table = $_SESSION['reset_table'];

        // Appel à la classe (doit retourner un tableau)
        $passwordManager = new ResetPassword();
        $result = $passwordManager->updatePassword($email, $table, $newPassword);

        // Vérifier sans provoquer d'avertissement
        if (is_array($result) && ($result['status'] ?? '') === 'success') {
            unset($_SESSION['reset_email'], $_SESSION['reset_table']);
            $success = "Mot de passe modifié avec succès. Redirection...";
            $redirect = true;
        } else {
            $error = $result['message'] ?? "Une erreur est survenue lors de la mise à jour.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Changer Mot de passe – BEEX</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/auth.css">
</head>

<body>

    <div class="login-page">
        <div class="row w-100 m-0">

            <!-- PARTIE GAUCHE -->
            <div class="col-lg-7 p-0">
                <div class="info-section">
                    <div class="info-content">
                        <img src="../../assets/images/logo_beex2.png" alt="Logo BEEX" class="logo">
                        <h1>Bienvenue dans BEEX</h1>
                        <p>Votre espace intelligent pour gérer toutes vos demandes rapidement et en toute transparence.
                        </p>
                    </div>
                </div>
            </div>

            <!-- PARTIE FORMULAIRE -->
            <div class="col-lg-5 p-0">
                <div class="form-section">
                    <div class="login-card">

                        <h2 class="form-title">Changer mot de passe</h2>
                        <p class="form-subtitle">
                            Créez un mot de passe sécurisé et unique pour protéger votre compte.
                        </p>
                 <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if (!empty($success)) : ?>
    <div class="alert alert-success"><?= $success ?></div>

    <script>
        setTimeout(function () {
            window.location.href = "login.php";
        }, 2000);
    </script>
<?php endif; ?>


                        <!-- FORMULAIRE -->
                        <form class="login-form" method="POST" action="">
                            <div class="form-group">
                                <label class="form-label">Nouveau mot de passe :</label>
                                <input type="password" name="new_password" class="form-input"
                                    placeholder="Nouveau mot de passe" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirmation du mot de passe :</label>
                                <input type="password" name="confirm_password" class="form-input"
                                    placeholder="Confirmation" required>
                            </div>

                            <button type="submit" class="submit-btn">Enregistrer</button>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

</body>

</html>