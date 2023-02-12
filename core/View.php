<?php

namespace core;

use JetBrains\PhpStorm\NoReturn;

class View
{
    public $layout = ['header', 'footer'];

    /**
     * @param $directory
     * @param array $vars
     * @return void
     */
    public function render($directory, array $vars = []): void
    {
        $surveyData = $vars;
        $path = 'views/' . $directory . '.php';
        if (file_exists($path)) {
            require 'views/layouts/' . $this->layout[0] . '.php';
            require 'views/' . $directory . '.php';
            require 'views/layouts/' . $this->layout[1] . '.php';
        }
    }

    /**
     * @param $code
     * @return void
     */
     #[NoReturn] static function errorCode($code): void
     {
        http_response_code($code);
        $path = 'views/' . $code. '.php';
        if (file_exists($path)){
            require 'views/errors/' . $code . '.php';
        }
        exit;

    }
}
