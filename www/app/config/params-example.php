<?php


// Initialiser les zones dynamiques
$title = "";
$content = "Oups, il semble y avoir un problème.";

//Pourquoi initialiser $content ici ? Si aucune route ne remplit $content, le template affichera ce message plutôt que de planter sur une variable inexistante.


// Paramètres de connexion
define("DB_HOST", "");
define("DB_NAME", "");
define("DB_USER", "");
define("DB_PWD", "");
