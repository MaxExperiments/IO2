<?php

class Link extends Helper {

    public function route ($content, $dest, $params, $attr = []) {
        foreach (App::$route->getRoutes() as $method => $routes) {
            foreach ($routes as $url => $route) {
                if ($dest==$route) {
                    foreach ($params as $name => $value) {
                        return Response::requireView('helpers.link',
                            array_merge_recursive(['attributes'=>$attr], ['content'=>$content,'attributes'=>['href'=>str_replace('{'.$name.'}', $value, $url)]])
                        );
                    }
                }
            }
        }
        return '';
    }

}
