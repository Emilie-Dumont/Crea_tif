<?php

/**
 * @var array $projet  le projet à afficher
 * @var array $creatif le créatif qui l'a réalisé
 * @var array $tags    les tags du projet
 */ ?>
<!-- VUE : détail d'un projet -->
<!--strtotime convertit une date en timestamp, date() formate un timestamp en date lisible pour php -->
<h1><?php echo $projet['titre']; ?></h1>
<p class="ct-byline">par <a href="#"><?php echo $creatif['pseudo']; ?></a> · <?php echo date('d/m/Y', strtotime($projet['dateCreation'])); ?></p>

<!-- Boutons d'action : modifier / supprimer -->
<div class="mb-4">
    <!-- Route : /projects/id/slug/edit/form.html -->
    <a href="projects/<?php echo $projet['id']; ?>/<?php echo \Core\Helpers\slugify($projet['titre']); ?>/edit/form.html" class="ct-btn ct-btn--primary">Éditer le projet</a>
    <!-- Route : /projects/delete/id/slug.html -->
    <a href="projects/delete/<?php echo $projet['id']; ?>/<?php echo \Core\Helpers\slugify($projet['titre']); ?>.html" class="ct-btn ct-btn--danger" onclick="return confirm('Supprimer définitivement ce projet ?');">Supprimer le projet</a>
</div>

<article class="ct-card">
    <div class="row">
        <div class="col-md-6">
            <img class="img-fluid mb-3 mb-md-0" src="images/<?php echo $projet['image']; ?>" alt="<?php echo $projet['titre']; ?>" />
        </div>
        <div class="col-md-6">
            <!-- Résumé en évidence -->
            <p class="lead" style="font-weight: 600"><?php echo $projet['resume']; ?></p>
            <hr />
            <!-- Texte complet -->
            <p><?php echo $projet['texte']; ?></p>

            <!-- Tags du projet (affichés seulement s'il y en a au moins un) -->
            <?php if (!empty($tags)): ?>
                <hr />
                <ul class="ct-tags">
                    <?php foreach ($tags as $tag): ?>
                        <li><a class="ct-tag" href="#"><?php echo $tag['nom']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</article>