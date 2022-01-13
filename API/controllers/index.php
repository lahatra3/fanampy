<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once('../models/models.php');
include_once('controllers.php');

try{
    if(!empty(trim($_GET['demande']))){
        $parametre = explode('/', filter_var(strip_tags($_GET['demande'])), FILTER_SANITIZE_URL);
        switch($parametre[0]){
            case 'log':
                require_once('./logger.php');
            break;

            case 'get':
                require_once('./getters.php');
            break;

            case 'add':
                require_once('./adding.php');
            break;

            case 'set':
                require_once('./setters.php');
            break;
            default: throw new Exception("Paramètre inconnu...!", 1);   
        }
    }
    else throw new Exception("Aucune demande n'a été passée en URL...!\nVeuillez corriger votre demande...!", 1);
    
}
catch(Exception $e){
    $erreurs = [
        'message' => $e.getMessage(),
        'code' => $e.getCode()
    ];
    print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
}
