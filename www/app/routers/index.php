<?php

// La sidebar est affichée sur toutes les pages : on charge ses données avant de router
include_once '../app/controllers/asideController.php';
\App\Controllers\AsideController\loadAction($connexion);

if (isset($_GET['ressource']) && $_GET['ressource'] === 'projets') {
    include_once '../app/routers/projets.php';
} else {
    $hero = true;

    include_once '../app/controllers/projetsController.php';

    \App\Controllers\ProjetsController\indexAction($connexion);
}
