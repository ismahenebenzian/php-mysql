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

// Vérifier le propriétaire
$sqlQuery = 'SELECT * FROM recipes WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]);
$recipe = $stmt->fetch();

if (!$recipe || $recipe['author'] !== $_SESSION['LOGGED_USER']) {
    echo 'Accès refusé.';
    return;
}

// Supprimer les commentaires liés (si la table comments existe)
$sqlQuery = 'DELETE FROM comments WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]);

// Supprimer la recette
$sqlQuery = 'DELETE FROM recipes WHERE recipe_id = :recipe_id';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['recipe_id' => $recipeId]);

echo 'La recette a bien été supprimée.';
echo '<br><a href="index.php">Retour</a>';