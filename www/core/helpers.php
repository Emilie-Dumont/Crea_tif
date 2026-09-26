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
 * a été raccourci.
 */
function truncate(string $texte, int $longueur): string
{
    if (mb_strlen($texte) <= $longueur) {
        return $texte;
    }

    $tronque = mb_substr($texte, 0, $longueur);

    $position = mb_strrpos($tronque, ' ');

    if ($position === false) {
        return $tronque . '...';
    }

    $tronque = mb_substr($tronque, 0, $position);

    return $tronque . '...';
}
