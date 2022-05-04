<?php
class ControllerGet {
    // ============================== MEMBRE ===============================
    public function membresAll() {
        $get=new Membres;
        $reponses=$get->getAllMembres();
        unset($get);
        print_r(json_encode($reponses));
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
            print_r(json_encode($reponses));
        }
        else {
            throw new Exception("Erreur: token invalide ...!");
            http_response_code(401);
        }
    }

    // =============================== FORMATIONS ============================
    public function formationsAll(string $secret) {
        $jwt = new JWT;
        $token = isValidToken($secret);
        unset($jwt);
        if(!empty($token)) {
            $get = new Formations;
            $reponses = $get->getAllFormations();
            unset($get);
            print_r(json_encode($reponses));

        }
        else {
            throw new Exception("Erreur: token invalide !");
            http_response_code(401);
        }
    }

    public function formations(string $secret) {
        $jwt = new JWT;
        $token = isValidToken($secret);
        unset($jwt);
        if(!empty($token)) {
            $infos = [
                'identifiant' => strip_tags(trim($token['id']))
            ];
            $get = new Formations;
            $reponses = $get->getFormations($infos);
            unset($get);
            print_r(json_encode($reponses));
        }
        else {
            throw new Exception("Erreur: token invalide !");
            http_response_code(401);
        }
    }
}
