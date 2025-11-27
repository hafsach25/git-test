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
                        <h3 class="form-title">Mot de passe oublié</h3>
                        <p class="form-subtitle">
                            Entrez le code de confirmation envoyé à votre email
                        </p>

                        <form class="login-form" action="" method="POST">
                            <div class="form-group">
                                <label for="confirmation_code">Code de confirmation</label>
                                <input type="text" id="confirmation_code" class="form-input "
                                    placeholder="Entrez le code de confirmation" required>
                            </div>
                            <button type="submit" class="submit-btn"><a href="changer_mdps.php">Vérifier le
                                    code</a></button>

                            <div class="text-center ">
                                <a href="login.php" class="forgot-password-link">Retour à la page de connexion</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>