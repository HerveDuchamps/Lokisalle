<?php
require_once('../inc/init.inc.php');

if(!utilisateurAdmin()){
    header('location:../connexion.php');
}

//ajouter un membre
if($_POST){
    
	debug($_POST);
	debug($_FILES);
    if(isset($_GET['action']) && $_GET['action'] == 'modification'){
        $resultat = $pdo ->prepare("UPDATE membre WHERE pseudo = :pseudo");
    }
    else{
        $resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES(':pseudo', ':nom', ':prenom', ':email', ':civilite', ':statut', NOW() )");
    //STR
    $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
    $resultat -> bindPAram(':email', $_POST['email'], PDO::PARAM_STR);
    $resultat -> bindPAram(':civilite', $_POST['civilite'], PDO::PARAM_STR);  
    $resultat -> bindPAram(':mdp', $_POST['mdp'], PDO::PARAM_STR);
   

    //INT
    $resultat -> bindParam(':statut', $_POST['statut'], PDO::PARAM_INT);
    }
    if($resultat -> execute()){
        $_GET['action'] = 'ajout';
		$last_id = $pdo -> lastInsertId();
		$msg .= '<div class="validation">Le membre N� ' . $last_id . ' a �t� enregistr� avec succ�s !</div>';
    }
    echo $msg;
    
}


//Traitement pour afficher tous les membres :
if(isset($_GET['action']) && $_GET['action'] == 'affichage'){//si une action existe dans l'url et que cette action est 'affichage', alors je fais les traitements pour afficher les membres.
	//Requette pour r�cup�rer tous les infos de tous les membres :
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
		$contenu .= '<td><a href="?action=modification&id=' . $membres['id_membre'] . '"><img src="' . RACINE_SITE . 'img/edit.png"/></a></td>';
		$contenu .= '<td><a href="?action=suppression&id=' . $membres['id_membre'] . '"><img src="' . RACINE_SITE . 'img/delete.png"/></a></td>';
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

 <div class="form"> 
   <form action="" method="post" enctype="multipart/form-data">
        <label>Pseudo</label>
        <input type="text" name="pseudo" placeholder="pseudo"/>
        <label>Mot de passe</label>
        <input type="text" name="mdp" placeholder="mot de passe"/>
        <label>Nom</label>
        <input type="text" name="nom" placeholder="Votre nom"/>
        <label>Prénom</label>
        <input type="text" name="prenom" placeholder="Votre prénom"/>
        <label>Email</label>
        <input type="email" name="email" placeholder="Votre Email"/>
        <label>Civilité</label>
        <select name="civilite">
            <option value="m">Homme</option>
            <option value="f">Femme</option>
        </select>
        <label>Statut</label>
        <select name="statut">
            <option value="1">Admin</option>
            <option value="0">Membre</option>
        </select>
         <input type="submit" name="enregister" value="Enregister"/>
    </form>
</div>

<?php require_once('../inc/footer.inc.php')?>