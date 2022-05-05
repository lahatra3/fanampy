<?php
class ControllerUpdate {

    // ==================================== MEMBRES =================================
    public function membres(string $adresse, string $phone, string $secret) {
        $jwt = new JWT;
        $token = $jwt->isValidToken($secret);
        unset($jwt);
        if(!empty($token)) {
            if(!empty(trim($adresse)) && !empty(trim($phone))) {
                $donnees = [
                    'adresse' => strip_tags(trim($adresse)),
                    'phone1' => strip_tags(trim($phone)),
                    'id' => strip_tags(trim($token['id']))
                ];

                $verify = [
                    'phone1' => strip_tags(trim($phone))
                ];
                $update = new Membres;
                $reponses = $update->updateMembres($donnees, $verify);
                unset($update);
                echo $reponses;
            }
            else {
                throw new Exception("Erreur: un des paramètres est vide !");
                http_response_code(400);
            }
        }
        else {
            throw new Exception("Erreur: token invalide !");
            http_response_code(401);
        }
    }

    public function membresPassword(string $lastKey, string $newKey, string $secret) {
        $jwt = new JWT;
        $token = $jwt->isValidToken($secret);
        unset($jwt);
        if(!empty($token)) {
            if(!empty(trim($lastKey)) && !empty(trim($newKey))) {
                $donnees = [
                    'newKey' => $newKey,
                    'id' => strip_tags(trim($token['id']))
                ];

                $verify = [
                    'id' => strip_tags(trim($token['id'])),
                    'lastKey' => $lastKey
                ];
                $update = new Membres;
                $reponses = $update->updateMembresKeypass($donnees, $verify);
                unset($update);
                echo $reponses;
            }
            else {
                throw new Exception("Erreur: un des paramètres est vide !");
                http_response_code(400);
            }
        }
        else {
            throw new Exception("Erreur: token invalide !");
            http_response_code(401);
        }
    }

    // =================================== FORMATIONS ================================
    public function formations() {

    }
}
