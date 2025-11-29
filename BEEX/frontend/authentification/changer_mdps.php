<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <!--width=device-width = largeur reelle de ecran -->
    <!-- initial-scale=1= sans zoom, echelle normal -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>changer Mot de passe – BEEX</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/auth.css">
</head>

<body>

    <div class="login-page">
        <div class="row w-100 m-0">
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


            <div class="col-lg-5 p-0">
                <div class="form-section">
                    <div class="login-card">
                        <h2 class="form-title">Changer mot de passe</h3>
                            <p class="form-subtitle ">
                                Créez un mot de passe sécurisé et unique pour protéger votre compte.
                            </p>
                            <form class="login-form">
                                <div class="form-group">
                                    <label class="form-label">Nouveau mot de passe :</label>
                                    <input class="form-input " placeholder="Nouveau mot de passe" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">confirmation de mot de passe :</label>
                                    <input class="form-input " placeholder="confirmation mot de passe" required>
                                </div>
                                <button type="submit" class="submit-btn "><a href="login.php">Enregistrer</a></button>
                            </form>
                    </div>
                </div>
            </div>
        </div>

</body>