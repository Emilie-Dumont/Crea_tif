<?php

namespace App\Models\TagsModel;

use \PDO;

/**
 * Récupère tous les tags d'un projet, classés par ordre alphabétique.
 * Passe par la table intermédiaire projets_has_tags (relation N-M).
 */
function findAllByProjetId(PDO $connexion, int $projetId): array
{
    $sql = "SELECT tags.*
FROM tags
INNER JOIN projets_has_tags ON projets_has_tags.tag = tags.id
WHERE projets_has_tags.projet = :projet
ORDER BY tags.nom;";

    $rs = $connexion->prepare($sql);
    $rs->bindValue(':projet', $projetId, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère tous les tags, dans l'ordre de leur id. Sert à la sidebar.
 */
function findAll(PDO $connexion): array
{
    $sql = "SELECT *
FROM tags
ORDER BY id;";

    $rs = $connexion->query($sql);
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Associe des tags à un projet : une ligne par tag dans la table intermédiaire
 * projets_has_tags. $tagIds est le tableau des id de tags cochés (peut être vide).
 */
function insertByProjetId(PDO $connexion, int $projetId, array $tagIds): void
{
    $sql = "INSERT INTO projets_has_tags (projet, tag)
VALUES (:projet, :tag);";

    $rs = $connexion->prepare($sql);

    // La requête préparée est réutilisée pour chaque tag coché
    foreach ($tagIds as $tagId) {
        $rs->bindValue(':projet', $projetId, PDO::PARAM_INT);
        $rs->bindValue(':tag', $tagId, PDO::PARAM_INT);
        $rs->execute();
    }
}

/**
 * Supprime tous les liens d'un projet avec ses tags (lignes de projets_has_tags).
 * Sert à la modification (on efface les anciens tags puis on réinsère les nouveaux)
 * et à la suppression d'un projet. Les tags eux-mêmes (table tags) ne sont pas touchés.
 */
function deleteByProjetId(PDO $connexion, int $projetId): void
{
    $sql = "DELETE FROM projets_has_tags
WHERE projet = :projet;";

    $rs = $connexion->prepare($sql);
    $rs->bindValue(':projet', $projetId, PDO::PARAM_INT);
    $rs->execute();
}
