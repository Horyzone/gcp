<?php

namespace App\Middlewares;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class RememberMiddleware
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        if (!isset($_SESSION["auth"])) {
            // Si cookie présent, alors vérifier
            if (isset($_COOKIE["remember"]) && !empty($_COOKIE["remember"])) {
                $remember_token = $_COOKIE["remember"];
                $parts = explode("==", $remember_token);
                $user_id = $parts[0];
                $user = $this->container->get("em")->getRepository("App\Entity\User")->getUserById($user_id);
                // Si utilisateur trouvé, vérifier le token
                if (null != $user && !empty($user)) {
                    if ($user[0]->isActive()) {
                        $expected = $user_id."==".$user[0]->getRememberToken().sha1($user_id."gcp");
                        // Si token valide, initialiser la session
                        if ($expected === $remember_token) {
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
                            setcookie("remember", $remember_token, time() + 60 * 60 * 24 * 1);
                        } else {
                            // Si cookie non valide
                            setcookie("remember", null, -1);
                        }
                    } else {
                        // Si utilisateur désactivé
                        setcookie("remember", null, -1);
                    }
                } else {
                    // Si cookie mais utilisateur inexistant
                    setcookie("remember", null, -1);
                }
            }
        }
        return $handler->handle($request);
    }
}
