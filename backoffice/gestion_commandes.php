<?php

require_once('../inc/init.inc.php');

$page = 'Gestion Avis';


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// AFFICHAGE DE TOUTES LES COMMANDES

$resultat = $pdo -> query("SELECT commande.id_commande, membre.id_membre, membre.email, produit.id_salle, salle.titre, produit.date_arrivee, produit.date_depart, produit.prix, commande.date_enregistrement FROM produit, membre, commande, salle WHERE membre.id_membre = commande.id_membre AND produit.id_produit = commande.id_produit AND salle.id_salle = produit.id_salle");

$contenu .= '<table border="1">';
$contenu .= '<tr>';
$contenu .= '<th>id commande</th>';
$contenu .= '<th>id membre</th>';
$contenu .= '<th>id produit</th>';
$contenu .= '<th>prix</th>';
$contenu .= '<th>date enregistrement</th>';
$contenu .= '<th>Action</th>';
$contenu .= '</tr>';

while($commandes = $resultat -> fetch(PDO::FETCH_ASSOC)){
	$contenu .= '<tr>';
	$contenu .= '<td>'.$commandes['id_commande'].'</td>'; 
	$contenu .= '<td>'.$commandes['id_membre']. ' - ' . $commandes['email'] .'</td>';
	$contenu .= '<td>'.$commandes['id_salle']. ' - ' . $commandes['titre'] .'<br>'. $commandes['date_arrivee'] . 'au ' . $commandes['date_depart'].'</td>'; 
	$contenu .= '<td>'.$commandes['prix'].'</td>'; 
	$contenu .= '<td>'.$commandes['date_enregistrement'].'</td>'; 

	$contenu .= '<td><a href="?action=supprimer&id='.$commandes['id_commande'].'"><img src="'.'../img/delete.png"/></a></td>';
	$contenu .= '</tr>'; 
}

$contenu .= '</table>'; 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		


// SUPPRESSION D'UNE COMMANDE

if (isset($_GET['action']) && $_GET['action']=='supprimer' && isset($_GET['id'])){

	$resultat = $pdo -> prepare("DELETE FROM commande WHERE id_commande=:id_commande");
	$resultat -> bindParam(':id_commande', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if ($resultat){
		echo 'Commande supprimée avec succès !';
		header('location:gestion_commandes.php');
	}

	else {
		echo 'Commande inexistante...';
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

require_once('../inc/header.inc.php');
?>
<!-- Contenu HTML -->
<h1>Gestion des commandes</h1>

<br>
<?= $contenu ?>
<br>


<?php
require_once('../inc/footer.inc.php');
?>



