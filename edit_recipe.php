<?php
session_start();
include_once('mysql.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    echo 'Vous devez être connecté.';
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

if (!$recipe || $recipe['email'] !== $_SESSION['LOGGED_USER']) {
    echo 'Accès refusé.';
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une recette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container mt-4">

        <h1>Modifier la recette</h1>

        <form action="submit_edit_recipe.php" method="POST">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">

            <p>
                <label for="title">Titre</label><br>
                <input type="text" id="title" name="title" value="<?php echo $recipe['title']; ?>" required>
            </p>

            <p>
                <label for="recipe">Recette</label><br>
                <textarea id="recipe" name="recipe" rows="6" required><?php echo $recipe['recipe']; ?></textarea>
            </p>

            <p>
                <label for="is_enabled">Recette publiée ?</label>
                <input type="checkbox" id="is_enabled" name="is_enabled" 
                       <?php echo $recipe['is_enabled'] ? 'checked' : ''; ?>>
            </p>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </form>

    </div>

    <?php include_once('footer.php'); ?>

</body>
</html>