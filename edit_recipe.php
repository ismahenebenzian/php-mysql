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
$stmt->execute(['recipe_id' => $recipeId]);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une recette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

    <?php include_once('header.php'); ?>

    <div class="container flex-grow-1 py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <h1 class="text-center mb-4">Modifier la recette</h1>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="submit_edit_recipe.php" method="POST">
                            <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">

                            <div class="mb-3">
                                <label for="title" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?php echo $recipe['title']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="recipe" class="form-label">Recette</label>
                                <textarea class="form-control" id="recipe" name="recipe" rows="6" required><?php echo $recipe['recipe']; ?></textarea>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_enabled" name="is_enabled" 
                                       <?php echo $recipe['is_enabled'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_enabled">
                                    Recette publiée ?
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="index.php" class="btn btn-secondary">Annuler</a>
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