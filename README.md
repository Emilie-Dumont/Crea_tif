# Crea_tif

**Crea'tifs** — portfolio de coiffeurs excentriques et de leurs projets de coupes. Site réalisé en PHP (architecture MVC, sans framework) pour l'examen de Scripts Serveurs.

## Fonctionnalités

- Liste des projets, 10 par page, avec pagination.
- Résumé de chaque projet tronqué à 100 caractères sur la liste.
- Page de détail d'un projet (créa'tif, date, résumé, texte, tags).
- Ajout, modification et suppression d'un projet.
- Formulaire avec validation côté serveur (titre obligatoire, 45 caractères maximum, créa'tif obligatoire), messages d'erreur et valeurs conservées.
- Upload d'une image (jpg, jpeg, png, gif, webp ; 2 Mo maximum) : obligatoire à l'ajout, facultative à la modification.
- Choix du créa'tif (liste déroulante) et des tags (cases à cocher, relation N-M).
- Barre latérale sur toutes les pages : créa'tifs avec leur nombre de projets, et liste des tags.
- Page 404 si le projet demandé n'existe pas.

## Technologies

- PHP (procédural, avec espaces de noms), PDO
- MySQL
- Apache (réécriture d'URL avec `.htaccess`)
- Bootstrap 4 et CSS personnalisé

## Installation

1. Placer le projet dans le dossier web du serveur local (par exemple `www` de WAMP). Le module `mod_rewrite` d'Apache doit être activé.
2. Importer la base de données avec le fichier `Documents/db/creatifs_a_jour.sql` (il crée la base `creatifs`, ses tables et ses données, y compris la colonne `resume`).
3. Copier `www/app/config/params-example.php` en `www/app/config/params.php`, puis renseigner dans ce nouveau fichier les quatre constantes de connexion à la base de données (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PWD`) avec les valeurs de son propre serveur MySQL. Le fichier `params.php` est ignoré par Git (il est dans `.gitignore`) : il reste local et n'est jamais versionné.
4. Ouvrir le site : `http://localhost/Script_Server/Crea_tif/www/public/` (adapter selon l'emplacement du projet).

Les images envoyées par les formulaires sont enregistrées dans `www/public/images/`.

## Routes

Les adresses publiques sont en anglais. Le fichier `www/public/.htaccess` les traduit en paramètres (`ressource`, `action`, `id`) lus par les routeurs.

| Route                      | Adresse                                           | Action       |
| -------------------------- | ------------------------------------------------- | ------------ |
| Accueil / liste            | `/` ou `/projects` (`?page=2` pour la pagination) | `index`      |
| Détail                     | `/projects/id/slug.html`                          | `show`       |
| Formulaire d'ajout         | `/projects/add/form.html`                         | `addForm`    |
| Ajout                      | `/projects/add/insert.html`                       | `addInsert`  |
| Formulaire de modification | `/projects/id/slug/edit/form.html`                | `editForm`   |
| Modification               | `/projects/id/slug/edit/update.html`              | `editUpdate` |
| Suppression                | `/projects/delete/id/slug.html`                   | `delete`     |

Le slug n'est pas stocké en base : il est calculé avec `slugify()` à partir du titre. Seul l'id compte pour retrouver le projet. Toute adresse inconnue renvoie vers l'accueil.

## Structure du projet

```
Crea_tif/
├── Documents/            fichiers fournis (consignes, template HTML, base de données)
│   ├── db/creatifs_a_jour.sql
│   └── template/
└── www/
    ├── app/
    │   ├── config/       params-example.php (params.php : local, non versionné)
    │   ├── controllers/  asideController.php, projetsController.php
    │   ├── models/       projetsModel.php, creatifsModel.php, tagsModel.php
    │   ├── routers/      index.php (ressource), projets.php (action)
    │   └── views/
    │       ├── projets/  index.php, show.php, form.php
    │       └── templates/
    │           ├── default.php
    │           └── partials/  _head, _nav, _hero, _main, _aside, _footer, _scripts
    ├── core/             init.php, constantes.php, connexion.php, helpers.php
    └── public/           index.php (front controller), .htaccess, css, images, vendor
```

## Fonctionnement

1. Toute requête passe par `.htaccess`, qui la traduit en paramètres, puis par `public/index.php`.
2. `core/init.php` démarre la session, charge les paramètres, les constantes, la connexion PDO et les helpers.
3. `routers/index.php` charge d'abord les données de la barre latérale (`AsideController\loadAction`), puis choisit la ressource. `routers/projets.php` choisit l'action avec un `switch`.
4. Le contrôleur demande les données aux modèles (seuls endroits où se trouve le SQL), les donne à la vue, et range le HTML obtenu dans `$content`.
5. `views/templates/default.php` assemble les partials et affiche `$content`.

## Fonctions utilitaires (`www/core/helpers.php`)

- `slugify(string $texte): string` : met en minuscules, remplace les accents, et remplace les espaces et la ponctuation (`.`, `!`, `?`, `'`, `;`) par des tirets.
- `truncate(string $texte, int $longueur): string` : coupe à l'espace juste avant le caractère demandé, supprime la ponctuation finale (`. , ; : ! ?`) et ajoute `...` ; un texte assez court est renvoyé tel quel.
- `getImageError(array $fichier): ?string` : vérifie une image envoyée par un formulaire et renvoie un message d'erreur, ou `null` si elle est valide.
- `uploadImage(array $fichier, string $dossier): ?string` : enregistre l'image sous un nom unique et renvoie ce nom, ou `null` en cas d'échec.

## Choix techniques

- Noms internes en français, comme la base de données (`projets`, `ProjetsController`, `ressource=projets`) ; adresses publiques en anglais, comme l'exige la consigne.
- Un seul formulaire (`views/projets/form.php`) pour l'ajout et la modification, alimenté par `renderFormView()` dans le contrôleur.
- Les requêtes SQL qui reçoivent des valeurs sont préparées (PDO, marqueurs nommés) pour éviter les injections SQL.
- Après un ajout, une modification ou une suppression, le contrôleur redirige vers l'accueil (`header('Location: ...')` suivi de `exit;`) pour éviter un double envoi du formulaire au rafraîchissement de la page.
- Les modèles sont chargés par chaque action avec `include_once` ; les helpers, eux, sont chargés une fois pour toutes par `init.php`.
