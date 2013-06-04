<?php
return function(\Slim\Slim $app, array $budgetModel, callable $auth) {
    $app->post('/budgets/:budgetId/transactions', $auth, function($budgetId) use($app, $budgetModel) {
        $req = $app->request();
        try {
            $budgetModel['addItem']($budgetId, $req->post());
        } catch(Exception $e) {
            $app->flash('error', $e->getMessage());
        }

        $app->redirect($app->urlFor('home'));
    });
};
