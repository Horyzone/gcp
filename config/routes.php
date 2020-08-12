<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;

use App\Middlewares;

// Login
$app->group('', function ($app) {
    $app->get('/auth', AuthController::class. ':getLogin')->setName('login');
    $app->post('/auth', AuthController::class. ':postLogin');
})
->add(new Middlewares\RememberMiddleware($container))
->add(new Middlewares\CsrfMiddleware($container))
->add('csrf');

// Oauth google
$app->get('/auth/google', AuthController::class. ':getOauth')->setName('google');
$app->get('/auth/google/callback', AuthController::class. ':getOauthCallBack')->setName('callback');

// Se déconnecter
$app->get('/logout', AuthController::class. ':getLogout')->setName('logout');

// All pages get type
$app->group('', function ($app) {
    // Home page
    $app->get('/[calendar/{date}]', HomeController::class. ':getHome')->setName('home');
})
->add(new Middlewares\RememberMiddleware($container))
->add(new Middlewares\ConnectedMiddleware($container));
