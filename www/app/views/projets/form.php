<?php

/**
 * @var array $creatifs les créa'tifs, pour le menu déroulant
 * @var array $tags     tous les tags, pour les cases à cocher
 */ ?>
<!-- VUE : formulaire d'un projet -->
<!--
    Ce même gabarit servira pour :
    /projects/add/form.html            (ajout : champs vides)
    /projects/id/slug/edit/form.html   (modification : champs pré-remplis par le contrôleur)
-->
<h1 class="mb-4">Ajouter un projet</h1>

<!-- method="post" : les données partent dans le corps de la requête (pas dans l'URL) ; enctype="multipart/form-data" : obligatoire pour envoyer un fichier -->
<form action="projects/add/insert.html" method="post" enctype="multipart/form-data" class="ct-form-card">
    <label for="titre">Titre du projet</label>
    <input
        type="text"
        name="titre"
        id="titre"
        class="form-control"
        placeholder="Ex : Frange Kamikaze" />

    <!-- Champ ajouté par rapport à la maquette : la base a une colonne "resume" -->
    <label for="resume">Résumé</label>
    <textarea
        id="resume"
        name="resume"
        class="form-control"
        rows="2"
        placeholder="Une phrase d'accroche (affichée sur l'accueil)"></textarea>

    <label for="texte">Description</label>
    <textarea
        id="texte"
        name="texte"
        class="form-control"
        rows="5"
        placeholder="Racontez l'histoire (courageuse) de ce projet..."></textarea>

    <label for="image">Photo du résultat</label>
    <div class="ct-dropzone">
        ✂️ Glissez une image ou choisissez-la ci-dessous
        <input
            type="file"
            class="form-control-file"
            id="image"
            name="image" />
    </div>

    <label for="creatif">Créa'tif</label>
    <select id="creatif" name="creatif" class="form-control">
        <option disabled selected>Sélectionnez le créa'tif</option>
        <?php foreach ($creatifs as $creatif): ?>
            <option value="<?php echo $creatif['id']; ?>"><?php echo $creatif['pseudo']; ?></option>
        <?php endforeach; ?>
    </select>

    <label>Tags <span style="font-weight:400;font-size:.8rem;color:#4a3a5a">(facultatif)</span></label>
    <div class="ct-tag-choice">
        <?php foreach ($tags as $tag): ?>
            <label><input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>" /> <?php echo $tag['nom']; ?></label>
        <?php endforeach; ?>
    </div>

    <div>
        <input class="ct-btn ct-btn--primary" type="submit" value="Enregistrer" />
        <input class="ct-btn ct-btn--ghost" type="reset" value="Réinitialiser" />
    </div>
</form>