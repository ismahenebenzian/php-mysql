<?php
session_start();
include_once('mysql.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    echo 'Vous devez être connecté.';
    return;
}

if (
    !isset($_POST['title']) || empty(trim($_POST['title'])) ||
    !isset($_POST['recipe']) || empty(trim($_POST['recipe']))
) {
    echo 'Il faut un titre et une recette valides.';
    return;
}

$title  = trim($_POST['title']);
$recipe = trim($_POST['recipe']);
$author = $_SESSION['LOGGED_USER'];

$sqlQuery = 'INSERT INTO recipes(title, recipe, author, is_enabled) 
             VALUES (:title, :recipe, :author, :is_enabled)';

$insertRecipe = $db->prepare($sqlQuery);
$insertRecipe->execute([
    'title'      => $title,
    'recipe'     => $recipe,
    'author'     => $author,
    'is_enabled' => 1,
]);

echo 'Recette ajoutée avec succès !';
echo '<br><a href="index.php">Retour à l\'accueil</a>';