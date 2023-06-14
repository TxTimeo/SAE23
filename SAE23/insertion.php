<?php
session_start();
include 'fonctions.php';

// Vérifier si l'utilisateur a le droit d'accéder à cette page
if (!isAdmin($_SESSION['login'])) {
    header("Location: index.php"); // Rediriger vers la page index.php
    exit();
}

// Traitement du formulaire d'insertion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nom']) && isset($_POST['serie']) && isset($_POST['plateforme']) && isset($_POST['commentaires']) && isset($_POST['prix'])) {
    $nom = $_POST['nom'];
    $serie = $_POST['serie'];
    $commentaires = $_POST['commentaires'];
    $prix = $_POST['prix'];
    $plateforme = $_POST['plateforme'];

    // Vérifier si tous les champs sont renseignés
    if (empty($nom) || empty($serie) || empty($plateforme) || empty($commentaires) || empty($prix)) {
        echo "Veuillez remplir tous les champs obligatoires.";
    } elseif (!preg_match('/^[0-9,]+$/', $prix)) {
        echo "Le champ 'prix' doit contenir uniquement des chiffres et des virgules.";
    } else {
        // Gestion de l'image
        $image = $_FILES['image'];
        $nomImage = $image['name'];
        $extension = pathinfo($nomImage, PATHINFO_EXTENSION); // Obtenir l'extension du fichier d'origine
        $nomImageEnregistre = 'images/'.uniqid().'.'.$extension; // Générer un nom de fichier unique avec l'extension

        // Déplacer l'image vers le dossier de destination
        move_uploaded_file($image['tmp_name'], $nomImageEnregistre);

        // Vérifier si l'élément existe déjà dans la base de données
        $madb = new PDO('sqlite:bdd/DATA.sqlite');
        $requeteExistence = "SELECT COUNT(*) FROM jeu WHERE Nom = :nom";
        $statementExistence = $madb->prepare($requeteExistence);
        $statementExistence->bindParam(':nom', $nom);
        $statementExistence->execute();
        $resultatExistence = $statementExistence->fetchColumn();

        if ($resultatExistence > 0) {
            echo "L'élément existe déjà dans la base de données.";
        } else {
            // Insertion de l'élément dans la base de données
            $requeteInsertion = "INSERT INTO jeu (Nom, Serie, Plateforme, Commentaires, Prix, Images) VALUES (:nom, :serie, :plateforme, :commentaires, :prix, :images)";
            $statementInsertion = $madb->prepare($requeteInsertion);
            $statementInsertion->bindParam(':nom', $nom);
            $statementInsertion->bindParam(':serie', $serie);
            $statementInsertion->bindParam(':plateforme', $plateforme);
            $statementInsertion->bindParam(':commentaires', $commentaires);
            $statementInsertion->bindParam(':prix', $prix);
            $statementInsertion->bindParam(':images', $nomImageEnregistre);
            $resultatInsertion = $statementInsertion->execute();

            if ($resultatInsertion) {
                // Rediriger vers la page insertion.php pour réinitialiser le formulaire
                header("Location: insertion.php");
                exit();
            } else {
                // Gérer l'erreur d'insertion
                echo "Erreur lors de l'insertion de l'élément.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Insertion d'un élément</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>
<body style="
    background: #121212;
">

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" aria-label="Fourth navbar example">
    <div class="container-fluid">
        <!-- Titre de la navbar -->
        <a class="navbar-brand" href="index.php">
            Accueil
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a href="suppression.php?action=supprimer_utilisateur" title="Supprimer un utilisateur" class="nav-link">
                        <i class="bi bi-trash"></i> Supprimer un jeu
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

<h1 style="margin:2%; padding-top:4%">Insertion d'un élément</h1>
<form method="POST" action="insertion.php" enctype="multipart/form-data">
    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" required><br>

    <label for="serie">Série :</label>
    <input type="text" name="serie" id="serie" required><br>

    <label for="commentaires">Commentaires :</label>
    <input type="text" name="commentaires" id="commentaires"><br>

    <label for="prix">Prix :</label>
    <input type="text" name="prix" id="prix"><br>

    <label for="plateforme">Plateforme :</label>
    <select name="plateforme" id="plateforme">
        <option value="XBox">XBox</option>
        <option value="PC">PC</option>
        <option value="PS3">PS3</option>
        <option value="PS4">PS4</option>
        <option value="Switch">Switch</option>
    </select><br>

    <label for="image">Image :</label>
    <input type="file" name="image" id="image" accept="image/*"><br>

    <input type="submit" value="Insérer" class="btn-connexion">
</form>

<footer class="footer" style="margin-top: 2%;">
    <p style="text-align:center; color: white"><?php echo $_SERVER['PHP_SELF']; ?> - 2023 1A2 Timéo & Hugo</p>
    <a href="index.php" style="margin-right: 43%; text-decoration: none; color: white;">Retour à la page d'accueil</a>
</footer>



</body>
</html>
