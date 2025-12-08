<?php/*
require_once __DIR__ . '/../../../backend/authentification/database.php';
$db = new Database();
$pdo = $db->pdo;

try {
    $pdo->beginTransaction();

    // Récupérer tous les admins
    $stmt = $pdo->query("SELECT id_ad, mdps_ad FROM administrateur");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Préparer la requête d'update
    $update = $pdo->prepare("UPDATE administrateur SET mdps_ad = ? WHERE id_ad = ?");
    foreach ($admins as $ad) {
        $plain = $ad['mdps_ad'];

        // Vérifie si le mot de passe n'est PAS encore un hash bcrypt
        if (!preg_match('/^\$2y\$/', $plain)) {
            $hash = password_hash($plain, PASSWORD_DEFAULT);
            $update->execute([$hash, $ad['id_ad']]);
        }
    }

    $pdo->commit();
    echo "✔ Migration des mots de passe terminée avec succès.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "❌ Erreur : " . $e->getMessage();
}*/
?>