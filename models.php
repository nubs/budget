<?php
return function($mongoUrl) {
    $dbName = str_replace('/', '', parse_url($mongoUrl)['path']);
    $db = (new Mongo($mongoUrl))->$dbName;

    $user = require 'models/user.php';
    $budget = require 'models/budget.php';

    return ['user' => $user($db), 'budget' => $budget($db)];
};
