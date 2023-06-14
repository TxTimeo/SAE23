<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['login'])) {
    header("Location: index.php"); // Redirection vers la page de connexion
    exit();
}
if (!isset($_SESSION)) {
    header("Location: connexion.php");
    exit();
}

// Connexion à la base de données
$madb = new PDO('sqlite:bdd/DATA.sqlite');

// Vérifier si le formulaire de modification a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['noJeu']) && isset($_POST['nom']) && isset($_POST['serie']) && isset($_POST['plateforme']) && isset($_POST['commentaires']) && isset($_POST['prix'])) {
    // Récupérer les données du formulaire
    $noJeu = $_POST['noJeu'];
    $nom = $_POST['nom'];
    $serie = $_POST['serie'];
    $plateforme = $_POST['plateforme'];
    $commentaires = $_POST['commentaires'];
    $prix = $_POST['prix'];

    // Mettre à jour les informations dans la base de données
    $stmt = $madb->prepare("UPDATE jeu SET Nom = :nom, Serie = :serie, Plateforme = :plateforme, Commentaires = :commentaires, Prix = :prix WHERE NoJeu = :noJeu");
    $stmt->execute([
        'nom' => $nom,
        'serie' => $serie,
        'plateforme' => $plateforme,
        'commentaires' => $commentaires,
        'prix' => $prix,
        'noJeu' => $noJeu
    ]);
}

// Vérifier si un élément a été sélectionné pour afficher le formulaire pré-rempli
if (isset($_POST['element'])) {
    $element = $_POST['element'];

    // Récupérer les données de l'élément à modifier depuis la base de données
    $stmt = $madb->prepare("SELECT * FROM jeu WHERE NoJeu = ?");
    $stmt->execute([$element]);
    $donnees = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modification d'un élément</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" aria-label="Fourth navbar example">
    <div class="container-fluid">
        <!--Titre de la navbar-->
        <a class="navbar-brand" href="index.php">
            Accueil
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse_navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a href="insertion.php?action=inserer_utilisateur" title="Insérer un utilisateur" class="nav-link">
                        <i class="bi bi-plus-circle"></i> Insérer un jeu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="suppression.php?action=supprimer_utilisateur" title="Supprimer un utilisateur" class="nav-link">
                        <i class="bi bi-trash"></i> Supprimer un jeu
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
<div class="container">
    <h1>Modifier un élément</h1>
    <?php if (!isset($_POST['element']) || (isset($_POST['element']) && isset($donnees))) { ?>
        <?php if (!isset($_POST['element'])) { ?>
            <form method="POST" action="modification.php" class="mb-4">
                <select name="element" class="form-select">
                    <option value="">Choisissez un élément</option>
                    <?php
                    // Récupérer tous les éléments de la base de données pour les afficher dans la liste déroulante
                    $stmt = $madb->prepare("SELECT NoJeu, Nom FROM jeu");
                    $stmt->execute();
                    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($resultats as $resultat) {
                        echo '<option value="' . $resultat['NoJeu'] . '">' . $resultat['Nom'] . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" value="Choisir" class="btn-connexion">
            </form>
        <?php } ?>
        <?php if (isset($_POST['element']) && isset($donnees)) { ?>
            <form method="POST" action="modification.php" class="mb-4">
                <input type="hidden" name="noJeu" value="<?php echo $donnees['NoJeu']; ?>">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom :</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?php echo $donnees['Nom']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="serie" class="form-label">Série :</label>
                    <input type="text" name="serie" id="serie" class="form-control" value="<?php echo $donnees['Serie']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="plateforme" class="form-label">Plateforme :</label>
                    <select name="plateforme" id="plateforme" class="form-select" required>
                        <option value="XBox" <?php echo ($donnees['Plateforme'] == 'XBox') ? 'selected' : ''; ?>>XBox</option>
                        <option value="PC" <?php echo ($donnees['Plateforme'] == 'PC') ? 'selected' : ''; ?>>PC</option>
                        <option value="PS3" <?php echo ($donnees['Plateforme'] == 'PS3') ? 'selected' : ''; ?>>PS3</option>
                        <option value="PS4" <?php echo ($donnees['Plateforme'] == 'PS4') ? 'selected' : ''; ?>>PS4</option>
                        <option value="Switch" <?php echo ($donnees['Plateforme'] == 'Switch') ? 'selected' : ''; ?>>Switch</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="commentaires" class="form-label">Commentaires :</label>
                    <textarea name="commentaires" id="commentaires" class="form-control" required><?php echo $donnees['Commentaires']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="prix" class="form-label">Prix :</label>
                    <input type="number" name="prix" id="prix" class="form-control" value="<?php echo $donnees['Prix']; ?>" required>
                </div>
                <input type="submit" value="Modifier" class="btn-connexion">
            </form>
        <?php } ?>
    <?php } ?>
</div>
<footer class="footer">
    <p style="text-align:center; color: white"><?php echo $_SERVER['PHP_SELF']; ?> - 2023 1A2 Timéo & Hugo</p>
    <a href="index.php" style=" margin-right: 43%">Retour à la page d'accueil</a>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
