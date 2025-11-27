<!DOCTYPE html>
<header>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/auth.css">
</header>
<body>
    <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <img src="../../assets/images/logo_beex2.png" alt="BEEX Logo" class="logo">
        <h1>Bienvenue sur BEEX</h1>
        <p>Votre espace intelligent pour gérer toutes vos demandes
rapidement et en toute transparence.</p>
        </div>
      <div class="col-lg-6">
        <div class="login-container">
            <h2 class="form-title">Connexion</h2>
            <p class="form-subtitle">Accéder à votre tableau de bord</p>
            <form action="../../core/authentification/login_process.php" method="POST" class="login-form">
                <div class="form-group">
                   <label for="username" >Email:</label>
                   <input type="text" id="username" name="username" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                <button type="submit" class="submit-btn">Connexion</button>
                <a href="mdps_oublie.php" class="forgot-password-link">Mot de passe oublié?</a>
            </form>
        </div>
    </div>
    </div>
</body>