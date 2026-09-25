<!-- Contenu -->
<div class="container ct-content-wrap">
    <div class="row">
        <!-- Colonne principale : contenu de la page (rempli par le contrôleur) -->
        <div class="col-lg-8">
            <?php echo $content; ?>
        </div>

        <!-- Colonne latérale : identique sur toutes les pages -->
        <?php include '../app/views/templates/partials/_aside.php'; ?>
    </div>
    <!-- /.row -->
</div>