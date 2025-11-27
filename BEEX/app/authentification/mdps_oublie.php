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
                <img alt="Logo BEEX" class="logo-login">
                <h1>Bienvenue dans BEEX</h1>
                <p>Gérez toutes vos demandes rapidement et en toute transparence.</p>
        </div>
        <div class="col-lg-6">
                <h3 class="text-center ">Connexion</h3>
                <p class="text-center ">Accédez à votre tableau de bord</p>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name='email' class="form-control" placeholder="exemple@entreprise.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name='password' class="form-control" placeholder="Votre mot de passe" required>
                    </div>
                    <button type="submit" class="btn btn-beex ">Se connecter</button>
                    <div class="text-end ">
                        <a href="#" class="link-beex">Mot de passe oublié ?</a>
                    </div>

                </form>
        </div>