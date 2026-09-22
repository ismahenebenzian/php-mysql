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

$sqlQuery = 'SELECT user_id FROM users WHERE email = :email';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['email' => $_SESSION['LOGGED_USER']]);
$user = $stmt->fetch();

if (!$user) {
    echo 'Utilisateur introuvable.';
    return;
}

$userId = $user['user_id'];

$sqlQuery = 'INSERT INTO recipes(user_id, title, recipe, is_enabled) 
             VALUES (:user_id, :title, :recipe, :is_enabled)';
$stmt = $db->prepare($sqlQuery);
$stmt->execute([
    'user_id'    => $userId,
    'title'      => $title,
    'recipe'     => $recipe,
    'is_enabled' => 1,
]);

header('Location: index.php');
exit;