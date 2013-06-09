<?php
return function(\Slim\Slim $app, array $budgetModel, callable $auth) {
    $app->post('/budgets/:budgetId/transactions', $auth, function($budgetId) use($app, $budgetModel) {
        $req = $app->request();
        try {
            if ($req->post('snapshot') === 'true') {
                $budgetModel['snapshot']($budgetId);
            } else {
                $budgetModel['addItem']($budgetId, $req->post());
            }
        } catch(Exception $e) {
            $app->flash('error', $e->getMessage());
        }

        $app->redirect($app->urlFor('home'));
    });

    $app->delete('/budgets/:budgetId/transactions/:transactionId', $auth, function($budgetId, $transactionId) use($app, $budgetModel) {
        $budgetModel['removeItem']($budgetId, $transactionId);
        $app->redirect($app->urlFor('home'));
    });
};
