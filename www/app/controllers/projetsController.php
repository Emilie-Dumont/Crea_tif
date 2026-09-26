<?php

namespace App\Controllers\ProjetsController;

use \PDO;
use \App\Models\ProjetsModel;

function indexAction(PDO $connexion)
{
    include_once '../app/models/projetsModel.php';
    $projets = ProjetsModel\findAll($connexion);
    global $content;
    ob_start();
    include '../app/views/projets/index.php';
    $content = ob_get_clean();
}
