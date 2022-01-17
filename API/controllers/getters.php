<?php
class PersonnesFanampy{
    private $identifiant = null;

    public function __construct(string $identifiant){
        $this -> identifiant =  $identifiant;
    }

    // ******************* Pour avoir les identités des membres **********************
    public function dataMembres(){
        try{
            $personne = new GetPersonnes('fanampy');
            $id = new GetPersonnesInfos($this -> identifiant);
            echo json_encode($personne -> getMembres($id -> getInfo()), JSON_FORCE_OBJECT); 
        }
        catch(Exception $e){
            $erreurs = [
                'message' => $e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }  
        unset($personne);
        unset($id);
    }

    // ********************* Pour avoir les formations **************************
    public function dataFormations(){
        try{
            $personne = new GetPersonnes('fanampy');
            $id = new GetPersonnesInfos($this -> identifiant);
            echo json_encode($personne -> getFormations($id -> getInfo()), JSON_FORCE_OBJECT);
        }
        catch(Exception $e){
            $erreurs = [
                'message' => $e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
        unset($personne);
        unset($id);
    }

    // ********************** Pour avoir les fonctions ***********************
    public function dataFonctions(){
        try{
            $personne = new GetPersonnes('fanampy');
            $id = new GetPersonnesInfos($this -> identifiant);
            echo json_encode($personne -> getFonctions($id -> getInfo()), JSON_FORCE_OBJECT);
        }
        catch(Exception $e){
            $erreurs = [
                'message' => $e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
    }
}
