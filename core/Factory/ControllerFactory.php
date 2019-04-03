<?php

namespace Core\Factory;

class ControllerFactory
{
    public static function create($controller)
    {
        $controller = "App\\Controllers\\{$controller}";
        return new $controller();
    }
}
