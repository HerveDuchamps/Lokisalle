<?php
require_once('../inc/init.inc.php');

$liste_produit = $pdo-> query ("SELECT * FROM produit");

$resultat = $pdo -> query ("SELECT c.id_commande, c.date_enregistrement, m.id_membre, m.email, p.id_produit, p.date_arrivee, p.date_depart, p.prix 
FROM commande c, membre m, produit p 
WHERE p.id_produit = c.id_produit 
AND m.id_membre = c.id_membre");

$contenu .= '<table border="1">';
	$contenu .= '<tr>';
	
    $contenu .= '<th>id_commande</th>';
	$contenu .= '<th>id_membre</th>';
	$contenu .= '<th>id_produit</th>';
	$contenu .= '<th>prix</th>';
    $contenu .= '<th>date_enregistrement</th>';
        
	$contenu .= '<th colspan="2">Actions</th>';	


	$contenu .= '</tr>';
		while($membres = $resultat -> fetch(PDO::FETCH_ASSOC)){
	$contenu .= '<tr>';
	    $contenu .= '<td>' . $membres['email'] . '</td>';
        $contenu .= '<td>' . $membres['date_arrivee'] . '</td>';
        $contenu .= '<td>' . $membres['date_depart'] . '</td>';
        $contenu .= '<td>' . $membres['id_salle'] . ' - ' . $membres['titre'] . '<br /><img src="' . RACINE_SITE . 'img/' . $membres['photo'] . '" height="80"/></td>';
        $contenu .= '<td>' . $membres['id_produit'] . '</td>';
	    $contenu .= '<td>' . $membres['prix'] . '€</td>';
        $contenu .= '<td>' . $membres['date_enregistrement'] . '</td>';
		
		$contenu .= '<td><a href="?action=suppression&id_produit=' . $membres['id_produit'] . '"><img src="' . RACINE_SITE . 'img/delete.png"/></a></td>';
		$contenu .= '</tr>';
	}
   
	$contenu .= '</table>'; 

$page='Gestion_des_commandes';
require_once('../inc/header.inc.php');
?>


<!--Contenu HTML-->

<?= $contenu?>
<div class="form">
    <form method="post" action="" enctype="multipart/form-data">
        <label>id membre</label>
        <input type="text" name="id_membre"/>

        <label>id produit </label>
        <input type="text" name="id_produit"/>

        <label>Salle</label>
        <select name="id_salle"/>
            <?php
            while($salle = $liste_salle->fetch(PDO::FETCH_ASSOC)){
                echo '<option value="'. $salle['id_salle']. '">'.$salle['id_salle'].'-'.$salle['titre'].'-'.$salle['adresse'].'-'.$salle['cp'].'-'.$salle['ville'].'-'.$salle['capacite'].'</option>';
            }
            ?>
        </select>

        <label>prix</label>
        <input type="text" name="prix"/>


<?php
require_once('../inc/footer.inc.php');
?>