<?php

include_once '../app/controllers/projetsController.php';

switch ($_GET['action'] ?? null) {
    default:
        $hero = true;
        \App\Controllers\ProjetsController\indexAction($connexion);
        break;
}
