<?php 
require_once __DIR__ .'/../authentification/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 

class GestionUtilisateurs {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    /**
     * Récupérer tous les utilisateurs (demandeurs et validateurs)
     */
    public function getAllUsers(): array {
        $users = [];
        
        // Récupérer les demandeurs
        $stmt = $this->pdo->prepare("
            SELECT 
                CONCAT('USR', LPAD(d.id_d, 3, '0')) AS id_affichage,
                d.id_d AS id,
                'Demandeur' AS type_user,
                d.nom_complet_d AS nom_prenom,
                d.email_d AS email,
                d.id_validateur,
                d.id_dep,
                v.nom_complet_v AS chef_nom,
                v.email_v AS chef_email,
                dep.nom_dep AS departement,
                d.poste_d AS poste
            FROM demandeur d
            LEFT JOIN validateur v ON d.id_validateur = v.id_v
            LEFT JOIN departement dep ON d.id_dep = dep.id_dep
            ORDER BY d.id_d DESC
        ");
        $stmt->execute();
        $demandeurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer les validateurs (Chefs)
        $stmt = $this->pdo->prepare("
            SELECT 
                CONCAT('USR', LPAD(v.id_v + 1000, 3, '0')) AS id_affichage,
                v.id_v AS id,
                'Chef' AS type_user,
                v.nom_complet_v AS nom_prenom,
                v.email_v AS email,
                NULL AS id_validateur,
                v.id_dep,
                NULL AS chef_nom,
                NULL AS chef_email,
                dep.nom_dep AS departement,
                NULL AS poste
            FROM validateur v
            LEFT JOIN departement dep ON v.id_dep = dep.id_dep
            ORDER BY v.id_v DESC
        ");
        $stmt->execute();
        $validateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Combiner les résultats
        $users = array_merge($demandeurs, $validateurs);
        
        // Trier par ID d'affichage
        usort($users, function($a, $b) {
            return strcmp($a['id_affichage'], $b['id_affichage']);
        });
        
        return $users;
    }

    /**
     * Récupérer un utilisateur par ID et type
     */
    public function getUserById($id, $type) {
        if ($type === 'Demandeur') {
            $stmt = $this->pdo->prepare("
                SELECT 
                    d.id_d AS id,
                    d.nom_complet_d AS nom_prenom,
                    d.email_d AS email,
                    d.id_validateur,
                    d.id_dep,
                    d.poste_d AS poste,
                    d.mdps_d AS password,
                    v.nom_complet_v AS chef_nom,
                    v.email_v AS chef_email,
                    dep.nom_dep AS departement
                FROM demandeur d
                LEFT JOIN validateur v ON d.id_validateur = v.id_v
                LEFT JOIN departement dep ON d.id_dep = dep.id_dep
                WHERE d.id_d = :id
            ");
        } else {
            $stmt = $this->pdo->prepare("
                SELECT 
                    v.id_v AS id,
                    v.nom_complet_v AS nom_prenom,
                    v.email_v AS email,
                    v.id_dep,
                    v.mdps_v AS password,
                    dep.nom_dep AS departement
                FROM validateur v
                LEFT JOIN departement dep ON v.id_dep = dep.id_dep
                WHERE v.id_v = :id
            ");
        }
        
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer tous les chefs (validateurs)
     */
    public function getChefs(): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                v.id_v AS id,
                v.nom_complet_v AS nom,
                v.email_v AS email,
                v.id_dep
            FROM validateur v
            ORDER BY v.nom_complet_v
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer le département d'un validateur
     */
    public function getDepartementValidateur($id_validateur) {
        if (empty($id_validateur)) {
            return null;
        }
        
        $stmt = $this->pdo->prepare("
            SELECT id_dep 
            FROM validateur 
            WHERE id_v = :id
        ");
        $stmt->execute([':id' => $id_validateur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id_dep'] : null;
    }

    /**
     * Récupérer tous les départements (équipes)
     */
    public function getEquipes(): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                id_dep AS id,
                nom_dep AS nom
            FROM departement
            ORDER BY nom_dep
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les membres d'une équipe (demandeurs d'un département)
     */
    public function getMembresEquipe($id_dep): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                d.id_d AS id,
                d.nom_complet_d AS nom,
                d.email_d AS email
            FROM demandeur d
            WHERE d.id_dep = :id_dep
            ORDER BY d.nom_complet_d
        ");
        $stmt->execute([':id_dep' => $id_dep]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer l'équipe d'un validateur (demandeurs assignés à ce validateur)
     */
    public function getEquipeValidateur($id_validateur): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                d.id_d AS id,
                d.nom_complet_d AS nom,
                d.email_d AS email,
                d.poste_d AS poste
            FROM demandeur d
            WHERE d.id_validateur = :id_validateur
            ORDER BY d.nom_complet_d
        ");
        $stmt->execute([':id_validateur' => $id_validateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Modifier un utilisateur
     */
    public function modifierUtilisateur($id, $type, $data): array {
        try {
            if ($type === 'Demandeur') {
                $sql = "UPDATE demandeur SET 
                        nom_complet_d = :nom,
                        email_d = :email,
                        id_validateur = :id_validateur,
                        id_dep = :id_dep,
                        poste_d = :poste";
                
                if (!empty($data['password'])) {
                    $sql .= ", mdps_d = :password";
                }
                
                $sql .= " WHERE id_d = :id";
                
                $params = [
                    ':id' => $id,
                    ':nom' => $data['nom'],
                    ':email' => $data['email'],
                    ':id_validateur' => $data['id_validateur'] ?? null,
                    ':id_dep' => $data['id_dep'] ?? null,
                    ':poste' => $data['poste'] ?? null
                ];
                
                if (!empty($data['password'])) {
                    $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
            } else {
                $sql = "UPDATE validateur SET 
                        nom_complet_v = :nom,
                        email_v = :email,
                        id_dep = :id_dep";
                
                if (!empty($data['password'])) {
                    $sql .= ", mdps_v = :password";
                }
                
                $sql .= " WHERE id_v = :id";
                
                $params = [
                    ':id' => $id,
                    ':nom' => $data['nom'],
                    ':email' => $data['email'],
                    ':id_dep' => $data['id_dep'] ?? null
                ];
                
                if (!empty($data['password'])) {
                    $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return ['success' => true, 'message' => 'Utilisateur modifié avec succès'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function supprimerUtilisateur($id, $type): array {
        try {
            // Démarrer une transaction
            $this->pdo->beginTransaction();
            
            if ($type === 'Demandeur') {
                $sql = "DELETE FROM demandeur WHERE id_d = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
            } else {
                // Si c'est un validateur (Chef), réassigner tous ses demandeurs au validateur id=1
                // Vérifier d'abord si le validateur id=1 existe
                $checkValidateur = $this->pdo->prepare("SELECT id_v, id_dep FROM validateur WHERE id_v = 1");
                $checkValidateur->execute();
                $validateurDefault = $checkValidateur->fetch(PDO::FETCH_ASSOC);
                
                if (!$validateurDefault) {
                    $this->pdo->rollBack();
                    return ['success' => false, 'message' => 'Le validateur par défaut (id=1) n\'existe pas'];
                }
                
                // Récupérer le département du validateur par défaut
                $id_dep_default = $validateurDefault['id_dep'];
                
                // Réassigner tous les demandeurs de ce validateur au validateur id=1
                // et mettre à jour leur département
                $updateDemandeurs = $this->pdo->prepare("
                    UPDATE demandeur 
                    SET id_validateur = 1, 
                        id_dep = :id_dep
                    WHERE id_validateur = :id_validateur
                ");
                $updateDemandeurs->execute([
                    ':id_dep' => $id_dep_default,
                    ':id_validateur' => $id
                ]);
                
                // Supprimer le validateur
                $sql = "DELETE FROM validateur WHERE id_v = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
            }
            
            // Valider la transaction
            $this->pdo->commit();
            
            $message = $type === 'Demandeur' 
                ? 'Utilisateur supprimé avec succès' 
                : 'Validateur supprimé avec succès. Son équipe a été réassignée au validateur par défaut.';
            
            return ['success' => true, 'message' => $message];
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }
}

?>

