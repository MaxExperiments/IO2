# IO2S2
Maxime Flin & Louis Gavalda

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
    - /exception Gestion des erreurs
    - /views Toutes le vues
        - /layout Les Layouts de base a importer dans les autres
    - config.php Fichier de configuration de l'application
    - routes.php Définis toutes les urls et appels de controller
- /lib Toutes le classes globale de l'application
- /public Les ressources client de l'application
- /tmp Fichier de debug, log...

#### I) La redirection d'url
On voulais que notre site puisse recevoir des URL simples et intuitives qui ne soient pas des chemins vers des fichers PHP dégueulasses. C'est à dire que à la place d'avoir l'url `/posts/singlepost.php?id=12` on préfèrera employer `/posts/1`.
Pour ce faire on commence par rediriger les requètes https de la manière suivante:
* Quel que soit l'url on la redirige dans le dossier /public
* Ça fait bim-bim-crrrr-clouic dans la machine informachose, et hop on passe à l'étape d'après.
* Dans le dossier public si l'url correspond au chemin juste qu'un ficher on redirige vers ce ficher sinon on redirige vers le ficher index.php
Ainsi on peut charges les ressources en utilisant une url intuitive et si gérer toutes les autres requête dans nos propres scripts. Ceci présente deux avantages majeurs:
* On sépare clairement le font et la back end. En effet le front se situe exclusivement dans le répertoire public.
* On peut choisir de gérer les exceptions, comme les posts introuvés, dans notre code ce qui donne beaucoup plus de liberté et de modularite au code de ce projet.
Cepedant toutes les urls de notre site vont maintenant sur le même ficher: index.php. Il faut donc une manière de parser les urls afin de charger le bon controller en fonction de l'url appelé. Pour ce faire, le fichier index charge le fichier `/lib/bootstrap.php` qui va include progressivement les différentes pièces indispensables de notre application. Dans ce fichier sont inclues 3 classes majeures dans notre structure MVC: Request, Router et Response.
Les instances de ces classes décrivent respectivement ce que recoit le server, comment il traite l'information et ce que va renvoyer le server au client.

#### II) Le développement de l'application web 
Le site est contruit de manière à ce que les fichiers à modifier pour le réaliser se trouvent uniquement dans le dossier app. Les autres dossiers étant là pour servir de base modulable pour le plus de projets prossibles. L'ajout d'une nouvelle page se décompose ici en plusieurs étapes:
* Ajouter la nouvelle url au fichier routes.php
* Créer le controller et la fonction associée 
* Charger et "rendre" une vue dans le controller
* Créer la vue correspondante

S'ajoute à ca la potentielle création de models pour interagir avec la base de donnée, la création ou gestion des erreurs en ajoutant des exceptions qui pourraient ne pas encore exister.

Dans cette partie nous allons détailler ces étapes du développement.

#### A) Les routes
Le fichier routes.php se trouvant à la base du dossier `app` contient toutes les urls auquelles l'application est capable d'associer un controller et une action, c'est à dire une méthode de ce controller. On décrit les urls de la manière suivante:

```php
    App::$route->get(url, action) // pour une requete de type GET uniquement
    App::$route->post(url, action) // pour une requete de type POST uniquement
    App::$route->put(url, action) // pour une requete de type PUT uniquement
```

Dans les lignes précédentes `url` est l'url appellé dans la requette. On peux y ajouter des paramètres pour que cette url décrive un ensemble d'urls. Par exemple on peut vouloir que la page /posts/1 et /posts/254 appellent toutes les deux la même méthode du controller mais avec une variable différente qui correspondrait ici à l'id du post. On écrit alors ce paramètres `{nom_du_parametre}` dans l'url.

Il faut cependant spécifier le type du paramètre avec une regex. Pour ce faire on utilise la fonction `App::$route->setPattern(nom,regex)`. Alors la router cherchera le paramètre d'url de manière à ce qu'il corresponde à l'expression régulière donnée. Dans le cas des posts décrit au dessus on ajouterait le code suivant au ficher `app/routes.php`:


```php
    App::$route->setPattern('id','[0-9]+');
    App::$route->get('/posts/{id}', 'Controller@methode');
```

Le Router donne aussi la possibilité de mettre des filtres sur les urls. Par exemple les pages de création de posts étant réservés aux utilisateurs connectés, on ajoute un filtre "authenticate" à cette url:

```php
    App::$route->filter(['authenticate'], function () {
        App::$route->get('/posts/new', 'Controller@method');
    });
```

