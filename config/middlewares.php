<?php

use Slim\Views\TwigMiddleware;
use App\Middlewares;

$app->add(TwigMiddleware::createFromContainer($app));

// Middleware pour la connexion (se souvenir de moi)
$app->add(new Middlewares\RememberMiddleware($container));

// Middleware pour les message d'alert en session
$app->add(new Middlewares\AlertMiddleware($container));

// Middleware pour la sauvegarde des champs de saisie
$app->add(new Middlewares\OldMiddleware($container));

// Middleware pour la génération de token
$app->add(new Middlewares\TokenMiddleware($container));
