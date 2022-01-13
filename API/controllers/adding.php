<?php
class AddingPersonnes{
    private $identification = null;

    public function __construct(int $nombre){
        $this -> identification = $nombre;
    }

    //  ****************** Pour ajouter des membres *******************
    public function addMembres($nom, $prenoms, $adresse, $phone1, $phone2, $email,
        $dateNaissance, $lieuNaissance, $villeOrigine){
        try{
            if(!empty($nom) && !empty($prenoms) && !empty($adresse)
             && !empty($phone1) && !empty($email) && !empty($dateNaissance)
              && !empty($lieuNaissance) && !empty($villeOrigine)){
                $userData = new GetPersonnesMembres('fanampy');
                $userData -> setInfoMembres($nom, $prenoms, $adresse, $phone1, $phone2, $email,
                    $dateNaissance, $lieuNaissance, $villeOrigine);
                $personne = new SetPersonnes('fanampy');
                $donnees = $personne -> verifyMembres($userData -> getInfoVerifyMembres());
                if($donnees['TRUE'] == '1'){
                    echo "Il semblerait que les adresses emails que vous avez données,
                 existe déjà !";
                }
                else{
                    $personne -> setMembres($userData -> getInfoMembres());
                    $donnees = $personne -> verifyMembres($userData -> getInfoVerifyMembres());
                    if($donnees['TRUE'] == '1'){
                        $_SESSION['id'] = $donnees['id'];
                        echo $donnees['TRUE'];
                    }
                    else{
                        echo "Il y a une erreur, vous n'êtes pas encore inscrits !";
                    }
                }
                unset($userData);
                unset($personne);
            }
              else throw new Exception("Les données `membres` sont vides !", 1);
        }
        catch(Exception $e){
            $erreurs = [
                'message' => $e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
    }

    // ********************* Pour ajouter des formations *******************
    public function addFormations($nom, $etablissement, $descriptions, $id_membres){
        try{
            if(!empty($nom) && !empty($etablissement)
                && !empty($id_membres)){
                $userData = new GetPersonnesFormations('fanampy');
                $userData -> setInfoFormations($nom, $etablissement, $descriptions, $id_membres);
                $personne = new SetPersonnes('fanampy');
                $personne -> setFormations($userData -> getInfoFormations());
                unset($userData);
                unset($personne);
            }
            else throw new Exception("Les données `formations` sont vides !", 1);   
        }
        catch(Exception $e){
            $erreurs = [
                'message' => $e -> getMessage(),
                'code' => $e -> getCode()
            ];
            print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
        }
    }

    // ********************** Pour ajouter de fonctions ***********************
    public function addFonctions($nom, $id_branches, $id_membres){
        try{
            if(!empty($nom) && !empty($id_branches) && !empty($id_membres)){
                $userData = new GetPersonnesFonctions('fanampy');
                $userData -> setInfoFonctions($nom, $id_branches, $id_membres);
                $personne = new SetPersonnes('fanampy');
                $personne -> setFonctions($userData -> getInfoFonctions());
                unset($userData);
                unset($personne);
            }
            else throw new Exception("Les données `fonctions` sont vides !", 1);
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
