<?php
require_once 'vendor/autoload.php';

$mongoUrl = getenv('MONGOHQ_URL') ?: die('Missing MONGOHQ_URL environment variable');
$cookieSecretKey = getenv('COOKIE_SECRET_KEY') ?: die('Missing COOKIE_SECRET_KEY environment variable');

$app = new \Slim\Slim(['cookies.lifetime' => '1 month', 'cookies.secret_key' => $cookieSecretKey, 'view' => new \Slim\Extras\Views\Twig()]);
$app->add(new \Slim\Middleware\SessionCookie(['secret' => $cookieSecretKey, 'name' => 'session']));

$models = require 'models.php';
$models = $models($mongoUrl);

$middleware = require 'middleware.php';
$middleware = $middleware($app, $models);

$routes = require 'routes.php';
$routes($app, $models, $middleware);

$app->run();
