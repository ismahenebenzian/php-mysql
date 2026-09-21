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

                    <?php if ($_SESSION['LOGGED_USER'] === $recipe['author']) : ?>
                        <br>
                        <a href="edit_recipe.php?id=<?php echo $recipe['recipe_id']; ?>">Modifier</a>
                        <a href="delete_recipe.php?id=<?php echo $recipe['recipe_id']; ?>">Supprimer</a>
                    <?php endif; ?>

                    <?php
                    // Récupérer les commentaires de cette recette
                    $sqlQuery = 'SELECT c.comment, u.email 
                                 FROM comments c 
                                 JOIN users u ON c.user_id = u.user_id 
                                 WHERE c.recipe_id = :recipe_id';
                    $stmt = $db->prepare($sqlQuery);
                    $stmt->execute(['recipe_id' => $recipe['recipe_id']]);
                    $comments = $stmt->fetchAll();
                    ?>

                    <!-- Affichage des commentaires -->
                    <h4>Commentaires</h4>

                    <?php if (count($comments) > 0): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div>
                                <p><?php echo $comment['comment']; ?></p>
                                <small><?php echo displayAuthor($comment['email'], $users); ?></small>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun commentaire pour le moment.</p>
                    <?php endif; ?>

                    <!-- Formulaire pour ajouter un commentaire -->
                    <?php if (isset($_SESSION['LOGGED_USER'])): ?>
                        <form action="submit_comment.php" method="POST">
                            <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">

                            <p>
                                <label for="comment_<?php echo $recipe['recipe_id']; ?>">Votre commentaire</label><br>
                                <textarea id="comment_<?php echo $recipe['recipe_id']; ?>" name="comment" rows="3" required></textarea>
                            </p>

                            <button type="submit">Envoyer</button>
                        </form>
                    <?php endif; ?>

                </article>

                <hr>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <!-- Footer -->
    <?php include_once('footer.php'); ?>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>