<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once('models/models.php');
include_once('controllers/controllers.php');

try{
    if(!empty($_GET['demande'])){
        $parametre = explode('/', filter_var(strip_tags($_GET['demande']).'/'), FILTER_SANITIZE_URL);
        switch($parametre[0]){
            case 'log':
                require_once('./controllers/logger.php');
            break;

            case 'get':
                require_once('./controllers/getters.php');
                if(!empty($parametre[1])){
                    if(!empty($_SESSION['id'])) $getters = new PersonnesFanampy((int)$_SESSION['id']);
                    elseif(!empty(trim($parametre[2])) && preg_match("#^[a-z0-9._-]+@[a-z0-9]{2,7}\.[a-z]{2,4}$#", trim($parametre[2]))){
                        $getters = new PersonnesFanampy($parametre[2]);
                    }
                    else throw new Exception("Email invalide !", 1);
                    switch($parametre[1]){
                        case 'membres':
                            $getters -> dataMembres();
                        break;

                        case 'formations':
                            $getters -> dataFormations();
                        break;

                        case 'fonctions':
                            $getters -> dataFonctions();
                        break;
                        default: throw new Exception("Paramètre invalide...!", 1);
                    }
                    unset($getters);
                }
                else throw new Exception("Erreur 🙏, veuillez préciser les données à prendre !😊", 1);
            break;

            case 'add':
                require_once('./controllers/adding.php');
                if(!empty(trim($parametre[1]))){
                    $adding = new AddingPersonnes('fanampy');
                    switch($parametre[1]){
                        case 'membres':
                            $adding -> addMembres($_POST['nom'], $_POST['prenoms'], $_POST['adresse'],
                        $_POST['phone1'], $_POST['phone2'], $_POST['email'], $_POST['dateNaissance'],
                        $_POST['lieuNaissance'], $_POST['villeOrigine']);
                        break;

                        case 'formations':
                            $adding -> addFormations($_POST['nom'], $_POST['etablissement'],
                                $_POST['descriptions']);
                        break;

                        case 'fonctions':
                            $adding -> addFonctions($_POST['nom'], $_POST['id_branches']);
                        break;
                        default: throw new Exception("Paramètre invalide !", 1);  
                    }
                    unset($adding);
                }
                else throw new Exception("Erreur 🙏, veuillez préciser les données à prendre !😊", 1);
                
            break;

            case 'update':
                require_once('./controllers/setters.php');
                if(!empty(trim($parametre[1]))){
                    $setters =  new UpadateFanampy('fanampy');
                    switch($parametre[1]){
                        case 'membres':
                            $setters -> getUpdateMembres($_POST['adresse'], $_POST['phone1'],
                            $_POST['phone2'], $_POST['email'], $_POST['id']);
                        break;

                        case 'formations':
                            $setters -> getUpdateFormations($_POST['nom'], $_POST['etablissement'],
                            $_POST['descriptions'], $_POST['id']);
                        break;

                        case 'fonctions':
                            $setters -> getUpdateFonctions($_POST['nom'],
                             $_POST['id_branches'], $_POST['id']);
                        break;
                        default: throw new Exception("Paramètre invalide pour la mise à jour !", 1);
                    }
                }
                else throw new Exception("Paramètre invalide !", 1);
            break;
            default: throw new Exception("Paramètre inconnu...!😊", 1);   
        }
    }
    else throw new Exception("Aucune demande n'a été passée en URL ou vous n'êtes pas connectés...!🙏\nVeuillez corriger votre demande...!😊", 1);
    
}
catch(Exception $e){
    $erreurs = [
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ];
    print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
}
