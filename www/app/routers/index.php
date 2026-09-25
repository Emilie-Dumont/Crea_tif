<?php
$hero = true;
ob_start();
include '../app/views/projets/index.php';
$content = ob_get_clean();
