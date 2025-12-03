<?php
if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
require_once __DIR__ . "/../authentification/database.php";
class UpdateStatus {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->pdo; 
    }

    /**
     * Met à jour le statut d'une demande
     * @param int $id ID de la demande
     * @param string $status Nouveau statut ('validée' ou 'rejetée')
     * @return bool true si mise à jour réussie, false sinon
     * @throws Exception si la demande n'existe pas ou n'est pas modifiable
     */
    public function update(int $id, string $status): bool {
        // Vérifier si le statut est valide
        if (!in_array($status, ['validee', 'rejete'])) {
            throw new Exception("Statut invalide.");
        }

        // Vérifier si la demande est en attente
        $stmt = $this->db->prepare("SELECT status FROM demande WHERE id_dm = ?");
        $stmt->execute([$id]);
        $demande = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$demande) {
            throw new Exception("Demande non trouvée.");
        }

        if ($demande['status'] !== 'en_attente') {
            throw new Exception("La demande n'est pas modifiable.");
        }

        // Mettre à jour le statut
        $update = $this->db->prepare("UPDATE demande SET status = ? WHERE id_dm = ?");
        return $update->execute([$status, $id]);
    }
}

$updateStatus = new UpdateStatus();

    if (isset($_GET['id_dm']) && isset($_GET['action'])) {
        $id = (int)$_GET['id_dm'];
        $action = $_GET['action'];

        $result = $updateStatus->update($id, $action);
        if ($result) {
            header("Location: ../../BEEX/frontend/validateur/dashboard.php?message=Statut mis à jour avec succès.");
            exit();
        } else {
            header("Location: ../../BEEX/frontend/validateur/dashboard.php?error=Échec de la mise à jour du statut.");
            exit();
        }
    }
   

