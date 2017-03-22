<?php
require_once('inc/init.inc.php');
?>

<?php
// Récupérer toutes les infos de tous les produits : 
    $salle = 'bureau';
$condition = '';
    if (!empty($_POST['categorie'])){
      $condition .= "AND s.categorie = '" . $_POST['categorie'] . "' ";
      }
    if (!empty($_POST['ville'])){
      $condition .= "AND s.ville = '" . $_POST['ville'] . "' ";
       }
    if (!empty($_POST['capacite'])){
      $condition .= "AND s.capacite = '" . $_POST['capacite'] . "' ";
       }
    if (!empty($_POST['prix'])){
      $condition .= "AND p.prix < '" . $_POST['prix'] . "' ";
       }

    $resultat = $pdo -> query("SELECT s.titre, s.description, s.photo, s.pays, s.ville, s.adresse, s.cp, s.capacite, s.categorie, date_format(p.date_arrivee, '%d/%m/%Y') as date_arrivee, p.date_depart, p.prix, p.etat
    FROM salle s, produit p
    WHERE s.id_salle=p.id_salle    
    $condition
    ");
		$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);
   

// Récupération des valeurs de filtres
    $resultat = $pdo -> query("SELECT categorie FROM salle GROUP BY categorie");
		$categories = $resultat -> fetchAll(PDO::FETCH_ASSOC);

    $resultat = $pdo -> query("SELECT ville FROM salle GROUP BY ville");
		$villes = $resultat -> fetchAll(PDO::FETCH_ASSOC);

    $resultat = $pdo -> query("SELECT capacite FROM salle GROUP BY capacite");
		$capacites = $resultat -> fetchAll(PDO::FETCH_ASSOC);

    $resultat = $pdo -> query("SELECT prix FROM produit GROUP BY prix");
		$prix = $resultat -> fetchAll(PDO::FETCH_ASSOC);
    $prix = max($prix);
    
    //$date_french_arrivee = $valeur['date_arrivee'];
    //echo $valeur['date_arrivee'];

$page = 'Boutique';
require_once('inc/header.inc.php');
?>
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-3">
          <h3>Filtres</h3>
          
          <form method="post" action="">
            <div class="radio">
            <label for="categorie">Catégories</label>
            <?php foreach($categories as $categories) : ?>
            <input onchange="this.form.submit()" type="radio" name="categorie" value="<?=$categories['categorie']?>" class="custom-control-input"><p><?= $categories['categorie'] ?></p>
            <?php endforeach; ?>
            </div>
            <div class="radio">
            <label for="ville">Villes</label>
            <?php foreach($villes as $villes) : ?>
            <input onchange="this.form.submit()" type="radio" name="ville" value="<?=$villes['ville']?>" class="custom-control-input"><p><?= $villes['ville'] ?></p>
            <?php endforeach; ?>
            </div>
            <label for="capacite">Capacité</label>
            <select name="capacite" class="form-control">
            <?php foreach($capacites as $capacites) : ?>
            <option onchange="this.form.submit()" value="">--</option>
            <option onchange="this.form.submit()" value="<?=$capacites['capacite']?>"><?=$capacites['capacite']?></option>
            <?php endforeach; ?>
            </select>
            <label>Prix</label>
            <input onchange="this.form.submit()" type="range" name="prix" min="0" max="<?=$prix['prix']?>">
            <p>Prix maximum : <?=$prix['prix']?> €</p>
            <label>Période</label>
            <input type="date" name="date_arrivee">
            <input type="date" name="date_depart">
            <input type="submit" class="btn btn-primary" value"recherche">
          </form>
          <!--
          <ul>
            <?php foreach($categories as $categories) : ?>
            <li><a href="?categories=<?= $categories['categorie'] ?>"><?= $categories['categorie'] ?></a></li>
            <?php endforeach; ?>
          </ul>
          -->
          
          
        </div>
        <div class="col-md-9 premiere_ligne_photo">
          <?php 
          foreach($produits as $valeur) : ?> 
          <!-- début vignette produit -->
          <div class="col-md-4 produits_salles">
            <a href="fiche_produit.php?id=<?= $valeur['id_produit'] ?>"><img class="photos center-block" src="<?= RACINE_SITE . '/img/' . $valeur['photo'] ?>" width="200 px"/></a>
            <h3  class="titre-photo"><?= $valeur['titre']?></h3>
            <p style="font-size: 15px; "><?= $valeur['prix'] ?> €</p>
            <p style="font-size: 15px; "><?= $valeur['description'] ?></p>
            <p style="font-size: 15px; " class="date"><?= $valeur['date_arrivee'] ?> au </p>

            <?php 
            $datedepart = new Datetime($valeur['date_depart']);
            $datedepart_fr = $datedepart->format('d/m/Y');
            ?>
            <p style="font-size: 15px; " class="date"><?= $datedepart_fr ?></p>
            <p style="height: 40px;"><?= substr($valeur['description'], 0, 45) . '...' ?></p>
            <a href="fiche_produit.php?id=<?= $valeur['id_produit'] ?>" style="padding: 5px 15px; background: orange; color: white; text-align: center; border: 2px solid black; border-radius: 5px">Voir la fiche</a>
          </div>
          <!-- fin vignette produit -->
	        <?php endforeach; ?>
        </div>



<?php require_once('inc/footer.inc.php');?>