<?php

class Html extends Helper {

    public static $scripts = [];

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

    public function addScript($url) {
        self::$scripts[] = $url;
    }

}
