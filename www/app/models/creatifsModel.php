<?php

namespace App\Models\CreatifsModel;

use \PDO;

/**
 * Récupère un seul créatif grâce à son id.
 * Renvoie null si aucun créatif n'a cet id.
 */
function findOneById(PDO $connexion, int $id): ?array
{
    $sql = "SELECT *
FROM creatifs
WHERE id = :id;";

    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetch(PDO::FETCH_ASSOC) ?: null;
}
