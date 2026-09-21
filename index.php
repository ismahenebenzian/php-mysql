<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de recettes - Page d'accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include_once('header.php'); ?>

    <div class="container mt-4">

        <?php
        include_once('variables.php');
        include_once('functions.php');
        include_once('mysql.php');
        ?>

        <?php include_once('login.php'); ?>

        <h1>Site de Recettes !</h1>

        <?php if (isset($_SESSION['LOGGED_USER'])): ?>

            <?php
            $sqlQuery = 'SELECT * FROM recipes WHERE is_enabled = :is_enabled';
            $recipesStatement = $db->prepare($sqlQuery);
            $recipesStatement->execute(['is_enabled' => 1]);
            $recipes = $recipesStatement->fetchAll();
            ?>

            <?php foreach ($recipes as $recipe) : ?>

                <article>
                    <h3>
                        <a href="recipe.php?id=<?php echo $recipe['recipe_id']; ?>">
                            <?php echo $recipe['title']; ?>
                        </a>
                    </h3>

                    <p><?php echo substr($recipe['recipe'], 0, 150); ?>...</p>

                    <i>Par <?php echo displayAuthor($recipe['author'], $users); ?></i>

                    <?php if ($_SESSION['LOGGED_USER'] === $recipe['author']) : ?>
                        <p>
                            <a href="edit_recipe.php?id=<?php echo $recipe['recipe_id']; ?>" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="delete_recipe.php?id=<?php echo $recipe['recipe_id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </p>
                    <?php endif; ?>
                </article>

                <hr>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <?php include_once('footer.php'); ?>

</body>
</html>