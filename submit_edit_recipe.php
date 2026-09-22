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
$isEnabled = isset($_POST['is_enabled']) ? 1 : 0;

$sqlQuery = 'SELECT r.*, u.email 
             FROM recipes r 
             JOIN users u ON r.user_id = u.user_id 
             WHERE r.recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]);
$existing = $stmt->fetch();

if (!$existing || $existing['email'] !== $_SESSION['LOGGED_USER']) {
    echo 'Accès refusé.';
    return;
}

$sqlQuery = 'UPDATE recipes 
             SET title = :title, 
                 recipe = :recipe, 
                 is_enabled = :is_enabled 
             WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute([
    'title'      => $title,
    'recipe'     => $recipe,
    'is_enabled' => $isEnabled,
    'recipe_id'  => $recipeId,
]);

header('Location: recipe.php?id=' . $recipeId);
exit;