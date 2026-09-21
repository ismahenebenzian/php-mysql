<?php
session_start();
include_once('mysql.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    echo 'Vous devez être connecté.';
    return;
}

$recipeId = (int) $_GET['id'];

$sqlQuery = 'SELECT * FROM recipes WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]) or die(print_r($db->errorInfo()));
$recipe = $stmt->fetch();

if (!$recipe || $recipe['author'] !== $_SESSION['LOGGED_USER']) {
    echo 'Accès refusé.';
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier une recette</title>
</head>
<body>

<?php include_once('header.php'); ?>

<h1>Modifier la recette</h1>

<form action="submit_edit_recipe.php" method="POST">
    <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">

    <p>
        <label for="title">Titre</label><br>
        <input type="text" id="title" name="title" value="<?php echo $recipe['title']; ?>">
    </p>

    <p>
        <label for="recipe">Recette</label><br>
        <textarea id="recipe" name="recipe"><?php echo $recipe['recipe']; ?></textarea>
    </p>

    <button type="submit">Enregistrer</button>
</form>

<?php include_once('footer.php'); ?>

</body>
</html>