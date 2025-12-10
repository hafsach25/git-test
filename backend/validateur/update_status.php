<?php
if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
require_once __DIR__ . "/../authentification/database.php";
require_once __DIR__ . "/../Notifications/DemandeurEmailService.php";
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
        $r=$update->execute([$status, $id]);
        $DemandeurEmailService = new DemandeurEmailService();
        $query=$this->db->prepare("SELECT d.id_dm as id, d.id_demandeur, d.typedebesoin as type_besoin, d.date_creation_dm, d.status, d.description_dm as description, dem.nom_complet_d as nom , dem.email_d FROM demande d LEFT JOIN demandeur dem ON d.id_demandeur = dem.id_d WHERE d.id_dm = ?");
        $query->execute([$id]);
        $demandeDetails = $query->fetch(PDO::FETCH_ASSOC);
        $DemandeurEmailService->envoyerChangementStatut(
            $demandeDetails['email_d'],
            $demandeDetails['nom_complet_d'],
            $status,
            $demandeDetails
        );
        return $r;

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
   

