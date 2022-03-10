<?php
class Database {
    private string $host;
    private string $database;
    private string $user;
    private string $password;

    public function __construct() {
        $lahatra=json_decode(file_get_contents('./db.json'));
        $this->host = $lahatra->host;
        $this->database = $lahatra->database;
        $this->user = $lahatra->user;
        $this->password = $lahatra->password;
    }

    protected function db_connect() {
        try {
            return new PDO("mysql:host=$this->host; dbname=$this->database; charset=utf8", 
                $this->user, $this->password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(Exception $e) {
            print_r(json_encode([
                'status' => false,
                'message' => "Erreur: Connexion à la base de données non établie !".$e->getMessage()
            ], JSON_FORCE_OBJECT));
        }
    }
}

class Membres extends Database {
    public function __construct() { }

    public function getAllMembres() {
        return Database::db_connect();
    }
}

$lahatra = new Membres;

print_r($lahatra->getAllMembres());