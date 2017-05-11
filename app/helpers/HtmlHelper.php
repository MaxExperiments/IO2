<?php

use Michelf\Markdown;

class Html extends Helper {

    /**
     * Ensemble des scripts a charger
     * @var Array
     */
    public static $scripts = [];

    /**
     * Crée un lien vers un controller et une action et elle est définie dans les routes
     * @param  String $content Contenu du lien a afficher
     * @param  String $dest    Controller et action ciblée
     * @param  Array $params   Paramètres de la requete
     * @param  Array $attr     Attributs du lien
     * @return String          La balise html du liens correspondant
     */
    public function route ($content, $dest, $params, $attr = []) {
        foreach (App::$route->getRoutes() as $method => $routes) {
            foreach ($routes as $url => $route) {
                if ($dest==$route) {
                    $attrs = ['href'=>$url];
                    foreach ($params as $name => $value) $attrs['href'] = str_replace('{'.$name.'}', $value, $attrs['href']);
                    return Response::requireView('helpers.link',
                            array_merge_recursive(['content'=>$content,'attributes'=>$attrs],['attributes'=>$attr])
                        );
                }
            }
        }
        return '';
    }

    /**
     * Ajoute un scrips js a charger a la fin du body
     * @param String $url Liens vers le script
     */
    public function addScript($url) {
        if (!in_array($url,self::$scripts)) self::$scripts[] = $url;
    }

    /**
     * Coupe le text apres un nombre donné de charactères
     * @param  String  $string Le text à couper
     * @param  integer $chars  Le nombre de charactères à garder
     * @return String          Le text coupé
     */
    public function shortCut($string, $chars = 250) {
        preg_match('/^.{0,' . $chars. '}(?:.*?)\b/iu', $string, $matches);
        return $matches[0];
    }

    /**
     * Tranforme le markdown en html en utlisant PHP-Markdown
     * @param  String $text Text markdown
     * @return String       Equivalent Html
     */
    public function bind ($text) {
        return Markdown::defaultTransform($text);
    }

    /**
     * Affiche le bouton vers la page suivante de la pagination
     * @param  int $total Nombre total de posts
     * @param  string $text  Text à afficher dans le bouton
     * @return string        Code Html du bouton
     */
    public function nextPage($total, $text = 'Page suivante') {
        $get = App::$request->get;
        $get['page'] = (isset($get['page'])) ? $get['page']+1 : 2;
        if ($get['page']*Post::$posts_per_page - $total > Post::$posts_per_page) return false;
        $get_str = '';
        foreach ($get as $name => $val) $get_str = $name . '=' . $val . '&';
        $get_str = trim($get_str,'&');
        return Response::requireView('helpers.paginate',[
            'url'  => App::$request->url . '?' . $get_str,
            'text' => $text
        ]);
    }
    
    /**
     * Affiche le bouton vers la page précédente
     * @param  string $text Text a afficher
     * @return String       Code Html du bouton
     */
    public function previousPage($text = 'Page précédente') {
        $get = App::$request->get;
        if (isset($get['page']) && $get['page'] > 1) $get['page']-=1;
        else return false;

        $get_str = '';
        foreach ($get as $name => $val) $get_str = $name . '=' . $val . '&';
        $get_str = trim($get_str,'&');
        return Response::requireView('helpers.paginate',[
            'url'  => App::$request->url . '?' . $get_str,
            'text' => $text
        ]);
    }
}
