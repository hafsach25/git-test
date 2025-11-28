<?php
session_start();
include __DIR__ . '/../../../backend/demandeur_traitm/recup_par_email.php';
require_once __DIR__ .  '/../../../backend/authentification/database.php';
$db = new Database();
$conn = $db->pdo;

<<<<<<< Updated upstream
var_dump($_SESSION);
=======
>>>>>>> Stashed changes
// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../../BEEX/frontend/authentification/login.php');
    exit;
}

$email = $_SESSION['email'] ?? null;
var_dump($email);
$demandeur = new Demandeur();
$user = $demandeur->getByEmail($email);
if (!$user) {
    echo "Utilisateur introuvable ou non connecté.";
    exit;
}
$id_user = $_SESSION['user_id']?? null; 
if (isset($_POST['save_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        if ($new_password !== $confirm_password) {
           echo "<script>alert('Les mots de passe ne correspondent pas !');</script>";
        } else {
            $query = $conn->prepare("UPDATE demandeur SET mdps_d= ? WHERE id_d= ?");
            $success = $query->execute([$new_password, $id_user]);
            if ($success) {
              echo "<script>alert('Mot de passe modifié avec succès !');</script>";
              } else {
                echo "<script>alert('Erreur lors de la modification.');</script>";}}}
if (isset($_POST['revenir_bord'])){

}

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>informations personnelles </title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/inf_demandeur.css">
</head>

<body>
    <?php
include ("header_menu.php") ?>
    <div class="cote">
        <a href="dashboard.php" class="retour_dashboard">← Retour à la page d'acceuil</a>
        <h2>Mes informations</h2>
    </div>
    <div class="main-content">
        <div class="form-wrapper d-flex justify-content-center">
            <div class="card shadow-sm p-4" >
                <form action="" method="post">
                    <!-- Nom -->
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['nom_complet_d']); ?>"   readonly>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email_d']); ?>" readonly>
                    </div>

                    <!-- Département -->
                    <div class="mb-3">

                        <label class="form-label">Département</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['nom_dep']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Poste</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['poste_d']); ?>" readonly>


                    </div>

                    <!-- Chef -->
                    <div class="mb-3">
                        <label class="form-label">Chef</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['chef']); ?>"  readonly>
                    </div>

                    <div class="my-4">

                        <h4>Changer le mot de passe</h4>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe :</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Entrez un nouveau mot de passe">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmer le mot de passe :</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirmez le mot de passe">
                    </div>

                    <div class="btn-container">


                        <button type="submit" class="btn-cancel mt-3" name="revenir_bord">Annuler</button>
                        <button type="submit" class="btn-save mt-3" name="save_password" >Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>