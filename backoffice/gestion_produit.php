<?php
require_once('../inc/init.inc.php');

//récupérer les informations depuis la table salle
$liste_salle = $pdo-> query("SELECT * FROM salle");

//Affichage des produits dans une table:


$resultat = $pdo -> query("SELECT s.titre, s.photo, p.*
FROM salle s, produit p
WHERE s.id_salle = p.id_salle");
	
$contenu .= '<table border="1">';
	$contenu .= '<tr>';
	
    $contenu .= '<th>id_produit</th>';
    $contenu .= '<th>date_arrivee</th>';
    $contenu .= '<th>date_depart</th>';
    $contenu .= '<th>id_salle</th>';
    $contenu .= '<th>prix</th>';
    $contenu .= '<th>etat</th>';
	$contenu .= '<th colspan="2">Actions</th>';	
		
	$contenu .= '</tr>';
		while($membres = $resultat -> fetch(PDO::FETCH_ASSOC)){
	$contenu .= '<tr>';
	

        $contenu .= '<td>' . $membres['id_produit'] . '</td>';
        $contenu .= '<td>' . $membres['date_arrivee'] . '</td>';
        $contenu .= '<td>' . $membres['date_depart'] . '</td>';
        $contenu .= '<td>' . $membres['id_salle'] . ' - ' . $membres['titre'] . '<br /><img src="' . RACINE_SITE . 'img/' . $membres['photo'] . '" height="80"/></td>';
        $contenu .= '<td>' . $membres['prix'] . '€</td>';
        $contenu .= '<td>' . $membres['etat'] . '</td>';
		$contenu .= '<td><a href="?action=modification&id_produit=' . $membres['id_produit'] . '"><img src="' . RACINE_SITE . 'img/edit.png"/></a></td>';
		$contenu .= '<td><a href="?action=suppression&id_produit=' . $membres['id_produit'] . '"><img src="' . RACINE_SITE . 'img/delete.png"/></a></td>';
		$contenu .= '</tr>';
	}
   
	$contenu .= '</table>'; 


//Enregistrer les produits dans la BDD:
if($_POST){
    if(isset($_GET['action']) && $_GET['action'] == 'modification'){
        $info = $pdo -> prepare("UPDATE produit SET  date_arrivee=:date_arrivee, date_depart=:date_depart, prix=:prix WHERE id_salle=:id_salle ");
        $info -> bindParam(':id_salle', $_POST['id_salle'], PDO::PARAM_INT); 
        $info -> bindParam(':date_arrive', $_POST['date_a'], PDO::PARAM_STR); 
        $info -> bindParam(':date_depart', $_POST['date_d'], PDO::PARAM_STR); 
        $info -> bindParam(':prix', $_POST['prix'], PDO::PARAM_STR); 
        $info -> execute();
    }
    else{
    extract($_POST);
    $info = $pdo -> query("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix) VALUES ('$id_salle', '$date_a', '$date_d', '$tarif')");

    echo '<div class="validation">le produit a bien été enregistrer dans la BDD</div>';

}}





$page ="Gestion boutique";
require_once('../inc/header.inc.php');
?>
<!--Contenu HTML-->

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