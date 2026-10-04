<?php

/**
 * @var array $asideCreatifs les créa'tifs, avec leur nombre de projets (nbProjets)
 * @var array $asideTags     tous les tags
 */ ?>
<div class="col-lg-4">
    <!-- Widget Créa'tifs -->
    <div class="ct-side-card">
        <h5 class="ct-side-card__head">Les créa'tifs</h5>
        <div class="ct-side-card__body">
            <ul class="ct-creatif-list">
                <?php foreach ($asideCreatifs as $creatif): ?>
                    <li>
                        <img class="ct-avatar" src="images/<?php echo $creatif['image']; ?>" alt="" />
                        <a href="#"><?php echo htmlspecialchars($creatif['pseudo']); ?></a>
                        <span class="ct-count"><?php echo $creatif['nbProjets']; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Widget Tags -->
    <div class="ct-side-card">
        <h5 class="ct-side-card__head">Tags</h5>
        <div class="ct-side-card__body">
            <ul class="ct-tags">
                <?php foreach ($asideTags as $tag): ?>
                    <li><a class="ct-tag" href="#"><?php echo htmlspecialchars($tag['nom']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>