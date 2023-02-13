<?php

namespace core;

use core\View;

abstract class BaseController
{
    public $route;
    public $view;

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = new View();
    }
}