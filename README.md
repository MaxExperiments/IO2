# IO2S2
Maxime Flin & Louis Gavalda

## TODOS
- [x] Touver ce que doit faire le site
- [ ] Completer le Model pour pouvoir faire des liens entre les tables (join left, join right...)
- [x] les updates et inserts dans les tables
- [x] les supressions dans les tables
- [x] Ajouter un Helper de liens
- [ ] Completer le helper form pour gerer tous les types du inputs
- [x] Faire un systeme de connection utlisateur avec la session
- [ ] Ajouter comme possibilte depuis le controlleur de renvoyer du json dans la response pour faire de l'ajax
- [x] Trouver ce que doit faire le site
- [x] Trouver ce que doit faire le site
- [x] Trouver ce que doit faire le site
- [x] Trouver ce que doit faire le site
- [x] Trouver ce que doit faire le site


## Ce que fait le site
Un forum de discussion d'usage simple et de conception légère (va tourner sur un serveur pour sans-abris. Sqlite ?) destiné aux étudiants de l'université **Paris Diderot** : [http://diderot.club/](http://diderot.club/)
(Publication anonyme, partage de fichiers, …)

## Notre expérience personnelle au cours de ce projet
Nous sommes grandement surpris par l'enrichissement mutuel — collaboration, entraide, échanges d'idées, … — qui découle d'un tel travail de groupe. La charge de travail tend à se répartir équitablement, chacun trouve son rôle et œuvre dans des domaines pour lesquels il possède une inclination bien spécifique : les potentialités de tous les membres du groupe sont pleinement exploitées, ce qui est formidable.
Maxime a été un peu difficile à mettre au travail, mais il commence à se faire à l'idée qu'il s'agit d'un projet réalisé _en commun_, il ne se rebiffe plus lorsqu'on lui demande de mettre pierre à l'édifice (il accepte par exemple de tester le système de connexion, et va jusqu'à imaginer de _fausses_ adresses mails pour simuler l'ajout de plusieurs utilisateurs à la base de données).

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

![https://i.imgur.com/5dq1Uvu.jpg]()
