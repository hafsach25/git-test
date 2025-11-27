<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>informations personnelles </title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/inf_demandeur.css">
</head>
<body>
    <div class="cote">
       <a href="dashboard.php" class="retour_dashboard">← Retour à la page d'acceuil</a>
       <h2>Mes informations</h2>
    </div>
    <div class="main-content">
    <div class="form-wrapper d-flex justify-content-center">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 650px;">
            <form class="border-form">
                <!-- Nom -->
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" value="Noura Ouahib" readonly>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="noura@example.com" readonly>
                </div>

                <!-- Département -->
                <div class="mb-3">

                    <label class="form-label">Département</label>
                    <input type="text" class="form-control" value="Informatique" readonly>
</div>
<div class="mb-3">
                    <label class="form-label">Poste</label>
                    <input type="text" class="form-control" value="Informatique" readonly>


                </div>

                <!-- Chef -->
                <div class="mb-3">
                    <label class="form-label">Chef</label>
                    <input type="text" class="form-control" value="Mme xxxxx" readonly>
                </div>

                <div class="my-4">

                <h4 >Changer le mot de passe</h4>
</div>

                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe :</label>
                    <input type="password" class="form-control" placeholder="Entrez un nouveau mot de passe">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmer le mot de passe :</label>
                    <input type="password" class="form-control" placeholder="Confirmez le mot de passe">
                </div>

                <div class="btn-container">


                <button class="btn-cancel mt-3">Annuler</button>
                <button class="btn-save mt-3">Enregistrer</button>
</div>
            </form>
        </div>
    </div>
</div>


</body>