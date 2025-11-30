<?php
session_start();
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/auth.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">



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
                        <h2 class="form-title">Connexion</h2>
                        <p class="form-subtitle">Accéder à votre tableau de bord</p>
                        <form action="../../../backend/authentification/login_process.php" method="POST"
                            class="login-form">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" id="email" name="email" class="form-input" required
                                    placeholder="Entrez votre email">
                            </div>
<div class="form-group position-relative">
    <label for="password">Mot de passe:</label>
    <div class="password-input-container">
        <input type="password" id="password" name="password" class="form-input" required placeholder="Entrez votre mot de passe" >
        <span class="toggle-password" onclick="togglePassword()">
            <i class="bi bi-eye-slash" id="eyeIcon" style="position:absolute; right:10px; top:38px; cursor:pointer; font-size:20px"></i>
        </span>
    </div>
</div>




                            <button type="submit" class="submit-btn">Se connecter</button>
                            <a href="mdps_oublie.php" class="forgot-password-link">Mot de passe oublié?</a>
                        </form>
                        <?php if (isset($_GET['error'])): ?>
                        <div style="color:red; font-weight:bold;">
                            <p style="text-align:center;"><i class="bi bi-x-circle"></i> Email ou mot de passe
                                incorrect.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>



<script>
function togglePassword() {
    const passwordField = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (passwordField.type === "password") {
        // Passe en texte clair
        passwordField.type = "text";
        icon.classList.remove("bi-eye-slash"); // retirer barré
        icon.classList.add("bi-eye");          // ajouter œil normal
    } else {
        // Masque le mot de passe
        passwordField.type = "password";
        icon.classList.remove("bi-eye");       // retirer œil normal
        icon.classList.add("bi-eye-slash");    // ajouter barré
    }
}
</script>




</body>