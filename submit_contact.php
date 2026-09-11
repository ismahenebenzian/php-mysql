<!-- submit_contact.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de recettes - Message reçu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container flex-grow-1">
        <!-- inclusion MENU en premier -->
        <?php include_once('header.php'); ?>

<?php
if (
(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
|| (!isset($_POST['message']) || empty($_POST['message']))
)
{
echo('Il faut un email et un message valides pour soumettre le formulaire.');
return;
}
?>
        
            <h1>Message bien reçu !</h1>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rappel de vos informations</h5>
                    <p class="card-text"><b>Email</b> : <?php echo $_POST['email']; ?></p>
                    <p class="card-text"><b>Message</b> : <?php echo $_POST['message']; ?></p>
                </div>
            </div>
        <?php
        ?>
    </div>

    <!-- inclusion du bas de page du site -->
    <?php include_once('footer.php'); ?>

    <!-- Inclusion de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>