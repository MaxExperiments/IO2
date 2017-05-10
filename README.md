# IO2S2
Maxime Flin & Louis Gavalda

## Ce que fait le site
Un forum de discussion d'usage simple et de conception légère (va tourner sur un serveur pour sans-abris. Sqlite ?) destiné aux étudiants de l'université **Paris Diderot** : [http://diderot.club/](http://diderot.club/)
(Publication anonyme, partage de fichiers, …)

## Notre expérience personnelle au cours de ce projet
Nous sommes grandement surpris par l'enrichissement mutuel — collaboration, entraide, échanges d'idées, … — qui découle d'un tel travail de groupe. La charge de travail tend à se répartir équitablement, chacun trouve son rôle et œuvre dans des domaines pour lesquels il possède une inclination bien spécifique : les potentialités de tous les membres du groupe sont pleinement exploitées, ce qui est formidable.

Maxime a été un peu difficile à mettre au travail, mais il commence à se faire à l'idée qu'il s'agit d'un projet réalisé _en commun_, il ne se rebiffe plus lorsqu'on lui demande de mettre pierre à l'édifice (il accepte par exemple de tester le système de connexion, et va jusqu'à imaginer de _fausses_ adresses mails pour simuler l'ajout de plusieurs utilisateurs à la base de données).

## Une architecture de Framework made from scratch
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

### Setup l'application
Pour préparer l'application à son déployement il faut preparer les base de donées. Pour ce faire dans le terminal, depuis le repertoire de base de l'application executer la commande:
```
    php serve init
```

Elle affichera le fichier sql à executer. Il faut aussi changer la configuration de la base données dans le fichier `app/config.php`.

### I) La redirection d'url
On voulais que notre site puisse recevoir des URL simples et intuitives qui ne soient pas des chemins vers des fichers PHP dégueulasses. C'est à dire que à la place d'avoir l'url `/posts/singlepost.php?id=12` on préfèrera employer `/posts/1`.
Pour ce faire on commence par rediriger les requètes https de la manière suivante:
* Quel que soit l'url on la redirige dans le dossier /public
* Ça fait bim-bim-crrrr-clouic dans la machine informachose, et hop on passe à l'étape d'après.
* Dans le dossier public si l'url correspond au chemin juste qu'un ficher on redirige vers ce ficher sinon on redirige vers le ficher index.php
Pour faire ces redirections d'url nous avons plusieurs options. Sur notre server nous l'avons fait directement avec nginx. En local nous avons tous les deux utilisé des fichiers `.htaccess` pour rediriger sur apache. Et pour finir nous avons aussi crée un petit script serve qui lance l'application sur un server php local (de manière un peu analogue à nginx mais en php pur cette fois).
Ainsi on peut charges les ressources en utilisant une url intuitive et si gérer toutes les autres requête dans nos propres scripts. Ceci présente deux avantages majeurs:
* On sépare clairement le font et la back end. En effet le front se situe exclusivement dans le répertoire public.
* On peut choisir de gérer les exceptions, comme les posts introuvés, dans notre code ce qui donne beaucoup plus de liberté et de modularite au code de ce projet.
Cepedant toutes les urls de notre site vont maintenant sur le même ficher: index.php. Il faut donc une manière de parser les urls afin de charger le bon controller en fonction de l'url appelé. Pour ce faire, le fichier index charge le fichier `/lib/bootstrap.php` qui va include progressivement les différentes pièces indispensables de notre application. Dans ce fichier sont inclues 3 classes majeures dans notre structure MVC: Request, Router et Response.
Les instances de ces classes décrivent respectivement ce que recoit le server, comment il traite l'information et ce que va renvoyer le server au client.

![Structure de l'application](tmp/appDiagram.png)

### II) Le développement de l'application web 
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

Une fois le model définit il faut l'utiliser dans les controller. Pour ce faire on appelle l'instance du model charge dans le controller et on utilise les méthodes suivantes.

#### Pour selectionner des données
On utilise la méthode get. Par exemple 

```php
    $posts = $this->post->get();
```

On peut ajouter à cet appel des conditions comme un ordre, les champs à selectionner ou encore des conditions sur ces champs. Ces fonctions sont faites pour êtres composées avant l'appel de le méthode get. Ainsi on utilise la syntaxe suivante

```php
    $posts = $this->post
                    ->select(['alias1'=>'col1','alias2'=>'col2'])
                    ->order('id','desc')
                    ->where('col3','>','2')
                    ->get();
```
La seule contrainte est d'appeller le `get` en dernier. 

#### Pour inserer des données
```php
    $this->post->insert([
        'col1' => 'val1',
        'col2' => 'val2',
        // ...
    ]);
```

#### Pour modifier des données
```php
    $this->post->update ([
        'col1' => 'newval1',
        'col2' => 'newval2',
        // ...
    ]);
```

Pour la méthode update on peut aussi composer la fonction `where`

#### Pour supprimer des données 
On utilise la méthode `delete` qui a une syntaxe et une utilisation identique à la méthode get.

### D) Les Filtres
On a parlé précédement pour les models et pour les routes de filtres. Nous allons ici en parler plus précisément et expliquer comment créer de nouveaux filtres. Tous les filtres se trouvent dans le fichier `app/Filters.php`. On trouve dans ce fichier 2 namespaces: `Router` et `Model`. Ces deux namespaces ont chacun un trait du nom de `Filters` qui contient tous les filtres du Router (resp. du Model). Pour ajouter un filtre il suffit d'ajouter une fonction dans le trait qui correspond. Ces fonctions doivent retourner `true` si les filtre est validé `false` sinon. 

Un exemple de filtre pour le model:

```php
    /**
     * Verifie que la longueur du champ n'excede pas un entier donne
     * @param  String $field  Nom du champ
     * @param  String $val Valeur du champ
     * @param  int $max    Longueure maximale
     * @return Boolean
     */
    function max($field, $val, $max) {
        return strlen($val) <= $max;
    }
```

et pour les routes:

```php
    /**
     * Teste la connection d'un utilisateur
     * @return Boolean True si un utilisateur est connecté dans la session
     */
    function authenticate () {
        return \Session::isAuthenticate();
    }
```

On remarquera que les filtres peuvent prendre des paramètres qui apparaissent apres `$field` et `$val`. Dans les tableaux associatif des models les paramètres sont séparées par `:`.

### E) Les Helpers
Les Helpers sont des classes aux fonction utilitaires dans les vues. Pour generer des liens ou des formulaires de facons générique à tout le site par exemple. Ils se trouvent dans le dossier `app/helpers`. Les helpers sont appellées au moment de charger une vue dans le controller. Par exemple:

```php
    App::$response->view('posts.show', ['post'=>$post,'replies'=>$replies],['Form'=>[$this->reply]]);
```

Ici on charge le helper `Form` crée préalablement. Le tableau auquel il est associé est l'ensemble des paramètres à passer au constructeur. Dans le cas de ce helper il prend pour unique paramètre le Model lié au formulaire que l'on souhaite créer. 

Ce helper regroupe un ensemble de fonctions qui peuvent être appellées dans la vue de la manière suivante par exemple:

```php
    <?= $Form->createForm($method, ['class'=>'form']) ?>
        <?= $Form->input('title', 'Titre',['id'=>['unID']]) ?>
        <?= $Form->input('content', 'Contenu') ?>
        <?= $Form->submit('Valider') ?>
    <?= $Form->endForm() ?>
```

On notera que l'utilisation d'un helper dépend entièrement de la façon dont il est fait.

### F) Les exceptions
A cause, ou grâce, à la redirection d'url le server ne peux prendre en compte quasiement aucune erreur hors mis les erreurs internes comises par les développeurs incompétents. Il faut donc créer un système qui permette à l'application de renvoyer des erreurs. Ce système se retrouve dans les exceptions. Quand on veux par exemple renvoyer une erreur du type `404 - Not Found` dans le php on tape le code suivant: 

```php
    throw new NotFoundException("Page introuvable");
```

Cet appel de fonction va appeller la classe `NotFoundException` qui hérite de `HttpException` et qui se trouve dans `app/exceptions`. Le constructeur de cette classe va appeller le contructeur parent qui va interrompre le bon déroulement de l'application pour afficer une page d'erreur. Dans le constructeur de `NotFoundException` on peut effectuer des opérations qui dépendent de ce que l'on veux faire, comme mettre un layout précis ou encore rediriger l'utilisateur. Dans notre cas nous avons opté pour un code simple:

```php
    class NotFoundException extends BaseException {

        protected $code = 404;

        public function __construct($message, Exception $previous = null) {
            App::$response->setStatusCode($this->code);
            parent::__construct($message, $this->code, $previous);
        }

    }
```

L'exception parente (`HttpException`) se charge d'appeller la fonction `err{code\_de\_l\_erreur}` dans le controller `ErrorsController` si l'application est configurée en mode `prod` et appelle le fonction `__dev` sinon. On a pu gérer de manière simple et rapide la gestions des erreurs dans notre code.

## Dans notre cas particulier
Pour notre application web spécifiquement nous avons 3 types de données à stoquer:
* les utilisateurs
* les posts de ces utilisateurs 
* les réponses à ces posts de ces utilisateurs

Nous avons donc construit notre application autour de ca. 

### Les Routes
Les routes ont été faites de manière la plus générique et intuitive possible.

Pour les utilisateurs:
* `/login` pour se connecter
* `/register` pour s'inscrire
* `/users` pour modifier son profil
* `/users/{id}` pour voir le profil de l'utilisateur avec l'id correspondant

Pour les posts:
* `/posts` pour voir tous les posts
* `/posts/{id}` pour voir le post avec l'id correspondant
* `/posts/create` pour ajouter un nouveau post
* `/posts/{id}/edit` pour modifier un post
* `/posts/{id}/delete` pour supprimer un post

### Les Controllers
On a eu donc besoins d'un controller associé pour chaque ressources. Par convention on a donné pour nom aux controller le pluriel du nom de la ressource. On a donc 3 controllers:
* `UsersController`
* `PostsController`
* `RepliesController`

### Les Models
De la même manière que pour les controllers nous avons donc 3 models. Par convetion nous avons donné comme nom aux models le singulier du nom de la ressource. 
* `User`
* `Post`
* `Reply`

Quand à la base de donnée, est est exportable dans la fichier `/scripts/tables.sql` et a la structure suivante:

![Structure de la base de données](tmp/projectIODatabaseSchema.png)

## Choses à ameliorer

Il y a bien sur des tonnes de choses à ameliorer sur ce projet. À titre d'exemple, on aurait du ajouter une système de pagination pour les posts et même de pagination dynamique en ajax pour les replies.

![](https://i.imgur.com/5dq1Uvu.jpg)
