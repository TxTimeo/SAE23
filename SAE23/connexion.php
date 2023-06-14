<?php
session_start();
include 'formulaires.php';
include 'fonctions.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <title>Page connexion</title>
	<body style="overflow-y: hidden;">

</head>
<body>
<header>
    <h1 style="font-size:40px;padding-bottom:30px" class="TITRE">Bienvenue Gamer ! </h1>
</header>
<nav>
    <?php

    // affichage du formulaire de connexion en l'absence de session ou le menu avec le nom de la personne
    $afficherFormulaire = true; // Variable pour suivre l'état du formulaire

    if (!empty($_SESSION) && isset($_SESSION["login"])) {
        $afficherFormulaire = false; // Ne pas afficher le formulaire si une session est active
    }

    if ($afficherFormulaire) {
        FormulaireAuthentification();
    }

    // Test de l'authentification si envoi du formulaire
    if (!empty($_POST) && isset($_POST["text"]) && isset($_POST["pass"])) {
        // Vérification du captcha
        if ($_POST['captcha'] !== $_SESSION['code']) {
            echo '<p class="error-message">Captcha incorrect</p>';
            $afficherFormulaire = true; // Afficher à nouveau le formulaire en cas de captcha incorrect
        } else {
            if (authentification($_POST["text"], $_POST["pass"])) {
                // Ouverture de Session
                $_SESSION["login"] = $_POST["text"];
                $_SESSION["statut"] = isAdmin($_POST["text"]);
                redirect("index.php", 1); // Redirection vers page index
                // ajout au fichier de log
                if ($_SESSION['statut'] == 1) {
                    $statut_texte = "administrateur";
                } else {
                    $statut_texte = "utilisateur";
                }
                $monfichier = fopen('logs/connexion.log', 'a+');
                fputs($monfichier, "Connexion de '" . $_POST['text'] . "' IP: " . $_SERVER['REMOTE_ADDR'] . " le " . date('l jS \of F Y \à H:i:s') . " (connexion réussie) - statut : " . $statut_texte . "\n");
                fclose($monfichier);
            } else {
                // ajout au fichier de log
                $monfichier = fopen('logs/connexion.log', 'a+');
                fputs($monfichier, "Connexion de '" . $_POST['text'] . "' IP: " . $_SERVER['REMOTE_ADDR'] . " le " . date('l jS \of F Y \à H:i:s') . " (connexion échouée) \n");
                fclose($monfichier);
                echo '<p class="error-message">Login ou mot de passe incorrect</p>';
                $afficherFormulaire = true; // Afficher à nouveau le formulaire en cas de login ou mot de passe incorrect
            }
        }
    }

    // Destruction de la session avec le lien index.php?action=logout
    if (!empty($_SESSION) && !empty($_GET) && isset($_GET["action"]) && $_GET["action"] == "logout") {
        session_destroy();
        $_SESSION = array();
        redirect("index.php", 1); // Redirection vers page index
    }

    ?>
</nav>
<article>
    <?php
    // Affichage du message accueil en fonction de la connexion
    if (!empty($_SESSION) && isset($_SESSION["login"])) {
        echo '<h1>votre login est ' . $_SESSION["login"] . ' </h1>';
    }

    // traitement de la zone centrale de la page en fonction des liens GET du menu s'il y a une session

    ?>
</article>
<footer class="footer" style="text-align: center; bottom: 0; position: fixed; left: 0;">
    <p style="text-align:center; color: white"><?php echo $_SERVER['PHP_SELF']; ?> - 2023 1A2 Timéo & Hugo</p>
    <a href="index.php" style=" margin-right: 43%">Retour à la page d'accueil</a>
</footer >
<?php
if (isset($_SESSION['login'])) {
    if (isset($_GET["action"]) && $_GET["action"] == "logout") {
        // destruction de la session si l'utilisateur essaie de se déconnecter
        session_destroy();
        $_SESSION = array();
        header("Location: index.php");
        exit;
    } else {
        // redirection vers la page d'accueil si l'utilisateur est déjà connecté
        header("Location: index.php");
        exit;
    }
}
?>

</body>
</html>
