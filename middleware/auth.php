<?php
return function(\Slim\Slim $app, array $userModel) {
    return function() use($app, $userModel) {
        $credentials = json_decode($app->getEncryptedCookie('auth'), true);
        if (!isset($credentials['username'], $credentials['password']) || !$userModel['exists']($credentials)) {
            $app->redirect($app->urlFor('login'));
        }
    };
};
