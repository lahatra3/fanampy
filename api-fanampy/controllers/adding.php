<?php
class ControllerAdd {

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
            else throw new Exception("Erreur: un des paramètres sont vides pour ajouter MEMBRES 😥.");
    }
}
