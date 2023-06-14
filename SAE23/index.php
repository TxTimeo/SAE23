<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Page accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
session_start();
include 'formulaires.php';
include 'fonctions.php';

// Vérification de la session
if (isset($_SESSION['login'])) {
    // Utilisateur connecté
    $username = $_SESSION['login'];
    echo '<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">';
    echo '<div class="container-fluid">';
    echo '<a class="nav-item" href="#">Accueil</a>';
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse">';
    echo '<span class="navbar-toggler-icon"></span>';
    echo '</button>';
    echo '<div class="collapse_navbar-collapse" id="navbarsExample04">';
    echo '<ul class="navbar-nav me-auto mb-2 mb-md-0">';
    // Vérification de l'administrateur
    if (isAdmin($_SESSION['login'])) {
        echo '<li class="nav-item"><a href="insertion.php?action=modifier_utilisateur" title="Insérer un jeu" class="text-white"><i class="bi bi-plus-square"></i> Insérer un jeu</a></li>';
echo '<li class="nav-item"><a href="suppression.php?action=supprimer_utilisateur" title="Supprimer un jeu" class="text-white"><i class="bi bi-trash"></i> Supprimer un jeu</a></li>';
echo '<li class="nav-item"><a href="modification.php?action=modifier_utilisateur" title="Modifier un jeu" class="text-white"><i class="bi bi-pencil-square"></i> Modifier un jeu</a></li>';
}
echo '<li class="nav-item"><a href="connexion.php?action=logout" title="Déconnexion" class="text-white"><i class="bi bi-box-arrow-right"></i> Se déconnecter</a></li>';
 
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</nav>';
} else {
    // Utilisateur non connecté
    echo '<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" >';
    echo '<div class="container-fluid">';
    echo '<a class="nav-item" href="#">Accueil</a>';
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" >';
    echo '<span class="navbar-toggler-icon"></span>';
    echo '</button>';
    echo '<div class="collapse_navbar-collapse" >';
    echo '<ul class="navbar-nav me-auto mb-2 mb-md-0">';
    echo '<li class="nav-item"><a href="connexion.php" title="Connexion" class="bouton-bar"><i class="bi bi-box-arrow-in-right"></i> &nbsp Se connecter</a></li>';
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</nav>';
    $username = "connecte-toi";
}


echo '<img src="images/6008.jpg" class="image-navbar" alt="Image d\'accueil">';
echo '<h2 class="TITRE">Bienvenue, ' . $username . ' !</h2>'; // Ajout du message de bienvenue

echo '<h1>Page accueil</h1>';

// Filtrage des jeux
$series = $_GET['series'] ?? 'Toutes';
$prix = $_GET['prix'] ?? 'Tous';
$plateforme = $_GET['plateforme'] ?? 'Toutes';

$jeuxFiltres = filtrerJeux($series, $prix, $plateforme);

echo '<main>';
echo '<article class="image-grid">';
afficheJeux($jeuxFiltres);
echo '</article>';
echo '</main>';


?>
<footer class="text-center text-white;" >
    <div class="pt-4">
        <div class="mb-12">
            <p style="color: white"><?php echo $_SERVER['PHP_SELF']; ?> - 2023 1A2 Timéo & Hugo</p>
        </div>
    </div>
</footer>

    <!--Partie script importation module Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

        </body>
</html>
