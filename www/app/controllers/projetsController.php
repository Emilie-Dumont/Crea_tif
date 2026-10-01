<?php

namespace App\Controllers\ProjetsController;

use \PDO;
use \App\Models\ProjetsModel;
use \App\Models\CreatifsModel;
use \App\Models\TagsModel;

/**
 * Affiche la liste des projets, 10 par page.
 * Le numéro de page vient de l'URL (?page=2).
 */
function indexAction(PDO $connexion): void
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
function showAction(PDO $connexion, int $id): void
{
    include_once '../app/models/projetsModel.php';
    include_once '../app/models/creatifsModel.php';
    include_once '../app/models/tagsModel.php';

    $projet = ProjetsModel\findOneById($connexion, $id);
    $tags = TagsModel\findAllByProjetId($connexion, $id);

    global $content, $title;

    // Aucun projet avec cet id : on répond "404 introuvable"
    if ($projet === null) {
        http_response_code(404);
        $content = '<p>Ce projet n\'existe pas.</p>';
        return;
    }
    // Le créatif qui a réalisé ce projet (la colonne "creatif" du projet contient son id)
    $creatif = CreatifsModel\findOneById($connexion, $projet['creatif']);
    // Le titre du projet apparaît dans l'onglet du navigateur
    $title = $projet['titre'];

    ob_start();
    include '../app/views/projets/show.php';
    $content = ob_get_clean();
}

/**
 * Prépare et affiche le formulaire (vue form.php). Elle sert à l'ajout ET à la modification :
 * ce qui change, ce sont les paramètres que les actions lui passent.
 * $formAction    : adresse où le formulaire sera envoyé
 * $formTitre     : titre de la page (h1 et onglet du navigateur)
 * $errors        : messages d'erreur à afficher (tableau vide = aucun)
 * $titre, $resume, $texte, $creatifChoisi, $tagsCoches : valeurs à mettre dans les champs
 * $imageActuelle : nom de l'image actuelle du projet (null à l'ajout)
 * Les créa'tifs (menu déroulant) et les tags (cases à cocher) sont lus ici.
 */
function renderFormView(
    PDO $connexion,
    string $formAction,
    string $formTitre,
    array $errors,
    string $titre,
    string $resume,
    string $texte,
    int $creatifChoisi,
    array $tagsCoches,
    ?string $imageActuelle
): void {
    include_once '../app/models/creatifsModel.php';
    include_once '../app/models/tagsModel.php';

    $creatifs = CreatifsModel\findAll($connexion);
    $tags = TagsModel\findAll($connexion);

    global $content, $title;
    $title = $formTitre;

    ob_start();
    include '../app/views/projets/form.php';
    $content = ob_get_clean();
}

/**
 * Affiche le formulaire d'ajout d'un projet (champs vides).
 */
function addFormAction(PDO $connexion): void
{
    renderFormView($connexion, 'projects/add/insert.html', 'Ajouter un projet', [], '', '', '', 0, [], null);
}

/**
 * Enregistre un nouveau projet envoyé par le formulaire d'ajout, puis
 * redirige vers l'accueil. Si le formulaire est invalide, il est réaffiché
 * avec les messages d'erreur et les valeurs déjà saisies.
 * Les champs texte sont dans $_POST, l'image dans $_FILES.
 */
function addInsertAction(PDO $connexion): void
{
    include_once '../app/models/projetsModel.php';
    include_once '../app/models/tagsModel.php';

    // Champs texte (trim enlève les espaces au début et à la fin)
    $titre = trim($_POST['titre'] ?? '');
    $resume = trim($_POST['resume'] ?? '');
    $texte = trim($_POST['texte'] ?? '');
    $creatifChoisi = (int) ($_POST['creatif'] ?? 0);

    // Tags cochés : un tableau d'id (vide si aucune case cochée), convertis en nombres entiers
    $tagsCoches = array_map('intval', $_POST['tags'] ?? []);

    // Validation : on note chaque problème dans le tableau $errors (un message par erreur)
    $errors = [];

    // Titre obligatoire, 45 caractères max (taille de la colonne en base)
    if ($titre === '') {
        $errors[] = 'Le titre est obligatoire.';
    } elseif (mb_strlen($titre) > 45) {
        $errors[] = 'Le titre ne peut pas dépasser 45 caractères.';
    }

    // Créa'tif obligatoire : 0 veut dire que rien n'a été choisi dans la liste
    if ($creatifChoisi < 1) {
        $errors[] = 'Veuillez choisir un créa\'tif.';
    }

    // Image obligatoire, au bon format et pas trop lourde (null = tout est bon)
    $erreurImage = \Core\Helpers\getImageError($_FILES['image'] ?? []);
    if ($erreurImage !== null) {
        $errors[] = $erreurImage;
    }

    // Si tout est valide, on enregistre l'image dans public/images/
    // (on le fait seulement s'il n'y a aucune erreur, pour ne pas laisser d'image orpheline)
    if (empty($errors)) {
        $image = \Core\Helpers\uploadImage($_FILES['image'], 'images/');

        if ($image === null) {
            $errors[] = 'L\'image n\'a pas pu être enregistrée sur le serveur.';
        }
    }

    // Au moins une erreur : on réaffiche le formulaire (sans redirection) avec les messages
    if (!empty($errors)) {
        renderFormView($connexion, 'projects/add/insert.html', 'Ajouter un projet', $errors, $titre, $resume, $texte, $creatifChoisi, $tagsCoches, null);
        return;
    }

    // Insertion du projet, puis de ses tags (on a besoin de l'id du nouveau projet)
    $projetId = ProjetsModel\insertOne($connexion, $titre, $resume, $texte, $image, $creatifChoisi);
    TagsModel\insertByProjetId($connexion, $projetId, $tagsCoches);

    // Redirection vers la page d'accueil
    header('Location: ' . PUBLIC_BASE_URL);
    exit;
}

