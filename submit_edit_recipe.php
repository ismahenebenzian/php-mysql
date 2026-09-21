<?php
session_start();
include_once('mysql.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    echo 'Vous devez être connecté.';
    return;
}

if (
    !isset($_POST['recipe_id']) ||
    !isset($_POST['title']) || empty($_POST['title']) ||
    !isset($_POST['recipe']) || empty($_POST['recipe'])
) {
    echo 'Données invalides.';
    return;
}

$recipeId = (int) $_POST['recipe_id'];
$title    = $_POST['title'];
$recipe   = $_POST['recipe'];

$sqlQuery = 'SELECT * FROM recipes WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]) or die(print_r($db->errorInfo()));
$existing = $stmt->fetch();

if (!$existing || $existing['author'] !== $_SESSION['LOGGED_USER']) {
    echo 'Accès refusé.';
    return;
}

$sqlQuery = 'UPDATE recipes SET title = :title, recipe = :recipe WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute([
    'title'     => $title,
    'recipe'    => $recipe,
    'recipe_id' => $recipeId,
]) or die(print_r($db->errorInfo()));

echo 'Recette modifiée avec succès !';
echo '<br><a href="index.php">Retour</a>';