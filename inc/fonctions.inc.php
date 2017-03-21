<?php
require_once('init.inc.php');

//1ere fonction debug :
function debug($arg){
	echo '<div style="color: white; padding: 10px; background: #' .rand(111111, 999999).'"</div>';
    
    $trace = debug_backtrace();
   echo 'Le debug à été demandé dans le fichier : ' .$trace[0]['file']. ' à la ligne : ' .$trace[0]['line']. '<hr/>';
   
    echo '<pre>';
    print_r($arg);
    echo '</pre>';
    echo '<div>';
};

//vérification si l'utilisateur est connecté
function utilisateurConnecte(){
    if(isset($_SESSION['membre'])){
        return TRUE;
    }
    else{
        return FALSE;
    }
}

//Vérification si l'utilisateur est Admin

function utilisateurAdmin(){
    if( utilisateurConnecte() && $_SESSION['membre']['statut']== 1){
        return TRUE;
    }
    else{
        return FALSE;
    }
}