/**
 * Affiche le formulaire de modification d'un projet, pré-rempli avec ses valeurs actuelles.
 * $id vient de l'URL (/projects/12/mon-titre/edit/form.html).
 */
function editFormAction(PDO $connexion, int $id): void
{
    include_once '../app/models/projetsModel.php';
    include_once '../app/models/tagsModel.php';

    $projet = ProjetsModel\findOneById($connexion, $id);

    // Aucun projet avec cet id : on répond "404 introuvable"
    if ($projet === null) {
        global $content;
        http_response_code(404);
        $content = '<p>Ce projet n\'existe pas.</p>';
        return;
    }

    // Les tags actuels du projet : on garde seulement leurs id (array_column), pour cocher les bonnes cases
    $tagsProjet = TagsModel\findAllByProjetId($connexion, $id);
    $tagsCoches = array_map('intval', array_column($tagsProjet, 'id'));

    renderFormView(
        $connexion,
        'projects/' . $id . '/' . \Core\Helpers\slugify($projet['titre']) . '/edit/update.html',
        'Modifier un projet',
        [],
        $projet['titre'],
        (string) $projet['resume'],
        (string) $projet['texte'],
        (int) $projet['creatif'],
        $tagsCoches,
        $projet['image']
    );
}

/**
 * Enregistre les modifications d'un projet, puis redirige vers sa page de détail.
 * Si le formulaire est invalide, il est réaffiché avec les messages d'erreur.
 * Différence avec l'ajout : l'image est facultative (si on n'en envoie pas, on garde l'ancienne).
 */
function editUpdateAction(PDO $connexion, int $id): void
{
    include_once '../app/models/projetsModel.php';
    include_once '../app/models/tagsModel.php';

    $projet = ProjetsModel\findOneById($connexion, $id);

    // Aucun projet avec cet id : on répond "404 introuvable"
    if ($projet === null) {
        global $content;
        http_response_code(404);
        $content = '<p>Ce projet n\'existe pas.</p>';
        return;
    }

    $titre = trim($_POST['titre'] ?? '');
    $resume = trim($_POST['resume'] ?? '');
    $texte = trim($_POST['texte'] ?? '');
    $creatifChoisi = (int) ($_POST['creatif'] ?? 0);
    $tagsCoches = array_map('intval', $_POST['tags'] ?? []);

    $errors = [];

    if ($titre === '') {
        $errors[] = 'Le titre est obligatoire.';
    } elseif (mb_strlen($titre) > 45) {
        $errors[] = 'Le titre ne peut pas dépasser 45 caractères.';
    }

    if ($creatifChoisi < 1) {
        $errors[] = 'Veuillez choisir un créa\'tif.';
    }

    // Image facultative : on ne la vérifie que si un fichier a été choisi dans le formulaire
    $fichier = $_FILES['image'] ?? [];
    $imageEnvoyee = isset($fichier['error']) && $fichier['error'] !== UPLOAD_ERR_NO_FILE;
    if ($imageEnvoyee) {
        $erreurImage = \Core\Helpers\getImageError($fichier);
        if ($erreurImage !== null) {
            $errors[] = $erreurImage;
        }
    }

    // Par défaut on garde l'image actuelle ; si une nouvelle est valide, on l'enregistre et elle la remplace
    $image = $projet['image'];
    if (empty($errors) && $imageEnvoyee) {
        $nouvelleImage = \Core\Helpers\uploadImage($fichier, 'images/');

        if ($nouvelleImage === null) {
            $errors[] = 'L\'image n\'a pas pu être enregistrée sur le serveur.';
        } else {
            $image = $nouvelleImage;
        }
    }

    // Au moins une erreur : on réaffiche le formulaire avec les messages
    if (!empty($errors)) {
        renderFormView(
            $connexion,
            'projects/' . $id . '/' . \Core\Helpers\slugify($projet['titre']) . '/edit/update.html',
            'Modifier un projet',
            $errors,
            $titre,
            $resume,
            $texte,
            $creatifChoisi,
            $tagsCoches,
            $projet['image']
        );
        return;
    }

    // Mise à jour du projet, puis des tags : on efface les anciens liens et on réinsère les cases cochées
    ProjetsModel\updateOne($connexion, $id, $titre, $resume, $texte, $image, $creatifChoisi);
    TagsModel\deleteByProjetId($connexion, $id);
    TagsModel\insertByProjetId($connexion, $id, $tagsCoches);

    // Redirection vers la page de détail du projet modifié (le slug vient du nouveau titre)
    header('Location: ' . PUBLIC_BASE_URL . 'projects/' . $id . '/' . \Core\Helpers\slugify($titre) . '.html');
    exit;
}

/**
 * Supprime un projet puis redirige vers l'accueil.
 * $id vient de l'URL (/projects/delete/12/mon-titre.html).
 * Ordre important : d'abord ses liens avec les tags, ensuite le projet lui-même.
 */
function deleteAction(PDO $connexion, int $id): void
{
    include_once '../app/models/projetsModel.php';
    include_once '../app/models/tagsModel.php';

    $projet = ProjetsModel\findOneById($connexion, $id);

    // Aucun projet avec cet id : on répond "404 introuvable"
    if ($projet === null) {
        global $content;
        http_response_code(404);
        $content = '<p>Ce projet n\'existe pas.</p>';
        return;
    }

    TagsModel\deleteByProjetId($connexion, $id);
    ProjetsModel\deleteOne($connexion, $id);

    // Redirection vers la page d'accueil
    header('Location: ' . PUBLIC_BASE_URL);
    exit;
}
