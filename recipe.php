<?php
session_start();
include_once('mysql.php');
include_once('variables.php');
include_once('functions.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    echo 'Vous devez être connecté.';
    return;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo 'Recette introuvable.';
    return;
}

$recipeId = (int) $_GET['id'];

$sqlQuery = 'SELECT * FROM recipes WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]);
$recipe = $stmt->fetch();

if (!$recipe) {
    echo 'La recette n\'existe pas.';
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $recipe['title']; ?> - Site de recettes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

    <?php include_once('header.php'); ?>

    <div class="container flex-grow-1 py-4">
        <div class="row justify-content-center">
            <div class="col-md-9">

                <a href="index.php" class="btn btn-secondary btn-sm mb-3">Retour à l'accueil</a>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        <h1 class="text-primary mb-3"><?php echo $recipe['title']; ?></h1>

                        <p><?php echo $recipe['recipe']; ?></p>

                        <p class="text-muted fst-italic">
                            Par <?php echo displayAuthor($recipe['author'], $users); ?>
                        </p>

                        <?php if ($_SESSION['LOGGED_USER'] === $recipe['author']) : ?>
                            <a href="edit_recipe.php?id=<?php echo $recipe['recipe_id']; ?>" 
                               class="btn btn-primary btn-sm">Modifier</a>
                            <a href="delete_recipe.php?id=<?php echo $recipe['recipe_id']; ?>" 
                               class="btn btn-danger btn-sm">Supprimer</a>
                        <?php endif; ?>

                        <hr class="my-4">

                        <h4 class="text-secondary mb-3">Commentaires</h4>

                        <?php
                        $sqlQuery = 'SELECT c.comment, c.created_at, u.email 
                                     FROM comments c 
                                     JOIN users u ON c.user_id = u.user_id 
                                     WHERE c.recipe_id = :recipe_id 
                                     ORDER BY c.created_at DESC';
                        $stmt = $db->prepare($sqlQuery);
                        $stmt->execute(['recipe_id' => $recipeId]);
                        $comments = $stmt->fetchAll();
                        ?>

                        <?php if (count($comments) > 0): ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="border-start border-3 border-primary ps-3 mb-3">
                                    <p class="mb-1"><?php echo $comment['comment']; ?></p>
                                    <small class="text-muted">
                                        <?php echo displayAuthor($comment['email'], $users); ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted fst-italic">Aucun commentaire pour le moment.</p>
                        <?php endif; ?>

                        <form action="submit_comment.php" method="POST" class="bg-light p-3 rounded mt-4">
                            <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">

                            <div class="mb-3">
                                <label for="comment" class="form-label">Postez un commentaire</label>
                                <textarea class="form-control" id="comment" name="comment" 
                                          placeholder="Soyez respectueux/se, nous sommes humain(e)s." 
                                          rows="3" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Envoyer</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>