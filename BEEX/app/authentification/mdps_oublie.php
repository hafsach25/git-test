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

                        <form class="login-form" actiont="" method="POST">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" id="email" name="email" class="form-input" required
                                    placeholder="Entrez votre email">
                            </div>

                            <button type="submit" class="submit-btn"><a href="confirmation_code.php">Envoyer le
                                    lien</a></button>

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