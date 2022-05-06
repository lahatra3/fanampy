<?php
class ControllerGet {
    // ================================= MEMBRES ===================================
    public function membresAll() {
        $get = new Membre;
        $resultats = $get->getAllMembres();
        unset($get);
        print_r(json_encode($resultats)); 
    }

    public function membres(int $id) {

    }
}
