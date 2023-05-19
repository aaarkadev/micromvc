<?php

require_once(APP_BASE.'src/Response.php');

class Router {

    private $routes = array();

    public function AddRoute(string $regex, string $className) {
        $this->routes[$regex] = $className;
    }

    public function Handle(Request $request, View $view, Db $db): Response {

        foreach($this->routes as $regex=>$className) {
            if(preg_match($regex, $request->data['server']['REQUEST_URI'])) {
                $obj = new $className;
                return $obj($request, $view, $db);
            }
        }

    }
}
