<?php
// ****************** CONNEXION À LA BASE DE DONNÉES ******************
class ConnexionDB{
    private $defaulValue = null;

    private function __construct(){
        $this -> $defaulValue = 'fanampy&lahatra';
    }

    protected function db_connect(){
        try{
            return new PDO('mysql:host=localhost; dbname=fanampy; charset=utf8', 'root', '', 
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(PDOException $e){
            $erreurs = [
                'message' => "Nous n'avons pas pu connecter à la base de données.<br>".$e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
    }
}

// ********* POUR OBTENIR LES DONNÉES D'UNE PERSONNE DANS FANAMPY *************
class GetPersonnes extends ConnexionDB{
    private $defaulValue = null;

    public function __construct(string $name){
        $this -> defaultValue = $name;
    }

    // ********************* POUR PRENDRE LES DONNÉES D'UNE PERSONNE *******************
    public function getMembres(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('SELECT nom, prenoms, adresse, phone1, phone2, email, 
                    dateNaissance, lieuNaissance, villeOrigine, date_debut, date_fin, active
                FROM membres 
                WHERE id = :id');
            $demande -> execute($donnees);
            $reponse = $demande -> fetchAll(PDO::FETCH_ASSOC);
            return $reponse;
        }
        catch(PDOException $e){
            $erreurs = [
                'message' => "Nous n'avons pas pu obtenir les données dans la table `membres`.<br>".$e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    // ******************** POUR PRENDRE LES FORMATIONS D'UNE PERSONNE **********************
    public function getFormations(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('SELECT nom, etablissement, descriptions
                FROM formations 
                WHERE id_membre = :id');
            $demande -> execute($donnees);
            $reponse = $demande -> fetchAll(PDO::FETCH_ASSOC);
            return $reponse;
        }
        catch(PDOException $e){
            $erreurs = [
                'message' => "Nous n'avons pas pu obtenir les données dans la table `formations`.<br>".$e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    // ******************* POUR PRENDRE LA FONCTION D'UNE PERSONNE ***********************
    public function getFonctions(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('SELECT f.nom, b.nom AS departements
                FROM fonctions f JOIN branches b ON b.id = f.id_branches 
                WHERE f.id_membres = :id');
            $demande -> execute($donnees);
            $reponse = $demande -> fetchAll(PDO::FETCH_ASSOC);
            return $reponse;
        }
        catch(PDOException $e){
            $erreurs = [
                'message' => "Nous n'avons pas pu obtenir les données dans la table `fonctions`.<br>".$e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
    }
}

// **************** POUR S'AUTHENTIFIER DANS L'APPLICATION FANAMPY **************
class Login extends ConnexionDB{
    private $defaulValue = null;

    public function __construct(string $name){
        $this -> defaultValue = $name;
    }

    public function authentifier(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('SELECT True, id, active 
                FROM membres
                WHERE (email = :identifiant OR phone1 = :identifiant OR phone2 = :identifiant)
                    AND keyword = SHA2(:keypass, 256)');
            $demande -> execute($donnees);
            $reponse = $demande -> fetch(PDO::FETCH_ASSOC);
            return $reponse;
        }
        catch(PDOException $e){
            $erreurs = [
                'message' => "Nous n'avons pas pu collecter les données privées.<br>".$e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
    }
}

// ******************** INSERTION *************** INSERTION ******************
class SetPersonnes extends ConnexionDB{
    private $defaulValue = null;

    public function __construct(string $name){
        $this -> defaultValue = $name;
    }

    // **************** POUR INSERER LES DONNÉES D'UNE PERSONNE MEMBRE ***************
    public function setMembres(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('INSERT INTO membres(nom, prenoms, adresse, 
                    phone1, phone2, email, dateNaissance, lieuNaissance, villeOrigine,
                    date_debut, active)
                VALUES(:nom, :prenoms, :adresse, :phone1, :phone2, :email, :dateNaissance,
                    :lieuNaissance, :villeOrigine, NOW(), 1');
            $demande -> execute($donnees);
            $database -> commit();
        }
        catch(PDOException $e){
            $database -> rollback();
            $erreurs = [
                'message' => "Nous n'avons pas pu inserer vos données dans la table `membres`.<br>".$e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
        $database = null;
    }
    
    // *************** POUR INSERER DES FORMATIONS ********************
    public function setFormations(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('INSERT INTO formations(nom, etablissement,
                    descriptions, id_membres)
                VALUES(:nom, :etablissement, :descriptions, :id_membres)');
            $demande -> execute($donnees);
            $database -> commit();
        }
        catch(PDOException $e){
            $database -> rollback();
            $erreurs = [
                'message' => "Nous n'avons pas pu inserer vos données dans la table `formations`.<br>".$e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    // ***************** POUR INSERER DES FONCTIONS *******************
    public function setFonctions(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('INSERT INTO fonctions(nom, id_branches, id_membres)
                VALUES(:nom, :id_branches, :id_membres)');
            $demande -> execute($donnees);
            $database -> commit();
        }
        catch(PDOException $e){
            $erreurs = [
                'message' => "Il y a une erreur dans l'insertion de fonctions.<br>".$e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJET));
        }
        $database = null;
    }
}
