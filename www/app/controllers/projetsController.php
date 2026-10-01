<?php

namespace App\Controllers\ProjetsController;

use \PDO;
use \App\Models\ProjetsModel;

/**
 * Affiche la liste des projets, 10 par page.
 * Le numéro de page vient de l'URL (?page=2).
 */
function indexAction(PDO $connexion)
{
    include_once '../app/models/projetsModel.php';

    $parPage = 10;

    // Nombre de pages nécessaires (c'est quoi ceil () arrondi vers le haut :30 ÷ 10 = 3 pages, 31 ÷ 10 = 3,1 donc 4 pages)
    $totalProjets = ProjetsModel\countAll($connexion);
    $nbPages = (int) ceil($totalProjets / $parPage);

    // Numéro de page demandé, ramené entre 1 et la dernière page
    //L'ordre des deux if : d'abord on plafonne à la dernière page (> $nbPages), ensuite on remonte à 1 si c'est en dessous. ?page=4 donne 3, ?page=-5 donne 1.
    $page = (int) ($_GET['page'] ?? 1);
    if ($page > $nbPages) {
        $page = $nbPages;
    }
    if ($page < 1) {
        $page = 1;
    }

    // Nombre de projets à sauter
    $offset = ($page - 1) * $parPage;

    $projets = ProjetsModel\findAll($connexion, $parPage, $offset);

    global $content;
    ob_start();
    include '../app/views/projets/index.php';
    $content = ob_get_clean();
}

/**
 * Affiche le détail d'un projet.
 * $id vient de l'URL (/projects/12/mon-titre.html).
 */
function showAction(PDO $connexion, int $id)
{
    include_once '../app/models/projetsModel.php';

    $projet = ProjetsModel\findOneById($connexion, $id);

    global $content, $title;

    // Aucun projet avec cet id : on répond "404 introuvable"
    if ($projet === null) {
        http_response_code(404);
        $content = '<p>Ce projet n\'existe pas.</p>';
        return;
    }
    // Le titre du projet apparaît dans l'onglet du navigateur
    $title = $projet['titre'];

    ob_start();
    include '../app/views/projets/show.php';
    $content = ob_get_clean();
}
