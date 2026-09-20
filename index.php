<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Site de recettes - Page d'accueil</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">

    <div class="container flex-grow-1">

        <!-- Inclusion du menu -->
        <?php include_once('header.php'); ?>

        <?php
        // Variables utilisateurs
        include_once('variables.php');

        // Fonctions
        include_once('functions.php');

        // Connexion à MySQL
        include_once('mysql.php');
        ?>

        <!-- Formulaire de connexion -->
        <?php include_once('login.php'); ?>

        <h1>Site de Recettes !</h1>

        <!-- Si l'utilisateur est connecté, on affiche les recettes -->
        <?php if (isset($_SESSION['LOGGED_USER'])): ?>

            <?php
            // On récupère les recettes valides depuis la base de données
            $sqlQuery = 'SELECT * FROM recipes WHERE is_enabled = :is_enabled';

            $recipesStatement = $db->prepare($sqlQuery);

            $recipesStatement->execute([
                'is_enabled' => 1
            ]);

            $recipes = $recipesStatement->fetchAll();
            ?>

            <!-- On affiche chaque recette -->
            <?php foreach ($recipes as $recipe) : ?>

                <article>
                    <h3>
                        <?php echo $recipe['title']; ?>
                    </h3>

                    <div>
                        <?php echo $recipe['recipe']; ?>
                    </div>

                    <i>
                        <?php echo displayAuthor($recipe['author'], $users); ?>
                    </i>
                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <!-- Footer -->
    <?php include_once('footer.php'); ?>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>