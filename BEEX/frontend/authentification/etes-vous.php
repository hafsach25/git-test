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
                        <h3 class="form-title">Vous êtes ?</h3>
                        <button class="submit-btn" style="margin-bottom: 10px;" onclick="selectUserType('demandeur')">Demandeur</button>
                        <button class="submit-btn" style="margin-bottom: 10px;" onclick="selectUserType('validateur')">Validateur</button>
                        <button class="submit-btn" style="margin-bottom: 10px;" onclick="selectUserType('administrateur')">Administrateur</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <script>
        function selectUserType(type) {
            // Redirige vers la page email en passant le type en GET
            window.location.href = `mdps_oublie.php?type=${type}`;
        }
    </script>
</body>