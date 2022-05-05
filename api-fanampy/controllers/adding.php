<?php
class ControllerAdd {
    // ============================== MEMBRES ================================
    public function membres(string $nom, string $prenoms, string $adresse,
        string $phone1, string $phone2, string $email, string $dateNaissance,
        string $lieuNaissance, string $villeOrigine, string $keypass) {

        if(!empty(trim($nom)) && !empty(trim($prenoms))  && !empty(trim($adresse))
            && !empty(trim($phone1)) && !empty(trim($email)) && !empty(trim($dateNaissance)) 
            && !empty(trim($lieuNaissance)) && !empty(trim($villeOrigine)) 
            && !empty(trim($keypass))) {

            if(preg_match('#^[a-zA-Z0-9_+.-]+@[a-z]{2,7}\.[a-z]{2,4}$#', $email)) {
                if(preg_match('#^(\+261|0)3[2-4][0-9]{7}$#', $phone1)) {
                    $phone2=preg_match('#^(\+261|0)3[2-4][0-9]{7}$#', $phone2) ? $phone2 : '';
                    
                    $verify=[
                        'nom' => strip_tags(trim($nom)),
                        'prenoms' => strip_tags(trim($prenoms)),
                        'email' => strip_tags(trim($email)),
                        'phone1' => strip_tags(trim($phone1)),
                        'phone2' => strip_tags(trim($phone2))
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
                    unset($add);
                    echo $reponses;
                }
                else {
                    throw new Exception("Erreur: telephone invalide 😥.");
                    http_response_code(400);
                } 
            }
            else {
                throw new Exception("Erreur: adresse email invalide 😥.");
                http_response_code(400);
            }
        }
        else {
            throw new Exception("Erreur: les données à ajouter sont vides MEMBRES 😥.");
            http_response_code(400);
        }
    }

    public function formations(string $nom, string $etablissement, string $descriptions, string $secret) {

        $jwt = new JWT;
        $token = isValidToken($secret);
        unset($jwt);
        if(!empty($token)) {
            if(!empty(trim($nom))) {
                $infos=[
                    'nom' => strip_tags(trim($nom)),
                    'etablissement' => strip_tags(trim($etablissement)),
                    'descriptions' => strip_tags(trim($descriptions)),
                    'id_membres' => strip_tags(trim($token['id']))
                ];
                $add=new Formations;
                $reponses=$add->addFormations($infos);
                unset($add);
                echo $reponses;
            }
            else {
                throw new Exception("Erreur: les données à ajouter sont vides FORMATIONS 😥.");
                http_response_code(400);
            }
        }
        else {
            throw new Exception("Erreur: token invalide !");
            http_response_code(401);
        }
    }

    public function fonctions(string $nom, int $id_branches, string $secret) {
        $jwt = new JWT;
        $token = isValidToken($secret);
        unset($jwt);
        if(!empty($token)) {
            if(!empty(trim($nom)) && !empty(trim($id_branches)) && !empty(trim($id_membres))) {
                $infos=[
                    'nom' => strip_tags(trim($nom)),
                    'id_branches' => strip_tags(trim($id_branches)),
                    'id_membres' => strip_tags(trim($token['id']))
                ];
                $add=new Fonctions;
                $reponses=$add->addFonctions($infos);
                unset($add);
                echo $reponses;
            }
            else {
                throw new Exception("Erreur: les données à ajouter sont vides FONCTIONS 😥.");
                http_response_code(400);
            }
        }
        else {
            throw new Exception("Erreur: token invalide !");
            http_response_code(401);
        }
    }
}
