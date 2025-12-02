<?php
session_start();
include_once '../../../backend/recuperation mdps/ResetPassword.php';

$type = $_GET['type'] ?? null;
if (!$type || !in_array($type, ['demandeur','validateur','administrateur'])) {
    die("Type d'utilisateur invalide");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $reset = new ResetPassword();
    $result = $reset->sendResetCode($email, $type);

    if ($result['status'] === 'success') {
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_type'] = $type;
        header("Location: confirmation_code.php");
        exit;
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <!--width=device-width = largeur reelle de ecran -->
    <!-- initial-scale=1= sans zoom, echelle normal -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mot de passe oublié – BEEX</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/auth.css">
</head>

<body>

    <div class="login-page">
        <div class="row  w-100 m-0">
            <div class="col-lg-7 p-0">
                <div class="info-section">
                    <div class="info-content">
                        <img src="../../assets/images/logo_beex2.png" alt="BEEX Logo" class="logo">
                        <h1>Bienvenue sur BEEX</h1>
                        <p>Votre espace intelligent pour gérer toutes vos demandes
                            rapidement et en toute transparence.</p>
                    </div>
                </div>
            </div>


            <div class="col-lg-5 p-0">
                <div class="form-section">
                    <div class="login-card">
                        <h2 class="form-title">Mot de passe oublié</h2>
                        <p class="form-subtitle">
                            Entrez votre email pour recevoir un lien de réinitialisation
                        </p>

                        <form class="login-form" action="" method="POST">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" id="email" name="email" class="form-input" required
                                    placeholder="Entrez votre email">
                            </div>

                            <button type="submit" class="submit-btn">Envoyer le code</button>

                            <div class="text-center">
                                <a href="login.php" class="forgot-password-link">Retour à la page de connexion</a>
                            </div>

                            <?php if (isset($error)): ?>
                            <div style="color:red; font-weight:bold;">
                                <p style="text-align:center;"><i class="bi bi-x-circle"></i> <?php echo $error; ?></p>
                            </div>
                            <?php endif; ?>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>