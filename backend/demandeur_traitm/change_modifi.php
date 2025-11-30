<?php
require_once __DIR__ .'/../authentification/database.php';
class changDeman{
    private $db;
    private $pdo;
    public function __construct() {
        $this->db = new Database();
        $this->pdo = $this->db->pdo; 
    }
        // Récupérer une demande par son ID
    public function getDemandeById($id_dm) {
        $stmt = $this->pdo->prepare("SELECT * FROM demande WHERE id_dm = ?");
        $stmt->execute([$id_dm]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    return [
        'type_besoin' => $result['typedebesoin'], // récupère l'id du type
        'description' => $result['description_dm'],
        'urgence' => $result['urgence_dm'],
        'date_limite' => $result['date_limite_dm'],
        'attachments' => $result['piece_jointe_dm'],
    ];
}

return null;


    }
        // Mettre à jour la demande
    public function updateDemande($id_dm, $type_besoin, $description, $urgence, $date_limite, $filename = null) {
        if ($filename) {
    $stmt = $this->pdo->prepare("UPDATE demande SET id_typedebesoin = ?, description_dm = ?, urgence_dm = ?, date_limite_dm = ?, piece_jointe_dm = ? WHERE id_dm = ?");
    return $stmt->execute([$type_besoin, $description, $urgence, $date_limite, $filename, $id_dm]);
} else {
    $stmt = $this->pdo->prepare("UPDATE demande SET id_typedebesoin = ?, description_dm = ?, urgence_dm = ?, date_limite_dm = ? WHERE id_dm = ?");
    return $stmt->execute([$type_besoin, $description, $urgence, $date_limite, $id_dm]);
}

    }
}

