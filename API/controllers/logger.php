<?php
try{
    if(!empty($_POST['identifiant']) && !empty($_POST['keypass'])){
        $userData = new GetPersonnesLogin('fanampy');
        $userData -> setInfoLogin($_POST['identifiant'], $_POST['keypass']);
        $login = new Login('fanampy');
        $donnees = $login -> authentifier($userData -> getInfoLogin());
        if($donnees['TRUE'] == 1){
            if($donnees['active'] == 1){
                $_SESSION['id'] = $donnees['id'];
                echo $donnees['TRUE'];
            }
            else{
                echo "Il paraît que vous n'êtes pas très active dans les activités de l'association.\n
                Veuillez-vous renseigner aux administrateurs 🙏.\n Merci de votre aimable compréhension !😊";
            }
        }
        else{
            echo "Identifiant et/ou mot de passe incorrect 🙏!<br>Veuillez réessayer !😊";
        }
    }
    else throw new Exception("L'identifiant et/ou le mot de passe réçus sont vides...!🙏", 1);   
}
catch(Exception $e){
    $erreurs = [
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ];
    print_r(json_encode($erreurs, JSON_FORCE_OBJECT));
}
unset($userData);
unset($login);
