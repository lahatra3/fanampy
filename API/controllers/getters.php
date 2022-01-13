<?php
class PersonnesFanampy{
    private $identification = null;

    public function __construct(int $nombre){
        $this -> identification = (int) $nombre;
    }

    public function dataMembres(){
        $personne = new GetPersonnes('fanampy');
        $id = new GetPersonnesId($this -> identification);
        echo json_encode($personne -> getMembres($id -> getInfoId()), JSON_FORCE_OBJECT);   
    }

    public function dataFormations(){
        $personne = new GetPersonnes('fanampy');
        $id = new GetPersonnesId($this -> identification);
        echo json_encode($personne -> getFormations($id -> getInfoId()), JSON_FORCE_OBJECT);
    }

    public function dataFonctions(){
        echo json_encode($personne -> getFonctions($id -> getInfoId()), JSON_FORCE_OBJECT);
    }
}
