<?php

namespace App\Models\ProjetsModel;

use \PDO;

/**
 * Récupère une page de projets, du plus récent au plus ancien.
 * $limit  : nombre de projets à renvoyer
 * $offset : nombre de projets à sauter avant de commencer
 * Chaque projet contient aussi le pseudo de son créatif (colonne creatifPseudo).
 */
function findAll(PDO $connexion, int $limit = 10, int $offset = 0): array
{
    $sql = "SELECT projets.*, creatifs.pseudo AS creatifPseudo
FROM projets
INNER JOIN creatifs ON creatifs.id = projets.creatif
ORDER BY projets.dateCreation DESC, projets.id DESC
LIMIT :limit OFFSET :offset;";

    $rs = $connexion->prepare($sql);
    $rs->bindValue(':limit', $limit, PDO::PARAM_INT);
    $rs->bindValue(':offset', $offset, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Compte le nombre total de projets (sert à calculer le nombre de pages). ici il y a 30 projets donc il y aura 3 pages de 10 projets mais si il y a 31 projets il y aura 3 pages de 10 projets et 1 page avec 1 projet...
 */
function countAll(PDO $connexion): int
{
    $sql = "SELECT COUNT(*) FROM projets;";

    $rs = $connexion->query($sql);
    return (int) $rs->fetchColumn();
}

/**
 * Récupère un seul projet grâce à son id.
 * Renvoie null si aucun projet n'a cet id.
 */
function findOneById(PDO $connexion, int $id): ?array
{
    $sql = "SELECT *
FROM projets
WHERE id = :id;";

    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetch(PDO::FETCH_ASSOC) ?: null;
}
