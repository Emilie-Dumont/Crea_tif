<?php

namespace Core\Helpers;

/**
 * Transforme une chaîne en slug utilisable dans une URL.
 * Ex: "Café à Paris !" -> "cafe-a-paris"
 */
function slugify(string $texte): string
{
    // 1. minuscules
    $texte = strtolower($texte);

    // 2. remplacement des caractères accentués par leur équivalent simple
    $caracteresAccentues = ['à', 'â', 'ä', 'é', 'è', 'ê', 'ë', 'î', 'ï', 'ô', 'ö', 'ù', 'û', 'ü', 'ç', 'ñ'];
    $caracteresSimples   = ['a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'c', 'n'];
    $texte = str_replace($caracteresAccentues, $caracteresSimples, $texte);

    // 3. remplacement des caractères de ponctuation demandés par un tiret
    $caracteresAremplacer = [' ', '.', '!', '?', "'", ';'];
    $texte = str_replace($caracteresAremplacer, '-', $texte);

    // 4. on retire les tirets en trop en début/fin de chaîne mais pas si il y a un tiret au milieu bon-jour le tiret reste !
    $texte = trim($texte, '-');

    return $texte;
}

/**
 * Tronque un texte à $longueur caractères, en coupant à l'espace
 * juste avant le $longueur-ème caractère, et ajoute "..." si le texte
 * a été raccourci. Si le texte coupé se termine par une ponctuation
 * (. , ; : ! ?), on la supprime avant d'ajouter "...".
 */
function truncate(string $texte, int $longueur): string
{
    // Texte assez court : on le renvoie tel quel
    //mb_strlen compte les caractères (et gère les accents/UTF-8).
    if (mb_strlen($texte) <= $longueur) {
        return $texte;
    }

    // On garde les $longueur premiers caractères, mb_substr découpe le texte en tenant compte des accents et des caractères spéciaux, sans risquer de corrompre le texte ou d'afficher des caractères bizarres.
    $tronque = mb_substr($texte, 0, $longueur);

    // On recule jusqu'au dernier espace pour ne pas couper un mot en deux
    // (s'il n'y a aucun espace, on garde les $longueur caractères tels quels)
    $position = mb_strrpos($tronque, ' ');

    if ($position !== false) {
        $tronque = mb_substr($tronque, 0, $position);
    }

    // rtrim enlève à la FIN de la chaîne tous les caractères de la liste donnée
    $tronque = rtrim($tronque, '.,;:!?');

    return $tronque . '...';
}

/**
 * Vérifie une image envoyée par un formulaire ($_FILES['image']).
 * Renvoie un message d'erreur (texte) si l'image est refusée,
 * ou null si tout est bon. N'enregistre rien : c'est uploadImage() qui s'en charge.
 */
function getImageError(array $fichier): ?string
{
    // Aucun champ image reçu, ou aucun fichier choisi dans le formulaire
    if (!isset($fichier['error']) || $fichier['error'] === UPLOAD_ERR_NO_FILE) {
        return 'Une image est obligatoire.';
    }

    // Fichier plus gros que la limite de PHP (upload_max_filesize) ou du formulaire
    if ($fichier['error'] === UPLOAD_ERR_INI_SIZE || $fichier['error'] === UPLOAD_ERR_FORM_SIZE) {
        return 'L\'image est trop lourde (2 Mo maximum).';
    }

    // Toute autre erreur d'envoi
    if ($fichier['error'] !== UPLOAD_ERR_OK) {
        return 'L\'envoi de l\'image a échoué, veuillez réessayer.';
    }

    // L'extension doit être celle d'une image,pathinfo(..., PATHINFO_EXTENSION) récupère l'extension du nom du fichier (photo.PNG donne PNG). On ne regarde que l'extension pas ce qu'il y a dans le fichier, donc ce n'est pas 100% fiable mais suffisant pour un site perso.
    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
        return 'L\'image doit être au format jpg, jpeg, png, gif ou webp.';
    }

    return null;
}

/**
 * Enregistre une image envoyée par un formulaire ($_FILES['image']) dans le dossier $dossier.
 * Renvoie le nom unique du fichier enregistré (ex. 66ff1a2b3c4d5.jpg),
 * ou null si aucune image valide n'a été envoyée.
 */
function uploadImage(array $fichier, string $dossier): ?string
{
    // 1. un fichier a-t-il bien été envoyé, sans erreur ?
    if (!isset($fichier['error']) || $fichier['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // 2. l'extension doit être celle d'une image
    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
        return null;
    }

    // 3. nom unique (évite d'écraser une image existante, et reste court : la colonne en base fait 45 caractères)
    $nom = uniqid() . '.' . $extension;

    // 4. on déplace le fichier du dossier temporaire vers le dossier final
    if (!move_uploaded_file($fichier['tmp_name'], $dossier . $nom)) {
        return null;
    }

    return $nom;
}
