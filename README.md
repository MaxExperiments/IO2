# IO2S2
starring by Maxime Flin et Louis Gavalda

## TODOS
- [ ] Touver ce que doit faire le site
- [ ] Completer le Model pour pouvoir faire des liens entre les tables (join left, join right...)
- [x] les updates et inserts dans les tables
- [ ] les supressions dans les tables
- [x] Ajouter un Helper de liens
- [ ] Completer le helper form pour gerer tous les types du inputs
- [x] Faire un systeme de connection utlisateur avec la session
- [ ] Ajouter comme possibilte depuis le controlleur de renvoyer du json dans la response pour faire de l'ajax
- [ ] Trouver ce que doit faire le site
- [ ] Trouver ce que doit faire le site
- [ ] Trouver ce que doit faire le site
- [ ] Trouver ce que doit faire le site
- [ ] Trouver ce que doit faire le site


## Ce que fait le site
Encore en recherche

## Notre expérience personnelle au cours de ce projet


### Une architecture de Framework made from scratch
On a souhaité s'organiser selon l'organisation Model-View-Controller (MVC). Cependant, n'étant pas autorisé d'utiliser des ressources externes pour ce projet il nous a semblé bon de rebatir l'architecture de notre projet sur des bases solides. S'inspirant modérément de Laravel, l'architecture du projet est la suivante:

- /app tous les fichiers de l'application
    - /controllers Tous les controllers
    - /model Tous les models
    - /helpers Tous les helpers utlisé dans les vues
    - /views Toutes le vues
        - /layout Les Layouts de base a importer dans les autres
    - config.php Fichier de configuration de l'application
    - routes.php Définis toutes les urls et appels de controller
- /lib Toutes le classes globale de l'application
- /public Les ressources client de l'application
- /tmp Fichier de debug, log...

#### I) La redirection d'url
On voulais que notre sites puisse recevoir des url simples et intuitives qui ne soient pas des chemins vers de fichers php. C'est à dire que à la place d'avoir l'url `/posts/singlepost.php?id=12` on préfère utiliser l'url `/posts/1`.
Pour ce faire on commence par rediriger les requètes https de la manière suivante:
* Quel que soit l'url on la redirige dans le dossier /public
* Dans le dossier public si l'url correspond au chemin juste qu'un ficher on redirige vers ce ficher sinon on redirige vers le ficher index.php
Ainsi on peut charges les ressources en utilisant une url intuitive et si gérer toutes les autres requête dans nos propres scripts. Ceci présente deux avantages majeurs:
* On sépare clairement le font et la back end. En effet le front se situe exclusivement dans le répertoire public.
* On peut choisir de gérer les exceptions, comme les posts introuvés, dans notre code ce qui donne beaucoup plus de liberté et de modularite au code de ce projet.
Cepedant toutes les urls de notre site vont maintenant sur le même ficher: index.php. Il faut donc une manière de parser les urls afin de charger le bon controller en fonction de l'url appelé. Pour ce faire, le fichier index charge le fichier `/lib/bootstrap.php` qui va include progressivement les différentes pièces indispensables de notre application. Dans ce fichier sont inclues 3 classes majeures dans notre structure MVC: Request, Router et Response.
