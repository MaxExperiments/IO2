# IO2S2
starring by Maxime flin et Louis Gavalda

## Une architecture de Framework made from scratch
N'étant pas autorisé d'utiliser des ressources externes pour ce projet il nous a semblé bon de rebatir l'architecture de notre projet sur des bases solides. S'inspirant modérément de Laravel, l'architecture du projet est la suivante:

- /app tous les fichiers de l'application
    - /controllers Tous les controllers
    - /model Tous les models
    - /views Toutes le vues
        - /layout Les Layouts de base a importer dans les autres
    - config.php Fichier de configuration de l'application
    - routes.php Définis toutes les urls et appels de controller
- /lib Toutes le classes globale de l'application
- /public Les ressources client de l'application
- /tmp Fichier de debug, log...

## Ce que fait le site
Encore en recherche

## TODOS
* Touver ce que doit faire le site
* Completer le Model pour pouvoir faire des liens entre les tables (join left, join right...)
* Ajouter comme possibilte depuis le controlleur de renvoyer du json dans la response pour faire de l'ajax
* Trouver ce que doit faire le site
