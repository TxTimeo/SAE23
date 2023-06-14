<link rel="stylesheet" type="text/css" href="css/style copy.css">

<?php
	//******************************************************************************
	function FormulaireAuthentification(){//fourni
	?>
	<form id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h1 style="color: black; padding-bottom:10px; font-size:30px">Se connecter</h1>

	<fieldset style="border: none;">
    <label for="text">Nom d'utilisateur : </label><input type="text" name="text" id="text" required size="20" /><br />
    <label for="id_pass">Mot de passe : &emsp; </label>
    <input type="password" name="pass" id="id_pass" required size="10" /><br />
    <label for="captcha">Captcha : </label>
	<br>
	<img src="image.php" alt="Captcha" onclick="this.src='image.php?' + Math.random();" /> <!-- Affichage de l'image captcha -->
	<input type="text" name="captcha" id="captcha" /><br /> <!-- Ajout du champ captcha -->
    <input type="submit" name="connect" value="Connexion" class="btn-connexion"/>
</fieldset>
	</form>
	<?php
	}
	//******************************************************************************
	function Menu()
	{
		// Vérifier si les clés du tableau $_SESSION existent
		if (isset($_SESSION["login"]) && isset($_SESSION["statut"])) {
			echo '<p>votre login est ' . $_SESSION["login"] . ' </p>';
			?>
			<ul>
				<?php if ($_SESSION["statut"]) { ?>
					
				<?php } ?>
			</ul>
		<?php
		} else {
			echo '<p>Contenu de la page index sans connexion</p>';
			echo '<div><a href="connexion.php" class="login-btn">Se connecter</a></div>';
		}
	}
// ------------------ Fonction Liste des Jeux ------------------

function listeJeux() {
    $retour = array();
    try {
        $madb = new PDO('sqlite:bdd/DATA.sqlite');
        $requete = "SELECT Images,Nom,Prix,commentaires,Plateforme FROM Jeu"; 
        $resultat = $madb->query($requete);
        $tableau_assoc = $resultat->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($tableau_assoc)) {
            $retour = $tableau_assoc;
        }
    } catch (Exception $e) {
        echo "Erreur de connexion avec la BDD";
    }
    return $retour;
}
//------------------ Fonction Affichage des Jeux ------------------

function afficheJeux($tab) {
    echo '<article class="image-table">';
    if (!empty($tab) && isset($tab[0])) {
        foreach ($tab[0] as $colonne => $valeur) {

        }
    }

    foreach ($tab as $ligne) {
        if (is_array($ligne)) {

            foreach ($ligne as $nomCellule => $cellule) {
                if ($nomCellule == 'Images') {
                    echo '<article  class="image-affiche">';
                    echo '<br>';
                    echo '<article class="nom">Nom du jeu : ' . $ligne['Nom'] . '</article>';
                    echo '<br>';
                    // Modification de la ligne pour inclure le chemin relatif du dossier "images"
                    echo '<img src="images/' . basename($cellule) . '" alt="Image du produit" width="200">';
                    echo '<br>';
                    echo '<br>';
                    echo '<article class="prix">Prix du jeu : ' . $ligne['Prix'] .' €'.'</article>';
                    echo '<br>';
                    echo '<article class="Commentaires">Commentaire : ' . $ligne['Commentaires'] . '</article>';
                    echo '<br>';
                    echo '<article class="Plateforme">Plateforme : ' . $ligne['Plateforme'] . '</article>';
                    echo '<br>';
                    echo '</article>';
                    echo '<br>';
                }
            }

        }
    }
    echo '</article>';
}




