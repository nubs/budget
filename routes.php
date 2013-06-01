<?php
return function(\Slim\Slim $app, array $models, array $middleware) {
    $loginRoute = require 'routes/login.php';
    $loginRoute($app, $models['user']);

    $homeRoute = require 'routes/home.php';
    $homeRoute($app, $models['budget'], $middleware['auth']);
};
