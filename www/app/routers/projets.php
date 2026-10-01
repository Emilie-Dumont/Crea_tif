<?php

include_once '../app/controllers/projetsController.php';

switch ($_GET['action'] ?? null) {
    case 'show':
        // ROUTE PROJETS.SHOW
        // PATTERN: /projects/id/slug.html
        // URL: http://localhost/script_server/Crea_tif/www/public/projects/12/mon-titre.html
        // CTRL: projets
        // ACTION: show
        \App\Controllers\ProjetsController\showAction($connexion, (int) $_GET['id']);
        break;

    default:
        // ROUTE PROJETS.INDEX
        // PATTERN: /projects
        // URL: http://localhost/script_server/Crea_tif/www/public/projects
        // CTRL: projets
        // ACTION: index
        $hero = true;
        \App\Controllers\ProjetsController\indexAction($connexion);
        break;
}
