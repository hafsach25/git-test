<?php
require_once  __DIR__ . '/../authentification/database.php';
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } 
class AdminDashboard {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }
    //statistiques globales

    public function getTotalDemandes() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM demande WHERE status='traite' OR status='en_cours' OR status='validee'");
        return $stmt->fetchColumn();
    }
        public function getTraite() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM demande WHERE status='traite'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
        public function getValidee() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM demande WHERE status='validee'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getEnCours() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM demande WHERE status='en_cours'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    //top 5 chefs de service ayant traité le plus de demandes
    public function getTopChefs($limit = 5) {
        $sql = "SELECT validateur.nom_complet_v AS nom, COUNT(demande.id_dm) AS total
            FROM demande
            JOIN validateur ON demande.id_v = validateur.id_v 
            GROUP BY validateur.id_v
            ORDER BY total DESC
            LIMIT :limit";//a changer demande.id_validateur
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT); // lie une valeur à un paramètre nommé dans la requête SQL préparée (obligatoire pour LIMIT)
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //top demandes par service
    public function getDemandesParService() {
        $sql = "SELECT s.nom_service AS service, COUNT(d.id_dm) AS total
                FROM service s
                LEFT JOIN demande d ON d.id_service = s.id_service
                GROUP BY s.id_service
                ORDER BY COUNT(d.id_dm) DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        public function getDernieresDemandes($limit = 5) {
        $sql="SELECT dm.id_dm AS id, d.nom_complet_d AS demandeur, t.nom_tb type, dm.date_creation_dm AS date_creation, dm.status AS status
                                     FROM demande dm
                                     JOIN demandeur d ON dm.id_demandeur = d.id_d
                                     JOIN type_besoin t ON dm.typedebesoin = t.id_tb
                                     WHERE dm.status IN ('en_cours', 'validee', 'traite')
                                     ORDER BY date_creation_dm DESC 
                                     LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }







    public function getDernieresInfosDemandes($limit = 10) {
    // On récupère les dernières demandes avec informations nécessaires
    $sql = "SELECT 
                d.id_dm AS id, 
                u.nom_complet_d AS demandeur, 
                d.typedebesoin AS type, 
                d.date_creation_dm AS date_creation, 
                d.status AS status
            FROM demande d
            LEFT JOIN demandeur u ON d.id_demandeur = u.id_d
            ORDER BY d.date_creation_dm DESC
            LIMIT :limit";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}
