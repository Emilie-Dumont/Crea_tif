<?php

namespace App\Controllers\AsideController;

use \PDO;
use \App\Models\CreatifsModel;
use \App\Models\TagsModel;

/**
 * Charge les données de la sidebar (créa'tifs + tags).
 * Appelée avant le routage, car la sidebar est affichée sur TOUTES les pages.
 * Elle remplit deux variables globales, lues par le partial _aside.php.
 */
function loadAction(PDO $connexion)
{
    include_once '../app/models/creatifsModel.php';
    include_once '../app/models/tagsModel.php';

    global $asideCreatifs, $asideTags;

    $asideCreatifs = CreatifsModel\findAllWithProjetsCount($connexion);
    $asideTags = TagsModel\findAll($connexion);
}
