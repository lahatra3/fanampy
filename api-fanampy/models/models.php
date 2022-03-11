<?php
abstract class Database {
    
    public function __construct() {
        $lahatra=json_decode(file_get_contents('./db.json'));
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

    public function getAllMembres():array {
        try {
            $database=Database::db_connect();
            $demande=$database -> query('SELECT nom, prenoms, adresse, phone1, phone2, email, 
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

    public function getMembres(array $donnees) {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('SELECT nom, prenoms, adresse, phone1, phone2, email, 
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

    protected function verifyMembres(array $donnees) {
        $database=Database::db_connect();
        $demande=$database->prepare('SELECT True FROM membres
            WHERE (nom=:nom AND prenoms=:prenoms) OR email=:email');
        $demande->execute($donnees);
        $reponses=$demande->fetch(PDO::FETCH_ASSOC);
        $demande->closeCursor();
        if(empty($reponses)) $reponses['TRUE']=0;
        return $reponses['TRUE'];
    }

    public function addMembres(array $donnees) {
        try {
            if($this->verifyMembres()===1) {
                $database=Database::db_connect();
                $demande=$database->prepare('INSERT INTO membres(nom, prenoms, adresse, 
                    phone1, phone2, email, dateNaisssance, lieuNaissance, villeOrigine,
                    keypass)
                    VALUES(:nom, :prenoms, :adresse, :phone1, :phone2, :email, 
                    :dateNaissance, :lieuNaissance, :villeOrigine, :keypass)');
                $demande->execute($donnees);
                $status=1;
            }
            else $status=0;
            return $status;
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu ajouter MEMBRES. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function updateMembres(array $donnees) {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('UPDATE membres
                SET adresse=:adresse, phone1=:phone1, phone2=:phone2
                WHERE id=:identifiant');
            $demande->execute($donnees);
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu mettre à jours MEMBRES. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }

    public function deleteMembres(array $donnees) {
        try {
            $database=Database::db_connect();
            $demande=$database->prepare('DELETE FROM membres
                WHERE id=:identifiant');
            $demande->execute($donnees);
        }
        catch(PDOException $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Nous n'avons pas pu supprimer MEMBRES. ".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
        $database=null;
    }
}

$lahatra = new Membres;
print_r(json_encode($lahatra->getAllMembres(), JSON_FORCE_OBJECT));
