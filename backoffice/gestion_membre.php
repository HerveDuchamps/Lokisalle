<?php
require_once('../inc/init.inc.php');

if(!utilisateurAdmin()){
    header('location:../connexion.php');
}

//Traitement pour supprimer un membre
if(isset($_GET['action']) && $_GET['action']== 'suppression'){
    if(isset($_GET['id_membre']) && is_numeric($_GET['id_membre'])){
        $resultat = $pdo -> prepare("DELETE FROM membre WHERE id_membre = :id_membre");
        $resultat -> bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_INT); 
        $resultat -> execute();
        $membre = $resultat ->fetch(PDO::FETCH_ASSOC);
        echo '<div class="validation">le membre N° '. $membre['id_membre'] .' a été bien supprimer</div>';
    }

}
//ajouter ou modifier un membre
if($_POST){
    
	
    if(isset($_GET['action']) && $_GET['action'] == 'modification'){
        $resultat = $pdo ->prepare("UPDATE membre SET pseudo=:pseudo, mdp=:mdp, nom=:nom, prenom=:prenom, email=:email, civilite=:civilite, statut=:statut, date_enregistrement=NOW() WHERE id_membre = :id_membre");
        $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
        $resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $resultat -> bindPAram(':email', $_POST['email'], PDO::PARAM_STR);
        $resultat -> bindPAram(':civilite', $_POST['civilite'], PDO::PARAM_STR);  
        $resultat -> bindPAram(':mdp', $_POST['mdp'], PDO::PARAM_STR);
            //INT
        $resultat -> bindParam(':statut', $_POST['statut'], PDO::PARAM_INT);
        $resultat -> bindParam(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
        $resultat -> execute();
    }
    else{//vérification sur le pseudo
        $verif_pseudo = $pdo ->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
        $verif_pseudo -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR );
        $verif_pseudo -> execute();
        if($verif_pseudo->rowCount() > 0)
        {
            $msg .= '<div class="error">Attention le pseudo est déjà pris </div>';
        }
        else{
            $mdp = md5($_POST['mdp']);
           
            $resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES(:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, NOW() )");
            //STR
            $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
            $resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $resultat -> bindPAram(':email', $_POST['email'], PDO::PARAM_STR);
            $resultat -> bindPAram(':civilite', $_POST['civilite'], PDO::PARAM_STR);  
            $resultat -> bindPAram(':mdp', $mdp, PDO::PARAM_STR);
             //INT
            $resultat -> bindParam(':statut', $_POST['statut'], PDO::PARAM_INT);
            $resultat -> execute();
            $last_id = $pdo -> lastInsertId();
             $msg .= '<div class="validation">Le membre N° ' . $last_id . ' a été enregistré avec succés !</div>';
            }
          
        }
        
   

   
    echo $msg;
    
}


//Traitement pour afficher tous les membres :
if(isset($_GET['action']) && $_GET['action'] == 'affichage'){//si une action existe dans l'url et que cette action est 'affichage', alors je fais les traitements pour afficher les membres.
	//Requette pour récupérer tous les infos de tous les membres :
	$resultat = $pdo -> query("SELECT * FROM membre");
	
$contenu .= '<table border="1">';
	$contenu .= '<tr>';
		for($i = 0; $i < $resultat -> columnCount(); $i++){
			$colonne = $resultat -> getColumnMeta($i); //Cette 
	$contenu .= '<th>' .$colonne['name'] . '</th>';
		}
	$contenu .= '<th colspan="2">Actions</th>';	
		
	$contenu .= '</tr>';
		while($membres = $resultat -> fetch(PDO::FETCH_ASSOC)){
	$contenu .= '<tr>';
	
		foreach($membres as $indice =>$valeur){		
		     //$mdp = md5($_POST['mdp']);	
			$contenu .= '<td>' . $valeur . '</td>';
	
		}
		$contenu .= '<td><a href="?action=modification&id_membre=' . $membres['id_membre'] . '"><img src="' . RACINE_SITE . 'img/edit.png"/></a></td>';
		$contenu .= '<td><a href="?action=suppression&id_membre=' . $membres['id_membre'] . '"><img src="' . RACINE_SITE . 'img/delete.png"/></a></td>';
		$contenu .= '</tr>';
	}
	$contenu .= '</table>';
}
$page='Gestion membre';
require_once('../inc/header.inc.php');
?>

<!--Contenu html-->

<ul>
    <li><a href="?action=affichage">Afficher les membres</a></li>
    <li><a href="?action=ajout">Ajouter des membres</a></li>
</ul>
<hr/>
<?= $contenu?>

<?php
if(isset($_GET['id_membre']) && is_numeric($_GET['id_membre'])){
	$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
	$resultat -> bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
	$resultat -> execute();
	if($resultat -> rowCount() > 0){
		$membre_actuel = $resultat -> fetch(PDO::FETCH_ASSOC); 
	 }
}// fin du if !!
//garder les infos lors de la modif
   
$pseudo = (isset($membre_actuel)) ? $membre_actuel['pseudo'] : '';
$nom = (isset($membre_actuel))? $membre_actuel['nom'] : '';
$prenom = (isset($membre_actuel)) ? $membre_actuel['prenom'] : '';

$mdp = (isset($membre_actuel)) ? $membre_actuel['mdp'] : '';
$email = (isset($membre_actuel))? $membre_actuel['email'] : '';
$civilite = (isset($membre_actuel)) ? $membre_actuel['civilite'] : '';
$statut = (isset($membre_actuel))? $membre_actuel['statut'] : '';
$id_membre = (isset($membre_actuel))? $membre_actuel['id_membre'] : '';
$action = (isset($membre_actuel))? 'Modifier' :  'Ajouter';
?>

 <div class="form"> 
   <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" value="<?= $id_membre?>" name="id_membre">
        <label>Pseudo</label>
        <input type="text" name="pseudo" placeholder="pseudo" value="<?= $pseudo ?>"/>
        <label>Mot de passe</label>
        <input type="text" name="mdp" placeholder="mot de passe" value="<?= $mdp ?>"/>
        <label>Nom</label>
        <input type="text" name="nom" placeholder="Votre nom" value="<?= $nom ?>"/>
        <label>Prénom</label>
        <input type="text" name="prenom" placeholder="Votre prénom" value="<?= $prenom ?>"/>
        <label>Email</label>
        <input type="email" name="email" placeholder="Votre Email" value="<?= $email ?>"/>
        <label>Civilité</label>
        <select name="civilite" value="<?= $civilite ?>">
            <option value="m">Homme</option>
            <option value="f">Femme</option>
        </select>
        <label>Statut</label>
        <select name="statut" value="<?= $statut ?>">
            <option value="1">Admin</option>
            <option value="0">Membre</option>
        </select>
         <input type="submit" name="enregister" value="Enregister"/>
    </form>
</div>

<?php require_once('../inc/footer.inc.php')?>