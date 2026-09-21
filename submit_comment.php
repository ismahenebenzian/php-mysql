<?php
session_start();
include_once('mysql.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    echo 'Vous devez être connecté pour commenter.';
    return;
}

if (
    !isset($_POST['recipe_id']) || !ctype_digit($_POST['recipe_id']) ||
    !isset($_POST['comment']) || empty(trim($_POST['comment']))
) {
    echo 'Il faut un commentaire valide.';
    return;
}

$recipeId = (int) $_POST['recipe_id'];
$comment  = trim($_POST['comment']);

$sqlQuery = 'SELECT user_id FROM users WHERE email = :email';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['email' => $_SESSION['LOGGED_USER']]);
$user = $stmt->fetch();

if (!$user) {
    echo 'Utilisateur introuvable.';
    return;
}

$userId = $user['user_id'];

$sqlQuery = 'INSERT INTO comments(user_id, recipe_id, comment) 
             VALUES (:user_id, :recipe_id, :comment)';
$stmt = $db->prepare($sqlQuery);
$stmt->execute([
    'user_id'   => $userId,
    'recipe_id' => $recipeId,
    'comment'   => $comment,
]);

header('Location: recipe.php?id=' . $recipeId);
exit;