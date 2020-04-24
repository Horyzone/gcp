<?php

namespace App\Middlewares;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ConnectedMiddleware
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        if (!isset($_SESSION["auth"])) {
            return (new Response())->withHeader("Location", $this->container->get("router")->urlFor("login"))
                ->withStatus(301);
        } else {
            $user = $this->container->get("em")
                ->getRepository("App\Entity\User")->getUserById($_SESSION["auth"]["id_user"]);
            if (!is_null($user) && is_array($user) && count($user) === 1) {
                if ($user[0]->isActive() != true) {
                    if (!isset($_SESSION["alert2"])) {
                        $_SESSION["alert2"] = [];
                    }
                    $_SESSION["alert2"]["danger"] = ["Compte utilisateur désactivé"];
                    unset($_SESSION["auth"]);
                    unset($_SESSION["csrf"]);
                    unset($_SESSION["token"]);
                    return (new Response())->withHeader("Location", $this->container->get("router")->urlFor("login"))
                        ->withStatus(301);
                }
            } else {
                if (!isset($_SESSION["alert2"])) {
                    $_SESSION["alert2"] = [];
                }
                $_SESSION["alert2"]["danger"] = ["Compte utilisateur introuvable"];
                unset($_SESSION["auth"]);
                unset($_SESSION["csrf"]);
                unset($_SESSION["token"]);
                return (new Response())->withHeader("Location", $this->container->get("router")->urlFor("login"))
                    ->withStatus(301);
            }
            $_SESSION["auth"] = [
                "id_user" => $user[0]->getId(),
                "email" => $user[0]->getEmail(),
                "nom" => $user[0]->getLastName(),
                "prenom" => $user[0]->getFirstName(),
                "id_fonction" => $user[0]->getFonction()->getId(),
                "fonction" => $user[0]->getFonction()->getName(),
                "id_profil" => $user[0]->getProfil()->getId(),
                "profil" => $user[0]->getProfil()->getName(),
                "permissions" => $user[0]->getProfil()->getPermissions()
            ];
            $this->container->get("view")->getEnvironment()->addGlobal("auth", $_SESSION["auth"]);
            return $handler->handle($request);
        }
    }
}
