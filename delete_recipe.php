<?php
session_start();
include_once('mysql.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    echo 'Vous devez être connecté.';
    return;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo 'Il faut un identifiant de recette pour la supprimer.';
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

if ($recipe['email'] !== $_SESSION['LOGGED_USER']) {
    echo 'Accès refusé.';
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer une recette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container mt-4">

        <h1>Supprimer la recette</h1>

        <p>Voulez-vous vraiment supprimer cette recette ?</p>
        <h4><?php echo $recipe['title']; ?></h4>

        <form action="submit_delete_recipe.php" method="POST">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">

            <button type="submit" class="btn btn-danger">Oui, supprimer</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </form>

    </div>

    <?php include_once('footer.php'); ?>

</body>
</html>