<?php
require_once('inc/init.inc.php');

// Traitement pour la déconnexion
// connexion.php?action=deconnexion
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
	unset($_SESSION['membre']);
}

// Redirection si user est connecté
if(utilisateurConnecte()){
	header('location:index.php');
}

// Traitement de la connexion : 
if($_POST){
	// - Vérifier si le pseudo existe
	if(!empty($_POST['pseudo'])){
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
		$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
		$resultat -> execute();
		
		if($resultat -> rowCount() > 0){ // Le pseudo existe bien dans la BDD
			// - Vérifier si le MDP correspond au MDP en BDD
			$membre = $resultat -> fetch(PDO::FETCH_ASSOC);
			debug($membre);
			
			if($membre['mdp'] == md5($_POST['mdp'])){ // Le MDP en BDD est le même que le MDP fourni dans le formulaire. Je peux connecter l'utilisateur !
			
				// - Mettre toutes les infos de user dans la session
				foreach($membre as $indice => $valeur){
					if($indice != 'mdp'){
						$_SESSION['membre'][$indice] = $valeur;
					}
				}
				//debug($_SESSION);
				
				// - Redirection vers index.php
				header('location:index.php');
			}
			else{
				$msg .= '<div class="erreur">Erreur de MDP !</div>';
			}
		}
		else{
			$msg .= '<div class="erreur">Erreur de pseudo !</div>';
		}
	}
	else{
		$msg .= '<div class="erreur">Veuillez renseigner un pseudo !</div>'; 
	}
}

$page = 'Connexion';
require_once('inc/header.inc.php');
?>
<!-- Contenu HTML -->
<h1>Connexion</h1>
<?= $msg ?>
<form action="" method="post">
	<label>Pseudo :</label><br/>
	<input type="text" name="pseudo"/><br/><br/>

	<label>Mot de passe :</label><br/>
	<input type="text" name="mdp" /><br/><br/>
	
	<input type="submit" value="Connexion" name="connexion"/>
	
</form>
<?php
require_once('inc/footer.inc.php');
?>







