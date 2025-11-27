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

<div class="container">
    <div class="row">
        <div class="col-lg-6">
                <img src="../../assets/images/logo_beex2.png" alt="Logo BEEX" class="logo-login">
                <h1>Bienvenue dans BEEX</h1>
                <p>Gérez toutes vos demandes rapidement et en toute transparence.</p>
        </div>

        <div class="col-md-6">
            <div class="reset-card">
                <h3 class="text-center">Mot de passe oublié</h3>
                <p class="text-center ">
                    Entrez le code de confirmation envoyé à votre email
                </p>

                <form>
                    <div class="mb-3">
                        <label class="form-label">Code de confirmation</label>
                        <input type="text" class="form-control " placeholder="Entrez le code de confirmation">
                    </div>
                     <button type="submit" class="btn btn-beex "><a href="changer_mdps.php">Vérifier le code</a></button>

                    <div class="text-center ">
                        <a href="login.php" class="link-beex">Retour à la page de connexion</a>
                    </div>
                </form>
            </div>
        </div>