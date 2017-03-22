<?php
require_once('../inc/init.inc.php');


// TRAITEMENT POUR SUPPRIMER UN SALLE : 
// Dans un premier temps il faut supprimer la (les) photo(s) de ce produit de notre serveur.
if(isset($_GET['action']) && $_GET['action'] == 'suppression'){ // Si une action de suppression est passée dans l'URL, on va vérifier qu'il y a bien un ID et que cet ID est bien un INTEGER
	if(isset($_GET['id']) && is_numeric($_GET['id'])){ // OK il y a bien un ID qui est un INTEGER
	// Puisqu'il faut supprimer la ou les photo(s) du produit, je dois récupérer toutes les infos du produit.
		$resultat = $pdo -> prepare("SELECT * FROM salle WHERE id_salle = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();
		
		if($resultat -> rowCount() > 0){ // Cela signifie qu'il existe bien une salle avec cet ID. ON vérifie car l'utilisateur peut avoir modifier l'ID dans l'URL...
			$salle = $resultat -> fetch(PDO::FETCH_ASSOC);

			// Pour pouvoir supprimer la photo, il nous faut son emplacement (chemin) exact. 
			$chemin_photo_a_supprimer = RACINE_SERVEUR . RACINE_SITE . 'img/' . utf8_decode($salle['photo']);
			
			// dernière vérification : le fichier existe-t-il, et ce n'est pas la photo par défaut partagée par d'autres salle
			if(file_exists($chemin_photo_a_supprimer) && $salle['photo'] != 'default.jpg'){
				unlink($chemin_photo_a_supprimer); // Supprime le fichier du serveur.
			}
			
			// Maintenant que la photo est supprimée, on va supprimer la salle de la BDD : 
			$resultat = $pdo -> exec("DELETE FROM salle WHERE id_salle = $salle[id_salle]"); 
			
			if($resultat != FALSE){ // Si la requête est un succès
				$_GET['action'] = 'affichage';
				$msg .= '<div class="validation">La salle N°' . $salle['id_salle'] . ' a bien été supprimé !</div>';
			}
		}
		// Ici dans le else, on pourrait faire une redirection vers 404.php
	}
	// Ici dans le else, on pourrait faire une redirection vers 404.php
}




// TRAITEMENT POUR ENREGISTRER/MODIFIER UNE SALLE : 
if($_POST){
	
	debug($_POST);
	debug($_FILES);
	//$_FILES est une superglobale (ARRAY multidimentionnel) qui récupère les infos de chaque fichier "uploadé", Pour chacun, on récupère le nom, le type, l'emplacement temporaire, erreur (1,2,3,4,6,7,8, cf la doc PHP.net), et la taille en octets.
	
	// Vérifications des données
	
	// traitement sur les photos :
	$nom_photo = 'default.jpg';
	
	//Si je suis dans le cadre d'une modification de produit, alors, il doit y avoir un champs photo actuelle dans le formulaire. Donc $nom_photo va prendre la valeur de la photo actuelle pour la ré-enregistrer telle qu'elle est ! 
	if(isset($_POST['photo_actuelle'])){
		$nom_photo = $_POST['photo_actuelle'];
	}	
	if(!empty($_FILES['photo']['name'])){ // Si l'utilisateur nous a transmis une photo
		if($_FILES['photo']['error'] == 0){	
			$ext = explode('/', $_FILES['photo']['type']);
			$ext_autorisee = array('jpeg', 'gif', 'png');		
			if(in_array($ext[1], $ext_autorisee)){
				if($_FILES['photo']['size'] < 1000000){
			
					// On renomme la photo pour éviter les doublons dans le dossier photo/
					$nom_photo = $_POST['salle'] . '_' . $_FILES['photo']['name'];
					$nom_photo = utf8_decode($nom_photo);
					// enregistrer la photo dans le dossier photo/
					$chemin_photo = RACINE_SERVEUR . RACINE_SITE . 'img/' . $nom_photo;
					copy($_FILES['photo']['tmp_name'], $chemin_photo); // La fonction copy() permet de copier/coller un fichier d'un emplacement à un autre. Elle attend 2 args : 1/ L'emplacement du fichier à copier et 2/ l'emplacement définitif de la copie. 
					
				}
				else{
					$msg .= '<div class="error">Taille maximum des images : 1Mo</div>';
				}
			}
			else{
				$msg .= '<div class="error">Extensions autorisées : PNG, JPG, JPEG, GIF</div>';
			}
		}
		else{
			$msg .= '<div class="error">Veuillez sélectionner une nouvelle image</div>';
		}
	}
	
	// Je sors de cette condition avec $nom_photo ayant soit la valeur 'default.jpg', soit le nom de la photo chargée par User auquel nous avons ajouté la référence, soit la photo du produit que je suis en train de modifier. 
	
	//Enregistrement dans la BDD
	
	if(isset($_GET['action']) && $_GET['action'] == 'modification'){
		
		
		$resultat = $pdo -> prepare("UPDATE salle SET titre = :titre, description = :description, photo = :photo, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie  WHERE id_salle = :id_salle ");
		
		$resultat -> bindParam(':id_salle', $_POST['id_salle'], PDO::PARAM_INT);
		
	//STR
	$resultat -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
	$resultat -> bindParam(':description', $_POST['description'], PDO::PARAM_STR);	
	$resultat -> bindParam(':photo', $nom_photo , PDO::PARAM_STR);
	$resultat -> bindParam(':pays', $_POST['pays'], PDO::PARAM_STR);
	$resultat -> bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
	$resultat -> bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
	$resultat -> bindParam(':categorie', $_POST['categorie'], PDO::PARAM_STR);
	
	$nom_photo =  utf8_encode($nom_photo);
	
	//INT
	$resultat -> bindParam(':cp', $_POST['codepostal'], PDO::PARAM_INT);
	$resultat -> bindParam(':capacite', $_POST['capacite'], PDO::PARAM_INT);
		

	}
	elseif(isset($_GET['action']) && $_GET['action'] == 'ajout'){
		$resultat = $pdo -> prepare("INSERT INTO salle (titre, description,photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
	}
	
	//STR
	$resultat -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
	$resultat -> bindParam(':description', $_POST['description'], PDO::PARAM_STR);	
	$resultat -> bindParam(':photo', $nom_photo , PDO::PARAM_STR);
	$resultat -> bindParam(':pays', $_POST['pays'], PDO::PARAM_STR);
	$resultat -> bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
	$resultat -> bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
	$resultat -> bindParam(':categorie', $_POST['categorie'], PDO::PARAM_STR);
	
	$nom_photo =  utf8_encode($nom_photo);
	
	//INT
	$resultat -> bindParam(':cp', $_POST['codepostal'], PDO::PARAM_INT);
	$resultat -> bindParam(':capacite', $_POST['capacite'], PDO::PARAM_INT);
		
	$resultat -> execute();
		//$_GET['action'] = 'affichage';
		$last_id = $pdo-> lastInsertId();
		$msg .= '<div class="validation">La salle N°' . $last_id . ' a été enregistré avec succès !</div>';

	
	// Pourquoi effectuer "-> execute()" dans le if ?
	// Après avoir effectué ma requête je souhaite lancer des traitements (affichage du message, redirection etc...). Le problème est que ces traitements se lanceront quoi qu'il arrive (même si la requête echoue).
	// En effectuant ces traitements dans le if($resultat -> execute()) cela garantit qu'ils ne s'effectueront qu'en cas de succès de la requête. En cas d'echec rien ne se passe !
}

echo $msg;
// Traitement pour afficher tous les salles : 
if(isset($_GET['action']) && $_GET['action'] == 'affichage'){ // Si une action existe dans l'url et que cette action est 'affichage', alors je fais les traitements pour afficher les produits. 
	// REQUETE pour récupérer tous les infos de tous les salles :
	$resultat = $pdo -> query("SELECT * FROM salle");
	
	$contenu .= '<table border="1">';
	$contenu .= '<tr>';
	for($i = 0; $i < $resultat -> columnCount(); $i++){
		$colonne = $resultat -> getColumnMeta($i); 
		$contenu .= '<th>' . $colonne['name'] . '</th>';
	}
	$contenu .= '<th colspan="2">Actions</th>';
	$contenu .= '</tr>';

	while($salles = $resultat -> fetch(PDO::FETCH_ASSOC)){
		$contenu .= '<tr>'; 
		foreach($salles as $indice => $valeur){
			if($indice == 'photo'){
				$contenu .= '<td><img src="' . RACINE_SITE . 'img/' . $valeur . '" height="80"/></td>';
			}
			else{
				$contenu .= '<td>' . $valeur . '</td>';
			}	
		}
		$contenu .= '<td><a href="?action=modification&id=' . $salles['id_salle'] . '"><img src="' . RACINE_SITE . 'img/edit.png"/></a></td>';
		$contenu .= '<td><a href="?action=suppression&id=' . $salles['id_salle'] . '"><img src="' . RACINE_SITE . 'img/delete.png"/></a></td>';
		$contenu .= '</tr>'; 
	}
	$contenu .= '</table>'; 
}

$page='Gestion_des_salles';
require_once('../inc/header.inc.php');
?>
<!-- Contenu HTML -->
<h1>Gestion des salles</h1>
<ul>
	<li><a href="?action=affichage">Afficher les salles</a></li>
	<li><a href="?action=ajout">Ajouter une salle</a></li>
</ul><hr/>
<?= $msg ?>
<?= $contenu ?>

<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')) : ?>

<?php
if(isset($_GET['id']) && is_numeric($_GET['id'])){ // Si j'ai un ID dans l'URL, et que cet ID est bien une valeur numérique, je récupère les infos du produit correspondant dans la BDD :
	$resultat = $pdo -> prepare("SELECT * FROM salle WHERE id_salle = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();
	
	if($resultat -> rowCount() > 0){
		$salle_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
	}
}// fin du if !!!! 

$titre = (isset($salle_actuel)) ? $salle_actuel['titre'] : '';
$description = (isset($salle_actuel)) ? $salle_actuel['description'] : '';
$photo = (isset($salle_actuel)) ? $salle_actuel['photo'] : '';
$pays = (isset($salle_actuel)) ? $salle_actuel['pays'] : '';
$ville = (isset($salle_actuel)) ? $salle_actuel['ville'] : '';
$adresse = (isset($salle_actuel)) ? $salle_actuel['adresse'] : '';
$cp = (isset($salle_actuel)) ? $salle_actuel['cp'] : '';
$capacite = (isset($salle_actuel)) ? $salle_actuel['capacite'] : '';
$categorie = (isset($salle_actuel)) ?  $salle_actuel['categorie'] : '';

$id_salle = (isset($salle_actuel)) ? $salle_actuel['id_salle'] : '';
$action = (isset($salle_actuel)) ? 'Modifier' : 'Ajouter';


?>
<h3><?= $action ?> une salle</h3>

	<form style="background: white; padding: 40px; margin: 40px; " action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="id_salle" value="<?= $id_salle?>">
		<label>Titre : </label><br/>
		<input type="text" name="titre" placeholder="Titre de la salle"value="<?= $titre ?>"/><br/><br/>
		
		<label>Description :</label><br/>
		<textarea name="description" placeholder="Description de la salle"><?= $description?></textarea><br/><br/>
		
		<label>Photo  : </label><br/>
		<input type="file" name="photo" /><br/><br/>

		<label>Capacité : </label>
		<select name="capacite" value="<?= $capacite?>" >
			<option value="30">30</option>
			<option value="5">5</option>
			<option value="2" >2</option>
		</select><br/><br/>

		<label>Catégorie : </label>
		<select name="categorie" value="<?= $categorie?>">
			<option value="reunion">Réunion</option>
			<option value="bureau">Bureau</option>
			<option value="formation" >Formation</option>
		</select><br/><br/>

		<label>Pays : </label><br/>
		<select id="pays" name="pays" value="<?= $pays?>" >
			<option value="France">France</option>
			<option value="it">Italie</option>
			<option value="es" >Espagne</option>		
		</select><br /><br />
		
		<label>Ville : </label><br/>
		<input type="text" name="ville" value="<?= $ville?>"><br/><br/>
		
		
		<label>Adresse : </label><br/>
		<textarea name="adresse"><?= $adresse?></textarea><br/><br/>

		<label>Code Postal : </label><br/>
		<input type="text" name="codepostal" value="<?= $adresse?>"/><br/><br/>
		<input type="submit" value="Enregistrer"/>
	</form>
<?php endif; ?> 

<?php
require_once('../inc/footer.inc.php');
?>