<?php
class AddingPersonnes{
    private $defaultValue = null;

    public function __construct(string $name){
        $this -> defaultValue = $name;
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
                    echo "Il semblerait que l'adresse email que vous avez entrés,
                 existe déjà !";
                }
                else{
                    $personne -> setMembres($userData -> getInfoMembres());
                    $donnees = $personne -> verifyMembres($userData -> getInfoVerifyMembres());
                    if($donnees['TRUE'] == '1'){
                        $_SESSION['id'] = $donnees['id'];
                        $_SESSION['email'] = $donnees['email'];
                        echo $donnees['TRUE'];
                    }
                    else{
                        echo "Il y a une erreur, vous n'êtes pas encore inscrits !";
                    }
                }
                unset($userData);
                unset($personne);
                unset($donnees);
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

    // *********************** Pour ajouter des formations ***********************
    public function addFormations($nom, $etablissement, $descriptions){
        try{
            if(!empty($nom) && !empty($etablissement)
                && !empty($_SESSION['id'])){
                $userData = new GetPersonnesFormations('fanampy');
                $userData -> setInfoFormations($nom, $etablissement, $descriptions, $_SESSION['id']);
                $personne = new SetPersonnes('fanampy');
                $personne -> setFormations($userData -> getInfoFormations());
                unset($userData);
                unset($personne);
                echo '1';
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
            if(!empty($nom) && !empty($id_branches) && !empty($_SESSION['id'])){
                $userData = new GetPersonnesFonctions('fanampy');
                $userData -> setInfoFonctions($nom, $id_branches, $_SESSION['id']);
                $personne = new SetPersonnes('fanampy');
                $personne -> setFonctions($userData -> getInfoFonctions());
                unset($userData);
                unset($personne);
                echo '1';
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
