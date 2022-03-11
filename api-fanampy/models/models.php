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
                'message' => "Nous n'avons pas obtenir MEMBRES.".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
    }
}

$lahatra = new Membres;
print_r(json_encode($lahatra->getAllMembres(), JSON_FORCE_OBJECT));
