<?php
class GetPersonnesInfos{
    private $identifiant = null;

    public function __construct(string $identifiant){
        $this -> identifiant = strip_tags($identifiant);
    }

    public function getInfo(){
        return [
            'identifiant' => $this -> identifiant
        ];
    }
}

class GetPersonnesLogin{
    private $defaultValue = null;
    private $identifiant = null;
    private $keypass = null;
    
    public function __construct(string $name){
        $this -> defaultValue = $name;
    }

    public function setInfoLogin(string $identifiant, string $keypass){
        $this -> identifiant = strip_tags($identifiant);
        $this -> keypass = $keypass;
    }

    public function getInfoLogin(){
        return [
            'identifiant' => $this -> identifiant,
            'keypass' => $this -> keypass
        ];
    }
}

// ******************** THESE CLASSES ARE USED FOR ADDING PERSONNES
class GetPersonnesMembres{
    private $defaultValue = null;
    private $nom = null;
    private $prenoms = null;
    private $adresse = null;
    private $phone1 = null;
    private $phone2 = null;
    private $email = null;
    private $dateNaissance = null;
    private $lieuNaissance = null;
    private $villeOrigine = null;

    public function __construct(string $name){
        $this -> defaultValue = $name;
    }

    public function setInfoMembres(string $nom, string $prenoms, string $adresse, 
            string $phone1, string $phone2, string $email, string $dateNaissance, 
            string $lieuNaissance, string $villeOrigine){
        $this -> nom = ucwords(strip_tags($nom));
        $this -> prenoms = ucwords(strip_tags($prenoms));
        $this -> adresse = strip_tags($adresse);
        $this -> phone1 = strip_tags($phone1); 
        $this -> phone2 = strip_tags($phone2);
        $this -> email = strip_tags($email);
        $this -> dateNaissance = strip_tags($dateNaissance);
        $this -> lieuNaissance = strip_tags($lieuNaissance);
        $this -> villeOrigine = strip_tags($villeOrigine);
    }

    public function getInfoMembres(){
        return [
            'nom' => $this -> nom,
            'prenoms' => $this -> prenoms,
            'adresse' => $this -> adresse,
            'phone1' => $this -> phone1,
            'phone2' => $this -> phone2, 
            'email' => $this -> email,
            'dateNaissance' => $this -> dateNaissance,
            'lieuNaissance' => $this -> lieuNaissance,
            'villeOrigine' => $this -> villeOrigine
        ];
    }

    public function getInfoVerifyMembres(){
        return [
            'email' => $this -> email
        ];
    }
}

class GetPersonnesFormations{
    private $defaultValue = null;
    private $nom = null;
    private $etablissement = null;
    private $descritptions = null;
    private $id_membres = null;

    public function __construct(string $name){
        $this -> defaultValue = null;
    }

    public function setInfoFormations(string $nom, string $etablissement, 
        string $descritptions, int $id_membres){
        $this -> nom = strip_tags($nom);
        $this -> etablissement = strip_tags($etablissement);
        $this -> descriptions = strip_tags($descritptions);
        $this -> id_membres = (int) strip_tags($id_membres);
    }

    public function getInfoFormations(){
        return [
            'nom' => $this -> nom,
            'etablissement' => $this -> etablissement,
            'descriptions' => $this -> descriptions,
            'id_membres' => $this -> id_membres
        ];
    }
}

class GetPersonnesFonctions{
    private $defaultValue = null;
    private $nom = null;
    private $id_branches = null;
    private $id_membres = null;
    
    public function __construct(string $name){
        $this -> defaultValue = $name;
    }

    public function setInfoFonctions(string $nom, int $id_branches, int $id_membres){
        $this -> nom = $nom;
        $this -> id_branches = $id_branches;
        $this -> id_membres = $id_membres;
    }

    public function getInfoFonctions(){
        return [
            'nom' => $this -> nom,
            'id_branches' => $this -> id_branches,
            'id_membres' => $this -> id_membres
        ];
    }
}

// *********************** THESE FOLLOWING CLASS ARE USED FOR UPDATING **************************
class UpdatePersonnesMembres{
    private $identity = null;
    private $adresse = null;
    private $phone1 = null;
    private $phone2 = null;
    private $email = null;

    public function __construct(int $identity){
        $this -> identity = (int) $identity;
    }

    public function setInfoUpdateMembres(string $adresse, string $phone1, 
        string $phone2, string $email){
        $this -> adresse = strip_tags($adresse);
        $this -> phone1 = strip_tags($phone1);
        $this -> phone2 = strip_tags($phone2);
        $this -> email = strip_tags($email);
    }

    public function getInfoUpdateMembres(){
        return [
            'adresse' => $this -> adresse,
            'phone1' => $this -> phone1,
            'phone2' => $this -> phone2,
            'email' => $this -> email,
            'id' => $this -> identity
        ];
    }
}

class UpdatePersonnesFormations{
    private $identity = null;
    private $nom = null;
    private $etablissement = null;
    private $descriptions = null;

    public function __construct(int $nombre){
        $this -> identity = (int) $nombre;
    }

    public function setInfoUpdateFormations(string $nom, string $etablissement, string $descriptions){
        $this -> nom = strip_tags($nom);
        $this -> etablissement = strip_tags($etablissement);
        $this -> descriptions = strip_tags($descriptions);
    }

    public function getInfoUpdateFormations(){
        return [
            'nom' => $this -> nom,
            'etablissement' => $this -> etablissement,
            'descriptions' => $this -> descriptions,
            'id' => $this -> identity
        ];
    }
}

class UpdatePersonnesFonctions{
    private $identity = null;
    private $nom = null;
    private $id_branches = null;

    public function __construct(int $nombre){
        $this -> identity = $nombre;
    }

    public function setInfoUpdateFonctions(string $nom, int $id_branches){
        $this -> nom = strip_tags($nom);
        $this -> id_branches = strip_tags($id_branches);
    }

    public function getInfoUpdateFonctions(){
        return [
            'nom' => $this -> nom,
            'id_branches' => $this -> id_branches,
            'id' => $this -> identity
        ];
    }
}
