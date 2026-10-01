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

/**
 * Récupère tous les créatifs, avec le nombre de projets de chacun
 * (colonne nbProjets). Sert à la sidebar.
 * LEFT JOIN projets ON projets.creatif = creatifs.id : on relie chaque créatif à ses projets. Le LEFT garde aussi un créatif qui n'a aucun projet (compteur à 0).
 */
function findAllWithProjetsCount(PDO $connexion): array
{
    $sql = "SELECT creatifs.*, COUNT(projets.id) AS nbProjets
FROM creatifs
LEFT JOIN projets ON projets.creatif = creatifs.id
GROUP BY creatifs.id
ORDER BY creatifs.id;";

    $rs = $connexion->query($sql);
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère tous les créatifs, dans l'ordre de leur id.
 * Sert au menu déroulant du formulaire d'ajout / modification.
 */
function findAll(PDO $connexion): array
{
    $sql = "SELECT *
FROM creatifs
ORDER BY id;";

    $rs = $connexion->query($sql);
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}
