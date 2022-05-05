<?php
abstract class Database {
    
    public function __construct() {
        $lahatra=json_decode(file_get_contents('./models/db.json'));
        $this->host = $lahatra->host;
        $this->dbname = $lahatra->dbname;
        $this->user = $lahatra->user;
        $this->password = $lahatra->password;
    }

    protected function db_connect():object {
        try {
            return new PDO("mysql:host=$this->host; dbname=$this->dbname; charset=utf8", 
                $this->user, $this->password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: connexion à la base de données !".$e -> getMessage()
            ], JSON_FORCE_OBJECT));
        }
    }
}

class Membres extends Database {

    public function getAllMembres(): array {
        try {
            $database=Database::db_connect();
            $demande=$database -> query('SELECT id, nom, prenoms, adresse, phone1, phone2, email, 
                    dateNaissance, lieuNaissance, villeOrigine, date_debut, date_fin, active
                FROM membres 
                WHERE active = 1');
            $reponses=$demande -> fetchAll(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e){
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu obtenir MEMBRES TOUT.".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    public function getMembres(array $donnees): array | bool {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('SELECT id, nom, prenoms, adresse, phone1, phone2, email, 
                 dateNaissance, lieuNaissance, villeOrigine, date_debut, date_fin, active
                FROM membres 
                WHERE active = 1 AND (id=:identifiant OR email=:identifiant)');
            $demande->execute($donnees);
            $reponses=$demande->fetch(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu obtenir MEMBRES. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    private function verifyMembres(array $donnees): int {
        $database=Database::db_connect();
        $demande=$database->prepare('SELECT True FROM membres
            WHERE (nom=:nom AND prenoms=:prenoms) OR email=:email OR phone1=:phone1');
        $demande->execute($donnees);
        $reponses=$demande->fetch(PDO::FETCH_ASSOC);
        $demande->closeCursor();
        return empty($reponses)? 0 : 1;
    }

    public function addMembres(array $donnees, array $verify): int {
        try {
            $status = 0;
            if($this->verifyMembres($verify) === 0) {
                $database=Database::db_connect();
                $demande=$database->prepare('INSERT INTO membres(nom, prenoms, adresse, 
                    phone1, email, dateNaisssance, lieuNaissance, villeOrigine,
                    keypass)
                    VALUES(:nom, :prenoms, :adresse, :phone1, :email, 
                    :dateNaissance, :lieuNaissance, :villeOrigine, :keypass)');
                $demande->execute($donnees);
                $status = 1;
            }
            return $status;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu ajouter MEMBRES. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    private function verifyPhone(array $donnees): int {
        $database=Database::db_connect();
        $demande=$database->prepare('SELECT True FROM membres
            WHERE phone1=:phone1');
        $demande->execute($donnees);
        $reponses=$demande->fetch(PDO::FETCH_ASSOC);
        $demande->closeCursor();
        return !empty($reponses) ? 1 : 0;     
    }

    public function updateMembres(array $donnees, array $verify): int {
        try {
            $status = 0;
            if($this->verifyPhone($verify) === 0) {
                $database=Database::db_connect();
                $demande=$database->prepare('UPDATE membres
                    SET adresse=:adresse, phone1=:phone1
                    WHERE id=:id');
                $demande->execute($donnees);
                $status = 1;
            }
            return $status;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu mettre à jours MEMBRES. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    private function verifyKeypass(array $donnees): int {
        $database=Database::db_connect();
        $demande=$database->prepare('SELECT True FROM membres
            WHERE email=:email AND keypass=SHA2(:lastKey, 256)');
        $demande->execute($donnees);
        $reponses=$demande->fetch(PDO::FETCH_ASSOC);
        $demande->closeCursor();
        return empty($reponses)? 0 : 1;
    }

    public function updateMembresKeypass(array $donnees, array $verify): int {
        try {
            $status = 0;
            if($this->verifyKeypass($verify) === 1) {
                $database=Database::db_connect();
                $demande=$database->prepare('UPDATE membres 
                    SET keypass=SHA2(:newKey, 256)
                    WHERE id=:id');
                $demande->execute($donnees);
                $status = 1;
            }
            return $status;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas mettre à jours MOT DE PASSE. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
    }

    public function deleteMembres(array $donnees): int {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('DELETE FROM membres
                WHERE id=:identifiant');
            $demande->execute($donnees);
            return 1;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu supprimer MEMBRES. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }
}


class Formations extends Database {

    public function getAllFormations(): array {
        try {
            $database=Database::db_connect();
            $demande=$database->query('SELECT f.nom, f.etablissement, f.descriptions, 
                f.id_membres, m.prenoms, m.email
                FROM formations f
                JOIN membres m ON f.id_membres=m.id
                WHERE m.active = 1');
            $reponses=$demande->fetchAll(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false, 
                'message' => "Nous n'avons pas pu obtenir FORMATIONS TOUT. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function getFormations(array $donnees): array {
        try {
            $database=Database::db_connect();
            $demande=$database->query('SELECT f.id, f.nom, f.etablissement, f.descriptions, 
                f.id_membres, m.prenoms, m.email
                FROM formations f
                JOIN membres m ON f.id_membres=m.id
                WHERE m.active = 1 AND m.id=:identifiant');
            $demande->execute($donnees);
            $reponses=$demande->fetchAll(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu obtenir FORMATIONS. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function addFormations(array $donnees): int {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('INSERT INTO formations(nom, etablissement,
                descriptions, id_membres)
                VALUES(:nom, :etablissement, :descriptions, :id_membres)');
            $demande->execute($donnees);
            return 1;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas ajouter FORMATIONS. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
    }

    public function updateFormations(array $donnees): int {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('UPDATE formations 
                SET nom=:nom, etablissement=:etablissement, descriptions=:descriptions
                    WHERE id=:identifiant');
            $demande->execute($donnees);
            return 1;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu mettre à jour FORMATIONS. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function deleteFormations(array $donnees): int {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('DELETE FROM formations 
                WHERE id=:identifiant');
            $demande->execute($donnees);
            return 1;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu supprimer FORMATIONS. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }
}

class Fonctions extends Database {

    public function getAllFonctions(): array {
        try {
            $database=Database::db_connect();
            $demande=$database->query('SELECT f.id, f.nom, f.id_branches, 
                b.nom as branches, f.id_membres, m.prenoms, m.email
                FROM fonctions f
                JOIN branches b ON f.id_branches = b.id
                JOIN membres m ON f.id_membres=m.id
                WHERE m.active = 1');
            $reponses=$demande->fetchAll(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu obtenir FONCTIONS TOUT. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function getFonctions(array $donnees): array {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('SELECT f.id, f.nom, f.id_branches, 
                b.nom as branches, f.id_membres, m.prenoms, m.email
                FROM fonctions f
                JOIN branches b ON f.id_branches = b.id
                JOIN membres m ON f.id_membres=m.id
                WHERE m.active = 1 AND (m.id=:identifiant)');
            $demande->execute($donnees);
            $reponses=$demande->fetchAll(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu obtenir FONCTIONS. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function addFonctions(array $donnees): int {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('INSERT INTO fonctions(nom, id_branches, id_membres)
                VALUES(:nom, :id_branches, :id_membres)');
            $demande->execute($donnees);
            return 1;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu ajouter FONCTIONS. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function updateFonctions(array $donnees): int {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('UPDATE fonctions SET nom=:nom, id_branches=:id_branches,
                 id_membres=:id_membres
                WHERE id=:identifiant');
            $demande->execute($donnees);
            return 1;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu mettre à jour FONCTIONS. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function deleteFonctions(array $donnees): int {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('DELETE FROM fonctions
                WHERE id=:identifiant');
            $demande->execute($donnees);
            return 1;
        }
        catch(PDOException $e) {
            $database->rollBack();
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu supprimer FONCTIONS. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }
}

class Login extends Database {

    public function authentifier(array $donnees): array | bool {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('SELECT True, id, email
                FROM membres
                WHERE (email=:identifiant OR phone1=:identifiant) 
                    AND keypass=SHA2(:keypass, 256)');
            $demande->execute($donnees);
            $reponses=$demande->fetch(PDO::FETCH_ASSOC);
            $demande->closeCursor();
            return $reponses;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu obtenir les données d'authentifications. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }
}
