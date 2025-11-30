<?php if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
require_once __DIR__  . '/../authentification/database.php';
$db= new Database();
$pdo=$db->pdo;
$sql="SELECT 
                d.id_dm,
                t.nom_tb AS type_besoin,
                d.date_creation_dm,
                d.status,
                s.nom_service,
                d.description_dm,
                d.date_limite_dm,
                d.urgence_dm,
                d.transfere
            FROM demande d
            JOIN type_besoin t ON d.id_typedebesoin = t.id_tb
            JOIN service s ON d.id_service = s.id_service
            WHERE d.id_demandeur = ?
            ORDER BY d.date_creation_dm DESC
        ";
$stmt=$pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$_SESSION['imported_demandes']=$stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($_SESSION['imported_demandes']);

?>