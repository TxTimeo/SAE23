<?php
	//****************Fonctions utilisées*****************************************************************
	function authentification($login,$pass){
		$retour = false ;
		$madb = new PDO('sqlite:bdd/USER.sqlite'); 
		$login= $madb->quote($login);
		$pass = $madb->quote($pass);
		$requete = "SELECT UTILISATEUR,MOTDEPASSE FROM comptes WHERE UTILISATEUR=$login AND MOTDEPASSE=$pass";
		//var_dump($requete);echo "<br/>";  	
		$resultat = $madb->query($requete);
		$tableau_assoc = $resultat->fetchAll(PDO::FETCH_ASSOC);
		if (sizeof($tableau_assoc)!=0) $retour = true;	
		return $retour;
	}		//***********************************************************************************	
	function isAdmin($login){
		$retour = false ;
		// CNX BDD
		$madb = new PDO('sqlite:bdd/USER.sqlite'); 
		// Quote pour échapper les caractères spéciaux
		$login= $madb->quote($login);
		// Requête
		$requete = "SELECT UTILISATEUR,MOTDEPASSE FROM comptes WHERE UTILISATEUR=$login AND STATUT='administrateur'";
		// Executer la requête
		$resultat = $madb->query($requete);
		// Traiter le résultat
		$tableau_assoc = $resultat->fetchAll(PDO::FETCH_ASSOC);
		if (sizeof($tableau_assoc)!=0) $retour = true;	
		return $retour;	
		
	}

	//*********************************************************************************************************
	//Nom : redirect()
	//Role : Permet une redirection en javascript
	//Parametre : URL de redirection et Délais avant la redirection
	//Retour : Aucun
	//*******************
	function redirect($url,$tps)
	{
		$temps = $tps * 1000;
		
		echo "<script type=\"text/javascript\">\n"
		. "<!--\n"
		. "\n"
		. "function redirect() {\n"
		. "window.location='" . $url . "'\n"
		. "}\n"
		. "setTimeout('redirect()','" . $temps ."');\n"
		. "\n"
		. "// -->\n"
		. "</script>\n";
		
	}
	//********************************************************************************************************
	function afficheTableau($tab){
		echo '<table>';	
		echo '<tr>';// les entetes des colonnes qu'on lit dans le premier tableau par exemple
		foreach($tab[0] as $colonne=>$valeur){		echo "<th>$colonne</th>";		}
		echo "</tr>\n";
		// le corps de la table
		foreach($tab as $ligne){
			echo '<tr>';
			foreach($ligne as $cellule)		{		echo "<td>$cellule</td>";		}
			echo "</tr>\n";
		}
		echo '</table>';
	}

	function filtrerJeux($series, $prix, $plateforme)
{
    // Récupérer tous les jeux
    $jeux = listeJeux();

    // Filtrer les jeux en fonction des critères
    $jeuxFiltres = array_filter($jeux, function ($jeu) use ($series, $prix, $plateforme) {
        // Vérifier la série
        if ($series !== 'Toutes' && $jeu['serie'] !== $series) {
            return false;
        }
        // Vérifier le prix
        if ($prix !== 'Tous' && $jeu['prix'] !== $prix) {
            return false;
        }
        // Vérifier la plateforme
        if ($plateforme !== 'Toutes' && $jeu['plateforme'] !== $plateforme) {
            return false;
        }
        return true;
    });

    return $jeuxFiltres;
}



?>

