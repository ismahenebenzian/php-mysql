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

$sqlQuery = 'SELECT * FROM recipes WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]);
$recipe = $stmt->fetch();

if (!$recipe) {
    echo 'La recette n\'existe pas.';
    return;
}

if ($recipe['author'] !== $_SESSION['LOGGED_USER']) {
    echo 'Accès refusé.';
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Supprimer une recette</title>
</head>
<body>

<?php include_once('header.php'); ?>

<h1>Supprimer <?php echo $recipe['title']; ?></h1>

<form action="submit_delete_recipe.php" method="POST">
    <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">

    <p>Voulez-vous vraiment supprimer cette recette ?</p>

    <button type="submit">Oui, supprimer</button>
    <a href="index.php">Annuler</a>
</form>

<?php include_once('footer.php'); ?>

</body>
</html>