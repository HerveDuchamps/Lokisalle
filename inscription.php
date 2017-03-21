<?php
require_once('inc/init.inc.php');

debug($_POST);

$msg = '';
if($_POST){
    if(!empty($_POST['pseudo'])){
        if(strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) >20 ){
           
		$msg .= '<div class="erreur">Veuillez renseigner un pseudo de 3 à 20 caractères ! </div>'; 
        }
    }
    else{
        $msg .= '<div style="background: red; color: #fff;">Veuillez rentrez  un pseudo !! </div>';
    }
    $msg= '';
    if(empty($msg)){
        $resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
        $resultat ->bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $resultat ->execute();
        if($resultat -> rowCount()>0){
            $msg .= '<div style="background: red; color: #fff;"> Oups !! Ce pseudo n\'est pas disponible </div>'; 
        }    
    
       else{
        //Insertion des données
         
        extract($_POST);
      $mdpcrypt = md5($mdp);
        $resultat = $pdo -> query("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, date_enregistrement) VALUES('$pseudo', '$mdpcrypt', '$nom', '$prenom', '$email', '$civilite', NOW())");
    }
    }

}


//Création de variable pour garder en mémoire les infos saisies :

$pseudo = (isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';	
//je mets dans $pseudo la valeur $_POST['pseudo'] , si $_POST['pseudo'] existe sinon je mets du vide	
	
$nom = (isset($_POST['nom'])) ? $_POST['nom'] : '';		

$prenom = (isset($_POST['prenom'])) ? $_POST['prenom'] : '';		

$email = (isset($_POST['email'])) ? $_POST['email'] : '';		

$civilite = (isset($_POST['civilite'])) ? $_POST['civilite'] : '';



$page= 'Inscription';
require_once('inc/header.inc.php');
?>
<!--Contenu html-->
<?= $msg?>
 <div class="form">   
     <form method="post" action="" >
       
        <input type="text" placeholder="Votre pseudo" name="pseudo" value="<?= $pseudo?>"/>
       
        <input type="text" placeholder="Votre mot de passe" name="mdp"/>
        
        <input type="text" placeholder="Votre nom" name="nom" value="<?= $nom?>"/>
       
        <input type="text" placeholder="Votre prenom" name="prenom" value="<?= $prenom?>"/>
        
        <input type="email" placeholder="Votre email" name="email" value="<?= $email?>"/>
        <select name="civilite">
        <option value="m<?= ($civilite == 'm')? 'selected' : '' ?>">Homme</option>    
        <option value="f<?=($civilite == 'f')? 'selected' : '' ?>">Femme</option>
        </select>
        <input type="submit" value="Inscription">
       

    </form>
</div>
<?php
require_once('inc/footer.inc.php');?>