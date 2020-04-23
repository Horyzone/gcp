<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;

use App\Middlewares;

// Login
$app->group('', function ($app) {
    $app->get('/auth', AuthController::class. ':getLogin')->setName('login');
    $app->post('/auth', AuthController::class. ':postLogin');
})
->add(new Middlewares\CsrfMiddleware($container))
->add('csrf');

// All pages get type
$app->group('', function ($app) {
    // Home page
    $app->get('/', HomeController::class. ':getHome')->setName('home');

    // Se déconnecter
    $app->get('/logout', AuthController::class. ':getLogout')->setName('logout');
})
->add(new App\Middlewares\ConnectedMiddleware($container));
