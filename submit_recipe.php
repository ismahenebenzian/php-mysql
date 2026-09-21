<?php
session_start();

// Connexion à la BDD
include_once('mysql.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Site de recettes - Recette ajoutée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container flex-grow-1">

        <?php include_once('header.php'); ?>

        <?php
        // 1. Sécurité : l'utilisateur doit être connecté
        if (!isset($_SESSION['LOGGED_USER'])) {
            echo '<div class="alert alert-danger">Vous devez être connecté.</div>';
            echo '<a href="index.php" class="btn btn-primary">Retour</a>';
            include_once('footer.php');
            return;
        }

        // 2. Vérification des données soumises
        if (
            !isset($_POST['title']) || empty(trim($_POST['title'])) ||
            !isset($_POST['recipe']) || empty(trim($_POST['recipe']))
        ) {
            echo '<div class="alert alert-danger">Il faut un titre et une recette valides.</div>';
            echo '<a href="create_recipe.php" class="btn btn-primary">Retour au formulaire</a>';
            include_once('footer.php');
            return;
        }

        // 3. Nettoyage des données
        $title  = trim($_POST['title']);
        $recipe = trim($_POST['recipe']);
        $author = $_SESSION['LOGGED_USER'];

        // 4. Insertion en base via PDO
        try {
            $sqlQuery = 'INSERT INTO recipes(title, recipe, author, is_enabled) 
                         VALUES (:title, :recipe, :author, :is_enabled)';

            $insertRecipe = $db->prepare($sqlQuery);
            $insertRecipe->execute([
                'title'      => $title,
                'recipe'     => $recipe,
                'author'     => $author,
                'is_enabled' => 1,   // 1 = true, 0 = false
            ]);

            echo '<div class="alert alert-success">Recette ajoutée avec succès !</div>';

        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Erreur : ' . $e->getMessage() . '</div>';
        }
        ?>

        <a href="index.php" class="btn btn-primary">Retour à l\'accueil</a>

    </div>

    <?php include_once('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>