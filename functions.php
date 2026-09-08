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
?>

    
<?php
// exemple 3
function displayAuthor(string $authorEmail, array $users) : string
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