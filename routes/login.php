<?php
return function(\Slim\Slim $app, array $userModel) {
    $app->get('/login', function() use($app) {
        $app->render('login.html');
    })->name('login');

    $app->post('/login', function() use($app, $userModel) {
        $req = $app->request();
        $credentials = ['username' => $req->post('username'), 'password' => $req->post('password')];

        if (!$userModel['exists']($credentials)) {
            $app->flash('error', 'Credentials entered were invalid.');
            $app->redirect($app->urlFor('login'));
        }

        $app->setEncryptedCookie('auth', json_encode($credentials));
        $app->redirect($app->urlFor('home'));
    });
};
