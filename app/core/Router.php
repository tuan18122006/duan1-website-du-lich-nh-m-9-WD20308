<?php

class Router
{
    public function run()
    {
        $url = $_GET['url'] ?? 'dashboard/index';
        $url = explode('/', trim($url, '/'));

        $controllerName = ucfirst($url[0]) . "Controller";
        $method = $url[1] ?? 'index';

        $controllerFile = "../app/controllers/$controllerName.php";

        if (!file_exists($controllerFile)) {
            die("Controller $controllerName not found!");
        }

        require_once $controllerFile;
        $controller = new $controllerName;

        if (!method_exists($controller, $method)) {
            die("Method $method not found!");
        }

        $controller->$method();
    }
}