Ainsi la fonction donnée en paramètre n'est executé que si **tous** les filtres du tableau renvoient true. On peut aussi prendre la négation d'un filtre et ajouter un ensemble d'urls dans le cas ou **au moins un** des filtres n'est pas validé. 

```php
    App::$route->filter(['filtre1','!negation', function () {
        // si tous les filtres sont validés   
    }, function () {
        // si au moins un filtre n'est pas validé   
    });
```

### B) Les controllers
On a vu dans la section précédente comment l'application web redirigais dynamiquement les urls sur des méthodes spécifiques des controllers. Nous allons maintenant voir comment les controllers nous permettent simplement de rendre une réponse en prenant en compte la requête. 

Un controller est une classe qui hérite de `BaseController` et qui se trouve dans le dossier `app/controllers/BaseController.php`. Le fichier dans lequel se trouve le controller doit être `app/controller/NomController.php`où nom est le nom du controller. PostsController ou UsersController par exemple. Le code minimum d'un controller est donc:

```php
    class PostsController extends BaseController {

    }
```

Le controller a plusieurs attributs qui peuvent être modifiés selon les besoins:
* Le layout: `$this->layout` est le nom de la vue globale dans laquelle on ajoute le contenu des vues chargées dans les controller
* Les models: `$this->model` est le tableau de tous les models a charger dans les controller quand il est appellé par l'application
* Les helpers: `$this->autoLoadHelpers` est un tableau de tous les helpers chargées dans les vues

Ces attributs sont spécifiques pour chaque controller et valent respectivement par défaut "basics", \["NomDuControllerAuSingulier"\] et \["Html"\]. Ils peuvent être changés à tout moments dans le controller. 

Admettons maintenant que la route appelle l'action `PostsController@index`. Alors la méthode `index` de la classe `PostsController` sera appellée. La syntaxe pour cette méthode est la suivante:

```php
    class PostsController extends BaseController {
        
        public function index ($param_url1, $param_url2) {
            // je fais mes actions: requêtes sql, traitement des données
        }

    }
```

On remarque que les paramètres d'url définis dans les routes apparaisent dans les paramètres de la méthode alors appellée.

On doit alors renvoyer une vue. Pour ce faire on appelle l'object `Response` dont l'instance est dans la class `App`. Les détails de cet objet serons donnés plus tard. Pour renvoyer une vue on utilise la fonction `requireView`. Cette fonction va stocker le contenu de ce qu'affiche la vue dans une variable et la retourner. Elle prend plusieurs paramètres: 
* le chemin depuis `app/view` avec comme séparateurs pour les dossiers un point 
* un tableau associatif des variables à injecter dans la vue
* les helpers dont la vue peut avoir besoin

Si cette vue est l'affichage définitif de la page on peut utiliser la fonction `view` de l'objet Response toujours qui va injecter cette vue dans le layout défini.

Le code deviens donc
```php
    class PostsController extends BaseController {
        
        public function index () {
            // selection via un model des post dans la variable $posts
            App::$response->view('posts.index',['posts'=>$posts]);
        }

    }
```

### C) Les Models
On veux maintenant pouvoir selectionner les éléments dans la base de données. Pour ce faire on utilise des models. Les models se trouvent dans le dossier `app/models`. Ces classes héritent toutes de la classe `Model` qui gère la plupart des cas que l'application doit gerer. Les models définient au cours du développement du site sont donc réduits à un ensemble de variables qui définissent la structure de la base de données. 
Les variables à définir sont donc 
* `$table` le nom de la table associée au model
* `$belongsTo` un tableau de toutes la relations du type "appartient à". Par exemple un post appartient à un utilisateur. Ce champs activera la selection automatique de l'utilisateur associé à un post.
* `$hash` les variables a crypter dans la base de données 
* `$attributes` le type de champs de la base de données. On entend par type le type de formulaire associé au champ
* `$validation` un tableau des filtres a valider par les données pour être misent dans la base de données

Ainsi le model `app/models/Post.php` s'écrit par exemple:

```php
    class Post extends Model {
        
        protected $table = 'posts';

        public $attributes = [
            'title' => 'text',
            'content' => 'textarea'
        ];

        public $validation = [
            'title' => ['required','max:50'],
            'content' => ['required, 'min:10']
        ]
    }
```

Une fois le model définit il faut l'utiliser dans les controller. 



![](https://i.imgur.com/5dq1Uvu.jpg)
