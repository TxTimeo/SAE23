<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<?php
session_start();
include 'fonctions.php';

// Affichage du message accueil en fonction de la connexion
if (empty($_SESSION)) {
    header("Location: connexion.php"); // Rediriger vers la page connexion.php
    exit();
}

// Vérifier si l'utilisateur a le droit d'accéder à cette page
if (!isAdmin($_SESSION['login'])) {
    header("Location: index.php"); // Rediriger vers la page index.php
    exit();
}

// Traitement du formulaire de suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['NoJeu'])) {
    $NoJeu = $_POST['NoJeu'];

    // Récupérer le nom de l'image associée à l'élément
    $madb = new PDO('sqlite:bdd/DATA.sqlite');
    $requeteImage = "SELECT Images FROM Jeu WHERE NoJeu = :NoJeu";
    $statementImage = $madb->prepare($requeteImage);
    $statementImage->bindParam(':NoJeu', $NoJeu);
    $resultatImage = $statementImage->execute();

    if ($resultatImage && $rowImage = $statementImage->fetch(PDO::FETCH_ASSOC)) {
        $image = $rowImage['Images'];

        // Supprimer l'élément de la base de données
        $requeteSuppression = "DELETE FROM Jeu WHERE NoJeu = :NoJeu";
        $statementSuppression = $madb->prepare($requeteSuppression);
        $statementSuppression->bindParam(':NoJeu', $NoJeu);
        $resultatSuppression = $statementSuppression->execute();

        if ($resultatSuppression) {
            // Supprimer l'image du dossier "images"
            $cheminImage = "images/" . $image;
            if (file_exists($cheminImage)) {
                unlink($cheminImage);
            }

            // Rediriger vers la page suppression.php pour actualiser la liste
            header("Location: suppression.php");
            exit();
        } else {
            // Gérer l'erreur de suppression
            echo "Erreur lors de la suppression de l'élément.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Suppression d'un élément</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" aria-label="Fourth navbar example">
    <div class="container-fluid">
        <!-- Titre de la navbar -->
        <a class="navbar-item" href="index.php">
            Accueil
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Éléments de la navbar -->
        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a href="insertion.php?action=inserer_utilisateur" title="Insérer un utilisateur" class="nav-link">
                        <i class="bi bi-plus-circle"></i> Insérer un jeu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modification.php?action=modifier_utilisateur" title="Modifier un utilisateur" class="nav-link">
                        <i class="bi bi-pencil-square"></i> Modifier un jeu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="connexion.php?action=logout" title="Déconnexion" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i> Se déconnecter
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<h1 style="padding-top:5%; padding-bottom:3%;">Suppression d'un élément</h1>

<form method="POST" action="suppression.php" class="text-center">
    <label for="NoJeu">Choisissez un élément à supprimer :</label>
    <br><br>
    <select name="NoJeu" id="NoJeu">
        <?php
        // Récupérer les éléments depuis la base de données
        $madb = new PDO('sqlite:bdd/DATA.sqlite');
        $requete = "SELECT NoJeu, Nom FROM Jeu";
        $resultat = $madb->query($requete);
        while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=\"{$row['NoJeu']}\">{$row['Nom']}</option>";
        }
        ?>
    </select>
    <br><br>
    <input type="submit" value="Supprimer" class="btn-connexion">
</form>

</body>
<footer class="footer" style="text-align: center; bottom: 0; position: fixed; left: 0;">
    <p style="text-align:center; color: white"><?php echo $_SERVER['PHP_SELF']; ?> - 2023 1A2 Timéo & Hugo</p>
    <a href="index.php" style=" margin-right: 43%">Retour à la page d'accueil</a>
</footer>

</html>
