<?php

if (isset($_GET['ressource']) && $_GET['ressource'] === 'projets') {
    include_once '../app/routers/projets.php';
} else {
    $hero = true;

    include_once '../app/controllers/projetsController.php';

    \App\Controllers\ProjetsController\indexAction($connexion);
}
