<?php

class Router
{
    private $validRoutes = array();

    public function add($route, $function)
    {
        $this->validRoutes[] = $route;
        if ($_GET['url'] === $route) {
            $function->__invoke();
        }
    }
}
