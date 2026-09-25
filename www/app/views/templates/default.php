<!DOCTYPE html>
<html lang="fr">

<head>
    <?php include '../app/views/templates/partials/_head.php'; ?>
</head>

<body>

    <?php include '../app/views/templates/partials/_nav.php'; ?>

    <?php // Bandeau d'intro : uniquement sur la page d'accueil
    if ($hero) include '../app/views/templates/partials/_hero.php'; ?>

    <?php include '../app/views/templates/partials/_main.php'; ?>

    <?php include '../app/views/templates/partials/_footer.php'; ?>

    <?php include '../app/views/templates/partials/_scripts.php'; ?>

</body>

</html>