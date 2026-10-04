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

/*fetchColumn() récupère une seule valeur (la première colonne de la première ligne), ici ce nombre.*/
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

/**
 * Insère un nouveau projet en base (la date de création est celle du moment : NOW()).
 * Renvoie l'id du projet créé, pour pouvoir lui associer ses tags.
 */
function insertOne(PDO $connexion, string $titre, string $resume, string $texte, string $image, int $creatif): int
{
    $sql = "INSERT INTO projets (titre, resume, texte, dateCreation, image, creatif)
VALUES (:titre, :resume, :texte, NOW(), :image, :creatif);";

    $rs = $connexion->prepare($sql);
    $rs->bindValue(':titre', $titre, PDO::PARAM_STR);
    $rs->bindValue(':resume', $resume, PDO::PARAM_STR);
    $rs->bindValue(':texte', $texte, PDO::PARAM_STR);
    $rs->bindValue(':image', $image, PDO::PARAM_STR);
    $rs->bindValue(':creatif', $creatif, PDO::PARAM_INT);
    $rs->execute();
    return (int) $connexion->lastInsertId();
}

/**
 * Modifie un projet existant, repéré par son id.
 * La date de création n'est pas touchée : elle reste celle de l'ajout.
 * void car on ne renvoie rien, on ne fait que modifier le projet de base.
 * Set les colonnes à modifier avec les nouvelles valeurs, et on précise quel projet modifier avec WHERE id = :id.
 */
function updateOne(PDO $connexion, int $id, string $titre, string $resume, string $texte, string $image, int $creatif): void
{
    $sql = "UPDATE projets
SET titre = :titre,
    resume = :resume,
    texte = :texte,
    image = :image,
    creatif = :creatif
WHERE id = :id;";

    $rs = $connexion->prepare($sql);
    $rs->bindValue(':titre', $titre, PDO::PARAM_STR);
    $rs->bindValue(':resume', $resume, PDO::PARAM_STR);
    $rs->bindValue(':texte', $texte, PDO::PARAM_STR);
    $rs->bindValue(':image', $image, PDO::PARAM_STR);
    $rs->bindValue(':creatif', $creatif, PDO::PARAM_INT);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
}

/**
 * Supprime un projet, repéré par son id.
 * Attention : ses liens avec les tags (table projets_has_tags) doivent être supprimés AVANT,
 * sinon la base refuse (clé étrangère).
 */
function deleteOne(PDO $connexion, int $id): void
{
    $sql = "DELETE FROM projets
WHERE id = :id;";

    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
}
