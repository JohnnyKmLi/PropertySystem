<?php

$router = new Router();

$router->add('sync', function () {
    $controller = new PropertyController();
    $controller->syncLocalDatabaseWithRemoteAPI();
});

$router->add('read', function () {
    $controller = new PropertyController();
    echo $controller->viewProperties();
});
