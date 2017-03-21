<?php
require_once('../inc/init.inc.php');

//récupérer les informations depuis la table salle
$liste_salle = $pdo-> query("SELECT * FROM salle");

//Affichage des produits dans une table:


$resultat = $pdo -> query("SELECT * FROM produit");
	
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
		$contenu .= '<td><a href="?action=modification&id_produit=' . $membres['id_produit'] . '"><img src="' . RACINE_SITE . 'img/edit.png"/></a></td>';
		$contenu .= '<td><a href="?action=suppression&id_produit=' . $membres['id_produit'] . '"><img src="' . RACINE_SITE . 'img/delete.png"/></a></td>';
		$contenu .= '</tr>';
	}
	$contenu .= '</table>';


//Enregistrer les produits dans la BDD:
if($_POST){
    extract($_POST);
    $info = $pdo -> query("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix) VALUES ('$id_salle', '$date_a', '$date_d', '$tarif')");

    echo '<div class="validation">le produit a bien été enregistrer dans la BDD</div>';

}





$page ="";
require_once('../inc/header.inc.php');
?>
<!--Contenu HTML-->
<?= $page ?>
<?= $contenu?>
<div class="form">
    <form method="post" action="" enctype="multipart/form-data">
        <label>Date d'arrivée</label>
        <input type="text" name="date_a"/>

        <label>date de départ </label>
        <input type="text" name="date_d"/>

        <label>Salle</label>
        <select name="id_salle"/>
            <?php
            while($salle = $liste_salle->fetch(PDO::FETCH_ASSOC)){
                echo '<option value="'. $salle['id_salle']. '">'.$salle['id_salle'].'-'.$salle['titre'].'-'.$salle['adresse'].'-'.$salle['cp'].'-'.$salle['ville'].'-'.$salle['capacite'].'</option>';
            }
            ?>
        </select>

        <label>Tarif</label>
        <input type="text" name="tarif"/>

        <input type="submit" value="Enregister"/>


    </form>
</div>













<?php
require_once('../inc/footer.inc.php');
 ?>