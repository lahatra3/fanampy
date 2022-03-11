<?php
class ControllerLogin {

    public function __construct(string $identifiant, string $password) {
        if(!empty(trim($identifiant)) && !empty(trim($password))) {
            $this->data=[
                'identifiant' => strip_tags($identifiant),
                'keypass' => $password
            ];
        }
        else throw new Exception("Erreur: les paramètres d'enthentifications sont vides 😥.");
    }

    public function apiLogin() {
        $login=new Login;
        $resultats=$login->authentifier($this->data);
        unset($login);
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function sessionLogin() {
        $login=new Login;
        $resultats=$login->authentifier($this->data);
        unset($resultats);
        $_SESSION['TRUE'] = $resultats['TRUE'];
        $_SESSION['id'] = $resultats['id'];
        $_SESSION['email'] = $resultats['email'];
        print_r(json_encode($resultats, JSON_FORCE_OBJECT));
    }

    public function getSession() {
        print_r(json_encode($_SESSION, JSON_FORCE_OBJECT));
    }
}
