<?php
// Declaration du tableau des recettes
$recipes = [
    [
        'title' => 'Cassoulet',
        'recipe' => 'Etape 1 : des flageolets',
        'author' => 'mickael.andrieu@exemple.com',
        'is_enabled' => true,
    ],
    [
        'title' => 'Couscous',
        'recipe' => 'Etape 1 : de la semoule',
        'author' => 'mickael.andrieu@exemple.com',
        'is_enabled' => false,
    ],
    [
        'title' => 'Escalope milanaise',
        'recipe' => 'Etape 1 : prenez une belle escalope',
        'author' => 'mathieu.nebra@exemple.com',
        'is_enabled' => true,
    ],
];
$users = [
    [
        'full_name' => 'Mickaël Andrieu',
        'email' => 'mickael.andrieu@exemple.com',
        'age' => 34,
    ],
    [
        'full_name' => 'Mathieu Nebra',
        'email' => 'mathieu.nebra@exemple.com',
        'age' => 34,
    ],
    [
        'full_name' => 'Laurène Castor',
        'email' => 'laurene.castor@exemple.com',
        'age' => 28,
    ],
];
?>
<?php
// EXEMPLE 1 : FONCTION
function isValidRecipe(array $recipe) : bool {
if (array_key_exists('is_enabled', $recipe)) {
$isEnabled = $recipe['is_enabled'];
} else {
$isEnabled = false;
}
return $isEnabled;
}
$isCassouletValid=isValidRecipe($recipes[0]);
$isEscalopeValid=isValidRecipe($recipes[2]);
?>

    
<?php
// EXEMPLE 2 :
function getRecipes(array $recipes) : array {
$validRecipes = [];
foreach($recipes as $recipe) {
if (isValidRecipe($recipe)) {
$validRecipes[] = $recipe;
}
}
return $validRecipes;
}
// construire l'affichage HTML des recettes
foreach(getRecipes($recipes) as $recipe) {
// echo $recipe['title'] ..
}
?>

    
<?php
// exemple 3
function display_author(string $authorEmail, array $users) : string
{
    for ($i = 0; $i < count($users); $i++) {
        $author = $users[$i];
        if ($authorEmail === $author['email']) {
            return $author['full_name'] . '(' . $author['age'] . ' ans)';
        }
    }
    return 'Auteur inconnu';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Affichage des recettes</title>
</head>
<body>
    <h1>Liste des recettes de cuisine</h1>
    <?php foreach (getRecipes($recipes) as $recipe): ?>
        <?php if ($recipe['is_enabled']): ?>
            <article>
                <h3><?php echo $recipe['title']; ?></h3> 
                <i><?php echo(display_author($recipe['author'], $users)); ?></i>
            </article>
        <?php endif; ?>
    <?php endforeach; ?>

    
</body>
</html>
