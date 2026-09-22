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

$sqlQuery = 'SELECT r.*, u.email 
             FROM recipes r 
             JOIN users u ON r.user_id = u.user_id 
             WHERE r.recipe_id = :recipe_id';
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
    <title><?php echo $recipe['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container mt-4">

        <a href="index.php">Retour à l'accueil</a>

        <h1><?php echo $recipe['title']; ?></h1>

        <p><?php echo $recipe['recipe']; ?></p>

        <i>Par <?php echo displayAuthor($recipe['email'], $users); ?></i>

        <?php if ($_SESSION['LOGGED_USER'] === $recipe['email']) : ?>
            <p>
                <a href="edit_recipe.php?id=<?php echo $recipe['recipe_id']; ?>" class="btn btn-primary btn-sm">Modifier</a>
                <a href="delete_recipe.php?id=<?php echo $recipe['recipe_id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
            </p>
        <?php endif; ?>

        <hr>

        <!-- Moyenne des notes -->
        <?php
        $sqlQuery = 'SELECT AVG(review) AS moyenne, COUNT(*) AS nb 
                     FROM comments WHERE recipe_id = :recipe_id';
        $stmt = $db->prepare($sqlQuery);
        $stmt->execute(['recipe_id' => $recipeId]);
        $stats = $stmt->fetch();

        $moyenne = $stats['moyenne'] ? round($stats['moyenne'], 1) : 0;
        $nbCommentaires = $stats['nb'];
        ?>

        <p><b>Note moyenne :</b> <?php echo $moyenne; ?>/5 (<?php echo $nbCommentaires; ?> avis)</p>

        <hr>

        <h4>Commentaires</h4>

        <?php
        $sqlQuery = 'SELECT c.comment, c.created_at, c.review, u.email 
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
                <p><?php echo $comment['comment']; ?></p>
                <i><?php echo displayAuthor($comment['email'], $users); ?> - Note : <?php echo $comment['review']; ?>/5 - <?php echo $comment['created_at']; ?></i>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun commentaire pour le moment.</p>
        <?php endif; ?>

        <form action="submit_comment.php" method="POST">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">

            <p>
                <label for="comment">Postez un commentaire</label><br>
                <textarea id="comment" name="comment" rows="3" required></textarea>
            </p>

            <p>
                <label for="review">Votre note (0 à 5)</label><br>
                <input type="number" id="review" name="review" min="0" max="5" required>
            </p>

            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>

    </div>

    <?php include_once('footer.php'); ?>

</body>
</html>