<?php

use Slim\Csrf\Guard;
use Slim\Views\Twig;

// Router
$container->set('router', function () use ($app) {
    return $app->getRouteCollector()->getRouteParser();
});

// Csrf
$container->set('csrf', function () use ($app) {
    $guard = new Guard($app->getResponseFactory());
    $guard->setFailureHandler(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
        $request = $request->withAttribute("csrf_status", false);
        return $handler->handle($request);
    });
    return $guard;
});

// Twig
$container->set('view', function () use ($rootPath, $app) {
    if (slim_env('CACHE')) {
        $cache = $rootPath.'/storage/cache';
    } else {
        $cache = false;
    }
    $view = Twig::create($rootPath.'/app/src/Views', [
        'cache' => $cache,
        'debug' => true
    ]);

    if (getenv('ENV') == 'dev') {
        $view->addExtension(new Twig_Extension_Debug());
    }

    return $view;
});

// Monolog
$container->set('logger', function () use ($rootPath) {
    $settings = [
        "name" => "gcp-app",
        "path" => $rootPath."/storage/logs/app.log"
    ];
    $logger = new \Monolog\Logger($settings["name"]);
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings["path"], \Monolog\Logger::WARNING));
    return $logger;
});

// EntityManager de doctrine
$container->set('em', function () use ($rootPath) {
    if (getenv('ENV') == 'dev') {
        $db = "DB_DEV";
    } elseif (getenv('ENV') == 'prod') {
        $db = "DB_PROD";
    }
    $connection = [
        'url' => getenv($db)
    ];
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        ['app/src/Entity'],
        true,
        $rootPath.'/storage/cache/doctrine',
        null,
        false
    );
    return \Doctrine\ORM\EntityManager::create($connection, $config);
});
