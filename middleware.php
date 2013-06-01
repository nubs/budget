<?php
return function(\Slim\Slim $app, array $models) {
    $auth = require 'middleware/auth.php';
    return ['auth' => $auth($app, $models['user'])];
};
