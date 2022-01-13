<?php
class PersonnesFanampy{
    private $identification = null;

    public function __construct(int $nombre){
        $this -> identification = (int) $nombre;
    }

    // ******************* Pour avoir les identités des membres **********************
    public function dataMembres(){
        try{
            $personne = new GetPersonnes('fanampy');
            $id = new GetPersonnesId($this -> identification);
            echo json_encode($personne -> getMembres($id -> getInfoId()), JSON_FORCE_OBJECT); 
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
            $id = new GetPersonnesId($this -> identification);
            echo json_encode($personne -> getFormations($id -> getInfoId()), JSON_FORCE_OBJECT);
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
            $id = new GetPersonnesId($this -> identification);
            echo json_encode($personne -> getFonctions($id -> getInfoId()), JSON_FORCE_OBJECT);
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
