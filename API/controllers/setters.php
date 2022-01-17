<?php
class UpadateFanampy{
    private $defaultValue = null;
    public function __construct(string $name){
        $this -> defaultValue = $name;
    }

    // ***************** FOR UPDATING MEMBRES *****************
    public function getUpdateMembres(string $adresse, string $phone1,
        string $phone2, string $email, int $id){
        try{
            if(!empty($id)){
                $userData = new UpdatePersonnesMembres($id);
                $userData -> setInfoUpdateMembres($adresse, $phone1, $phone2, $email);
                $personne = new UpdatePersonnes('fanampy');
                $personne -> updateMembres($userData -> getInfoUpdateMembres());
                unset($userData);
                unset($personne);
                echo '1';
            }
            else throw new Exception("Aucun numéro d'identification reçu", 1);
        }
        catch(Exception $e){
            $erreurs = [
                'message' => $e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
    }

    // ********************** FOR UPDATING FORMATIONS ********************
    public function getUpdateFormations(string $nom, string $etablissement,
         string $descriptions, int $id){
        try{
            if(!empty($id)){
                $userData = new UpdatePersonnesFormations($id);
                $userData -> setInfoUpdateFormations($nom, $etablissement, $descriptions);
                $personne = new UpdatePersonnes('fanampy');
                $personne -> updateFormations($userData -> getInfoUpdateFormations());
                unset($userData);
                unset($personne);
                echo '1';
            }
            else throw new Exception("Aucun numéro d'identification reçu", 1);
        }
        catch(Exception $e){
            $erreurs = [
                'message' => $e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
    }

    // ********************* FOR UPDATING FONCTIONS *********************
    public function getUpdateFonctions(string $nom, int $id_branches, int $id){
        try{
            if(!empty($id)){
                $userData = new UpdatePersonnesFonctions($id);
                $userData -> setInfoUpdateFonctions($nom, $id_branches);
                $personne = new UpdatePersonnes('fanampy');
                $personne -> updateFonctions($userData -> getInfoUpdateFonctions());
                unset($userData);
                unset($personne);
                echo '1';
            }
            else throw new Exception("Aucun numéro d'identification reçu", 1);
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
