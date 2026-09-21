<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Site de recettes - Ajouter une recette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container flex-grow-1">

        <?php include_once('header.php'); ?>

        <h1>Ajouter une recette</h1>

        <?php if (!isset($_SESSION['LOGGED_USER'])): ?>
            <div class="alert alert-danger">
                Vous devez être connecté pour ajouter une recette.
            </div>
            <a href="index.php" class="btn btn-primary">Se connecter</a>
        <?php else: ?>

            <form action="submit_recipe.php" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="mb-3">
                    <label for="recipe" class="form-label">Recette</label>
                    <textarea class="form-control" id="recipe" name="recipe" rows="6" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>

        <?php endif; ?>
    </div>

    <?php include_once('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>