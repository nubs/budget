<?php
return function(\Slim\Slim $app, array $budgetModel, callable $auth) {
    $app->get('/', $auth, function() use($app, $budgetModel) {
        $app->render('home.html', ['budgets' => $budgetModel['find']()]);
    })->name('home');
};
