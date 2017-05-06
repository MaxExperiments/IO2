<?php

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
                    foreach ($params as $name => $value) {
                        return Response::requireView('helpers.link',
                            array_merge_recursive(['content'=>$content,'attributes'=>['href'=>str_replace('{'.$name.'}', $value, $url)]],['attributes'=>$attr])
                        );
                    }
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

    public function shortCut($string, $chars = 250) {
        preg_match('/^.{0,' . $chars. '}(?:.*?)\b/iu', $string, $matches);
        return $matches[0];
    }

}
