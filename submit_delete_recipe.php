<?php
session_start();
include_once('mysql.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    echo 'Vous devez être connecté.';
    return;
}

if (!isset($_POST['recipe_id']) || !ctype_digit($_POST['recipe_id'])) {
    echo 'Il manque l\'identifiant de la recette à supprimer.';
    return;
}

$recipeId = (int) $_POST['recipe_id'];

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

$sqlQuery = 'DELETE FROM comments WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]);

$sqlQuery = 'DELETE FROM recipes WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]);

header('Location: index.php');
exit;