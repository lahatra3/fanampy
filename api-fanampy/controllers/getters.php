<?php
class ControllerGet {

    public function __construct(string $identifiant) {
        $this->data=[
            'identifiant' => strip_tags(trim($identifiant))
        ];
    }

    public function membresAll() {
        $get=new Membres;
        $reponses=$get->getAllMembres();
        unset($get);
        print_r(json_encode($reponses, JSON_FORCE_OBJECT));
    }

    public function membres() {
        $get=new Membres;
        $reponses=$get->getMembres($this->data);
        unset($get);
        print_r(json_encode($reponses, JSON_FORCE_OBJECT));
    }
}
