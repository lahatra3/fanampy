<?php
class ControllerGet {
    public function membresAll() {
        $get=new Membres;
        $reponses=$get->getAllMembres();
        unset($get);
        print_r(json_encode($reponses, JSON_FORCE_OBJECT));
    }

    public function membres(string $secret) {
        $jwt = new JWT;
        $token = $jwt->isValidToken($secret);
        unset($token);
        if(!empty($token)) {
            $infos = [
                'identifiant' => strip_tags(trim($token['id']))
            ];
            $get=new Membres;
            $reponses=$get->getMembres($infos);
            unset($get);
            print_r(json_encode($reponses, JSON_FORCE_OBJECT));
        }
        else {
            throw new Exception("Erreur: token invalide ...!");
            http_response_code(401);
        }
    }
}
