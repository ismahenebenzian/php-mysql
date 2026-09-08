<!-- index.php -->
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Site de recettes - Page d'accueil</title>
<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
rel="stylesheet"
>
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container flex-grow-1">
<!-- inclusion MENU en premier -->
<?php include_once('header.php'); ?>
<h1>Site de recettes</h1>
<!-- inclusion de entete de sute -->
<?php
include_once('variables.php');
include_once('functions.php');
?>
<?php foreach(getRecipes($recipes) as $recipe) : ?>
<article>
<h3><?php echo $recipe['title']; ?> </h3>
<div><?php echo $recipe['recipe']; ?> </div>
<i><?php echo displayAuthor($recipe['author'], $users); ?> </i>
</article>
<?php endforeach ?>
</div>
<!-- inclusion du bas de page du site -->
<?php include_once('footer.php'); ?>
    <!-- Inclusion de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</body>
</html>
