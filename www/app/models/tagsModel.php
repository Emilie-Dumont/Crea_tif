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
