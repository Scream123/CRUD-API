<?php

namespace core;

use core\View;

class Router
{
    protected array $routes = [];
    protected array $params = [];
    protected int $param;

    public function __construct()
    {
        $array = require 'config/routes.php';
        foreach ($array as $key => $val) {
            $this->add($key, $val);
        }
    }

    public function add($route, $params)
    {
        $route = '#^' . $route . '$#';
        $this->routes[$route] = $params;
    }

    public function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');

        foreach ($this->routes as $route => $params) {

            if (preg_match($route, $url, $matches)) {

                $this->params = $params;
                if (isset($matches[1])) {
                    $this->param = $matches[1];
                }

                return true;
            }
        }

        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $path = 'controllers\\' . ucfirst($this->params['controller']) . 'Controller';
            if (class_exists($path)) {
                if (isset($this->param)) {
                    $this->params['action'] = strstr($this->params['action'], '/', true);
                }
                $action = 'action' . ucfirst($this->params['action']);

                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);
                    if (!isset($this->param)) {
                        $controller->$action();
                    } else {
                        $controller->$action($this->param);
                    }
                } else {
                    View::errorCode(404);
                }
            } else {
                View::errorCode(404);
            }
        } else {
            View::errorCode(404);
        }
    }
}