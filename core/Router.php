<?php

namespace Core;

use Core\Factory\ControllerFactory as ControllerFactory;

class Router
{

    /**
     * @var
     */
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->routeMatcher();
    }

    /**
     * @return mixed
     */
    private function getUrl()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    private function routeMatcher()
    {
        try {
            $url = array_filter(explode('/', $this->getUrl()));
            $routeFilter = function ($route) use ($url) {
                $route = array_filter(explode('/', $route[0]));
                if (count($url) === count($route)) {
                    for ($i = 0; $i < count($url); $i++) {
                        if (preg_match("/(?:\{)[a-z]+(?:\})/", $route[$i])) {
                            $route[$i] = $url[$i];
                        }
                    }
                    $routeString = implode("/", $route);
                    $urlString = implode("/", $url);
                    if ($routeString === $urlString) {
                        return $route;
                    }
                }

                return [];
            };
            $matchedRoute = array_filter($this->routes, $routeFilter);
            if (!empty($matchedRoute)) {
                $matchedRoute = reset($matchedRoute);
                $controllerInfo = explode("@", $matchedRoute[1]);
                list($controller, $method) = $controllerInfo;
                $controller = ControllerFactory::create($controller);
                //TODO: Treat any parameter passed by url
                $controller->$method();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
