<?php

/**
 * @var array       $creatifs      les créa'tifs, pour le menu déroulant
 * @var array       $tags          tous les tags, pour les cases à cocher
 * @var string      $formAction    adresse où le formulaire est envoyé (ajout ou modification)
 * @var string      $formTitre     titre de la page
 * @var array       $errors        les messages d'erreur (tableau vide si tout va bien)
 * @var string      $titre         titre saisi (vide au premier affichage de l'ajout)
 * @var string      $resume        résumé saisi
 * @var string      $texte         description saisie
 * @var int         $creatifChoisi id du créa'tif choisi (0 = aucun)
 * @var array       $tagsCoches    id des tags cochés
 * @var string|null $imageActuelle nom de l'image actuelle du projet (null à l'ajout)
 */ ?>
<!-- VUE : formulaire d'un projet -->
<!--
    Ce même gabarit sert pour :
    /projects/add/form.html            (ajout : champs vides)
    /projects/id/slug/edit/form.html   (modification : champs pré-remplis par le contrôleur)
-->
<h1 class="mb-4"><?php echo $formTitre; ?></h1>

<!-- Messages d'erreur : affichés seulement s'il y en a (alerte rouge Bootstrap) -->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- method="post" : les données partent dans le corps de la requête (pas dans l'URL) ; enctype="multipart/form-data" : obligatoire pour envoyer un fichier -->
<form action="<?php echo htmlspecialchars($formAction); ?>" method="post" enctype="multipart/form-data" class="ct-form-card">
    <label for="titre">Titre du projet</label>
    <!-- htmlspecialchars : neutralise les caractères spéciaux (guillemets, <, >) de ce que l'utilisateur a tapé avant de le remettre dans le HTML -->
    <input
        type="text"
        name="titre"
        id="titre"
        class="form-control"
        maxlength="45"
        value="<?php echo htmlspecialchars($titre); ?>"
        placeholder="Ex : Frange Kamikaze" />

    <!-- Champ ajouté par rapport à la maquette : la base a une colonne "resume" -->
    <label for="resume">Résumé</label>
    <textarea
        id="resume"
        name="resume"
        class="form-control"
        rows="2"
        placeholder="Une phrase d'accroche (affichée sur l'accueil)"><?php echo htmlspecialchars($resume); ?></textarea>

    <label for="texte">Description</label>
    <textarea
        id="texte"
        name="texte"
        class="form-control"
        rows="5"
        placeholder="Racontez l'histoire (courageuse) de ce projet..."><?php echo htmlspecialchars($texte); ?></textarea>

    <label for="image">Photo du résultat</label>
    <div class="ct-dropzone">
        <!-- Modification seulement : on montre l'image actuelle, et en choisir une autre est facultatif -->
        <?php if ($imageActuelle !== null): ?>
            <img src="images/<?php echo $imageActuelle; ?>" alt="Image actuelle" class="img-fluid mb-2" style="max-height:120px" />
            <br />
            Image actuelle : choisissez un fichier seulement pour la remplacer
            <br />
        <?php endif; ?>
        ✂️ Glissez une image ou choisissez-la ci-dessous
        <input
            type="file"
            class="form-control-file"
            id="image"
            name="image" />
    </div>

    <label for="creatif">Créa'tif</label>
    <select id="creatif" name="creatif" class="form-control">
        <!-- Le texte d'invite n'est présélectionné que si aucun créa'tif n'a été choisi -->
        <option disabled <?php echo $creatifChoisi === 0 ? 'selected' : ''; ?>>Sélectionnez le créa'tif</option>
        <?php foreach ($creatifs as $creatif): ?>
            <option value="<?php echo $creatif['id']; ?>" <?php echo $creatifChoisi === (int) $creatif['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($creatif['pseudo']); ?></option>
        <?php endforeach; ?>
    </select>

    <label>Tags <span style="font-weight:400;font-size:.8rem;color:#4a3a5a">(facultatif)</span></label>
    <div class="ct-tag-choice">
        <?php foreach ($tags as $tag): ?>
            <!-- in_array : la case est cochée si l'id du tag est dans la liste des tags déjà cochés -->
            <label><input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>" <?php echo in_array((int) $tag['id'], $tagsCoches, true) ? 'checked' : ''; ?> /> <?php echo htmlspecialchars($tag['nom']); ?></label>
        <?php endforeach; ?>
    </div>

    <div>
        <input class="ct-btn ct-btn--primary" type="submit" value="Enregistrer" />
        <input class="ct-btn ct-btn--ghost" type="reset" value="Réinitialiser" />
    </div>
</form>