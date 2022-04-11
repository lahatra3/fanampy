<?php
class ControllerLogin {

    public function __construct(string $identifiant, string $keypass) {
        if(!empty(trim($identifiant)) && !empty(trim($keypass))) {
            $this->data=[
                'identifiant' => strip_tags($identifiant),
                'keypass' => $keypass
            ];
        }
        else throw new Exception("Erreur: les paramètres d'enthentifications sont vides 😥.");
    }

    public function apiLogin(string $identifiant, string $password) {
        $infos = [
            'identifiant' => strip_tags($identifiant),
            'keypass' => $password
        ];

        $login = new Login;
        $resultats = $login->authentifier($infos);
        unset($login);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function tokenLogin() {
        $login=new Login;
        $resultats=$login->authentifier($this->data);
        unset($resultats);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }
}
