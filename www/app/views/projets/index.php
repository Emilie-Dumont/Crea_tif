<?php

/**
 * @var array $projets   les projets de la page courante
 * @var int   $page      numéro de la page courante
 * @var int   $nbPages   nombre total de pages
 */ ?>
<!-- VUE : liste des projets (accueil) -->

<?php foreach ($projets as $projet): ?>
    <article class="ct-card">
        <div class="row">
            <div class="col-md-4">
                <a href="projet.html">
                    <img class="img-fluid mb-3 mb-md-0" src="images/<?php echo $projet['image']; ?>" alt=" <?php echo $projet['titre']; ?>" />
                </a>
            </div>
            <div class="col-md-8">
                <h3><a href="projet.html"><?php echo $projet['titre']; ?></a></h3>
                <p class="ct-byline">par <a href="#">Mister Univ'Hair</a> · 17 août 2017</p>
                <p><?php echo \Core\Helpers\truncate($projet['resume'], 100); ?></p>
                <a class="ct-btn ct-btn--primary ct-btn--sm" href="projet.html">Voir le projet</a>
            </div>
        </div>
    </article>
<?php endforeach; ?>

<!-- Pagination : 10 projets par page -->
<nav aria-label="Navigation entre les pages de projets">
    <ul class="pagination ct-pagination" style="justify-content: center">

        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="projects?page=<?php echo $page - 1; ?>">Précédent</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $nbPages; $i++): ?>
            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                <a class="page-link" href="projects?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $nbPages): ?>
            <li class="page-item">
                <a class="page-link" href="projects?page=<?php echo $page + 1; ?>">Suivant</a>
            </li>
        <?php endif; ?>

    </ul>
</nav>