<?php
//require_once __DIR__ . '/../authentification/database.php';

class InfosValidateur {
    private $pdo;
    private $id;

    public function __construct($id_validateur) {
        $db = new Database();
        $this->pdo = $db->pdo;
        $this->id = $id_validateur;
    }

    /**
     * Récupérer les informations du validateur
     */
    public function getInfos() {
        $sql = "SELECT v.id_v, v.nom_complet_v, v.email_v, v.id_dep, d.nom_dep
                FROM validateur v
                LEFT JOIN departement d ON v.id_dep = d.id_dep
                WHERE v.id_v = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateProfild($id_user, $new_password = null) {
        if ($new_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE validateur SET  mdps_v = ? WHERE id_v = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([ $hashed_password, $id_user]);
    }
    else{
      return false;


}}


}
