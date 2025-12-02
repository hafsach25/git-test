<?php/*
require_once __DIR__ . '/../../../backend/authentification/database.php';
$db = new Database();
$pdo = $db->pdo;

// Sauvegarde recommandée AVANT la modification !
$pdo->beginTransaction();
try {
    $stmt = $pdo->query("SELECT id_v, mdps_v FROM validateur");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $update = $pdo->prepare("UPDATE validateur SET mdps_v = ? WHERE id_v = ?");

    foreach ($rows as $r) {
        $plain = $r['mdps_v'];
        // Ignore les valeurs déjà hachées : password_verify(false) serait vrai si plain n'est pas hash? mieux vérifier longueur/prefixe
        if (password_needs_rehash($plain, PASSWORD_DEFAULT) || !password_get_info($plain)['algo']) {
            // Si ce champ ressemble à du texte non-haché, on le hache.
            // ATTENTION: password_get_info retourne algo = 0 pour texte en clair.
            $new = password_hash($plain, PASSWORD_DEFAULT);
            $update->execute([$new, $r['id_v']]);
        }
    }

    $pdo->commit();
    echo "Migration terminée.\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erreur : " . $e->getMessage();
}*/
?>