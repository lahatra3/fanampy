<?php
class ControllerAdd {
    
    public function __construct() {}

    public function membres(string $nom, string $prenoms, string $adresse,
        string $phone1, string $phone2, string $email, string $dateNaissance,
        string $lieuNaissance, string $villeOrigine, string $keypass) {

        if(!empty(trim($nom)) && !empty(trim($prenoms))  && !empty(trim($adresse))
            && !empty(trim($phone1)) && !empty(trim($email)) && !empty(trim($dateNaissance)) 
            && !empty(trim($lieuNaissance)) && !empty(trim($villeOrigine)) 
            && !empty(trim($keypass))) {

            if(preg_match('#^[a-zA-Z0-9._+-]{3, }@[a-z]{2, 7}\.[a-z]{2, 4}#', $email)) {
                if(preg_match('#^(+261 | 0[234])[0-9]{7}#', $phone1)) {
                    $phone2=preg_match('#^(+261 | 0[234])[0-9]{7}#', $phone2) ? $phone2 : '';
                    
                    $verify=[
                        'nom' => strip_tags(trim($nom)),
                        'prenoms' => strip_tags(trim($prenoms)),
                        'email' => strip_tags(trim($email)),
                        'keypass' =>$keypass
                    ];

                    $donnees=[
                        'nom' => strip_tags(trim($nom)),
                        'prenoms' => strip_tags(trim($prenoms)),
                        'adresse' => strip_tags(trim($adresse)),
                        'phone1' => strip_tags(trim($phone1)),
                        'phone2' => strip_tags(trim($phone2)),
                        'email' => strip_tags(trim($email)),
                        'dateNaissance' => strip_tags(trim($dateNaissance)),
                        'lieuNaissance' => strip_tags(trim($lieuNaissance)),
                        'villeOrigine' => strip_tags(trim($villeOrigine)),
                        'keypass' => $keypass
                    ];
                    
                    $add = new Membres;
                    $reponses=$add->addMembres($donnees, $verify);
                    echo $reponses;
                }
                else throw new Exception("Erreur: telephone invalide 😥."); 
            }
            else throw new Exception("Erreur: adresse email invalide 😥.");
        }
        else throw new Exception("Erreur: les données à ajouter sont vides MEMBRES 😥.");
    }

    public function formations(string $nom, string $etablissement, string $descriptions, 
        int $id_membres) {

        if(!empty(trim($nom)) && !empty(trim($id_membres))) {
            $infos=[
                'nom' => strip_tags(trim($nom)),
                'etablissement' => strip_tags(trim($etablissement)),
                'descriptions' => strip_tags(trim($descriptions))
            ];
            $add=new Formations;
            $reponses=$add->addFormations($infos);
            echo $reponses;
        }
    }

    public function fonctions(string $nom, int $id_branches, int $id_membres) {
        if(!empty(trim($nom)) && !empty(trim($id_branches)) && !empty(trim($id_membres))) {
            $infos=[
                'nom' => strip_tags(trim($nom)),
                'id_branches' => strip_tags(trim($id_branches)),
                'id_membres' => strip_tags(trim($id_membres))
            ];
            $add=new Fonctions;
            $reponses=$add->addFonctions($infos);
            echo $reponses;
        }
        else throw new Exception("Erreur: les données à ajouter sont vides FONCTIONS 😥.");
    }
}
