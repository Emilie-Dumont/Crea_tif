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

    case 'addForm':
        // ROUTE PROJETS.ADDFORM
        // PATTERN: /projects/add/form.html
        // URL: http://localhost/script_server/Crea_tif/www/public/projects/add/form.html
        // CTRL: projets
        // ACTION: addForm
        \App\Controllers\ProjetsController\addFormAction($connexion);
        break;

    case 'addInsert':
        // ROUTE PROJETS.ADDINSERT
        // PATTERN: /projects/add/insert.html
        // URL: http://localhost/script_server/Crea_tif/www/public/projects/add/insert.html
        // CTRL: projets
        // ACTION: addInsert
        \App\Controllers\ProjetsController\addInsertAction($connexion);
        break;

    case 'editForm':
        // ROUTE PROJETS.EDITFORM
        // PATTERN: /projects/id/slug/edit/form.html
        // URL: http://localhost/script_server/Crea_tif/www/public/projects/12/mon-titre/edit/form.html
        // CTRL: projets
        // ACTION: editForm
        \App\Controllers\ProjetsController\editFormAction($connexion, (int) $_GET['id']);
        break;

    case 'editUpdate':
        // ROUTE PROJETS.EDITUPDATE
        // PATTERN: /projects/id/slug/edit/update.html
        // URL: http://localhost/script_server/Crea_tif/www/public/projects/12/mon-titre/edit/update.html
        // CTRL: projets
        // ACTION: editUpdate
        \App\Controllers\ProjetsController\editUpdateAction($connexion, (int) $_GET['id']);
        break;

    case 'delete':
        // ROUTE PROJETS.DELETE
        // PATTERN: /projects/delete/id/slug.html
        // URL: http://localhost/script_server/Crea_tif/www/public/projects/delete/12/mon-titre.html
        // CTRL: projets
        // ACTION: delete
        \App\Controllers\ProjetsController\deleteAction($connexion, (int) $_GET['id']);
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
