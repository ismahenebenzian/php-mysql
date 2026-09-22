<?php
session_start();
include_once('mysql.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    echo 'Vous devez être connecté pour commenter.';
    return;
}

if (
    !isset($_POST['recipe_id']) || !ctype_digit($_POST['recipe_id']) ||
    !isset($_POST['comment']) || empty(trim($_POST['comment'])) ||
    !isset($_POST['review']) || !ctype_digit($_POST['review'])
) {
    echo 'Il faut un commentaire et une note valides.';
    return;
}

$recipeId = (int) $_POST['recipe_id'];
$comment  = trim($_POST['comment']);
$review   = (int) $_POST['review'];

if ($review < 0 || $review > 5) {
    echo 'La note doit être comprise entre 0 et 5.';
    return;
}

$sqlQuery = 'SELECT user_id FROM users WHERE email = :email';
$stmt = $db->prepare($sqlQuery);
$stmt->execute(['email' => $_SESSION['LOGGED_USER']]);
$user = $stmt->fetch();

if (!$user) {
    echo 'Utilisateur introuvable.';
    return;
}

$userId = $user['user_id'];

$sqlQuery = 'INSERT INTO comments(user_id, recipe_id, comment, review) 
             VALUES (:user_id, :recipe_id, :comment, :review)';
$stmt = $db->prepare($sqlQuery);
$stmt->execute([
    'user_id'   => $userId,
    'recipe_id' => $recipeId,
    'comment'   => $comment,
    'review'    => $review,
]);

header('Location: recipe.php?id=' . $recipeId);
exit;