<?php
// ****************** CONNEXION À LA BASE DE DONNÉES ******************
class ConnexionDB{
    private $defaulValue = null;

    private function __construct(){
        $this -> $defaulValue = 'fanampy&lahatra';
    }

    protected function db_connect(){
        try{
            return new PDO('mysql:host=localhost; dbname=fanampy; charset=utf8', 'jitiy', '01Lah_tr*@ro0t/*', 
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(PDOException $e){
            $erreurs = [
                'message' =>"Nous n'avons pas pu connecter à la base de données.".$e -> getMessage(),
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
            if(!empty(ConnexionDB::db_connect())){
                $database = ConnexionDB::db_connect();
                $demande = $database -> prepare('SELECT nom, prenoms, adresse, phone1, phone2, email, 
                    dateNaissance, lieuNaissance, villeOrigine, date_debut, date_fin, active
                FROM membres 
                WHERE id = :identifiant OR email = :identifiant');
                $demande -> execute($donnees);
                $reponse = $demande -> fetchAll(PDO::FETCH_ASSOC);
                return $reponse;
            }
            else throw new Exception("Nous n'avons pas pu obtenir les données dans la table `membres`.", 1);
        }
        catch(PDOException $e){
            $erreurs = [
                'message' => $e -> getMessage(),
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
            $demande = $database -> prepare('SELECT f.id, f.nom, f.etablissement, f.descriptions
                FROM formations f JOIN membres m ON f.id_membres = m.id
                WHERE f.id_membres = :identifiant OR m.email = :identifiant');
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
            $demande = $database -> prepare('SELECT f.id, f.nom, b.nom AS departements
                FROM fonctions f 
                JOIN branches b ON b.id = f.id_branches 
                JOIN membres m ON f.id_membres = m.id
                WHERE f.id_membres = :identifiant OR m.email = :identifiant');
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
            $demande = $database -> prepare('SELECT True, id, active, email 
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
    
    // ********************** POUR VERIFIER SI UNE PERSONNE EST DÉJÀ MEMBRE ***********************
    public function verifyMembres(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('SELECT True, id, email FROM membres
                WHERE email = :email');
            $demande -> execute($donnees);
            $reponse = $demande -> fetchAll(PDO::FETCH_ASSOC);
            return $reponse;
        }
        catch(Exception $e){
            $erreurs = [
                'message' => $e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
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

// ********************* CETTE CLASS EST UTILISÉE POUR LES MISES À JOURS ***********************
class UpdatePersonnes extends ConnexionDB{
    private $defaulValue = null;

    public function __construct(string $name){
        $this -> defaultValue = $name;
    }

    // ********************** MISE À JOUR DES INFORMATIONS DES MEMBRES *********************
    public function updateMembres(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('UPDATE membres 
                SET adresse = :adresse, phone1 = :phone1, phone2 = :phone2, email = :email
                WHERE id = :id');
            $demande -> execute($donnees);
            $database -> commit();
        }
        catch(Exception $e){
            $database -> rollback();
            $erreurs = [
                'message' => "La mise à jours `membres` avait connu une erreur.<br>".$e ->  getMessage(),
                'code' => $e.getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    // ************************ MISE À JOUR DU MOT DE PASSE DES MEMBRES *************************
    public function updateKeypass(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('UPDATE membres
                SET keypass = :keypass
                WHERE email = :email');
            $demande -> execute($donnees);
            $database -> commit();
        }
        catch(PDOException $e){
            $database -> rollback();
            $erreurs = [
                'message' => "La mise à jours du mot de passé s'est avèrée un échec.<br>".$e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    // ********************* MISE À JOUR DES DONNÉES DE FORMATIONS *******************
    public function updateFormations(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('UPDATE formations
                SET nom = :nom, etablissement = :etablissement, descriptions = :descriptions
                WHERE id = :id');
            $demande -> execute($donnees);
            $database -> commit();
        }
        catch(Exception $e){
            $database -> rollback();
            $erreurs = [
                'message' => "La mise à jours `formations` avait connu une erreur.<br>".$e ->  getMessage(),
                'code' => $e.getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
        $database = null;
    }

    // ********************** MISE À JOUR DES DONNÉES FONCTIONS ************************
    public function updateFonctions(array $donnees){
        try{
            $database = ConnexionDB::db_connect();
            $demande = $database -> prepare('UPDATE fonctions
                SET nom = :nom, id_branches = :id_branches 
                WHERE id = :id');
            $demande -> execute($donnees);
            $database -> commit();
        }
        catch(Exception $e){
            $database -> rollback();
            $erreurs = [
                'message' => "La mise à jours `fonctions` avait connu une erreur.<br>".$e ->  getMessage(),
                'code' => $e.getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
        $database = null;
    }
}

class DeletePersonnes extends ConnexionDB{
    private $defaultValue = null;

    public function __construct(string $name){
        $this -> defaultValue = $name;
    }


}
