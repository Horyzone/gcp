<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

use App\Entity\User;

class AuthController extends Controller
{
    public function getLogin(RequestInterface $request, ResponseInterface $response)
    {
        if (isset($_SESSION["auth"]) && !empty($_SESSION["auth"])) {
            return $this->redirect($response, "home");
        } else {
            $title = "Se connecter";
            if (file_exists(dirname(__DIR__, 3)."/credentials.json")) {
               $google = true;
            } else {
               $google = false;
            }
            $params = compact("title", "google");
            return $this->render($response, 'auth/login.twig', $params);
        }
    }

    public function postLogin(RequestInterface $request, ResponseInterface $response)
    {
        if (isset($_SESSION["auth"]) && !empty($_SESSION["auth"])) {
            return $this->redirect($response, "home");
        } else {
            $params = $request->getParsedBody();
            if (isset($params["email"]) && isset($params["password"])) {
                if ($request->getAttribute("csrf_status") !== false) {
                    $errors = [];
                    // Email
                    if (!Validator::email()->validate($params["email"])) {
                        $errors[] = "Adresse email invalide.";
                    }
                    // Password
                    if (!isset($params["password"]) || strlen($params["password"]) < 1) {
                        $errors[] = "Mot de passe requis.";
                    }
                    // Login
                    if (empty($errors)) {
                        $user = $this->em->getRepository("App\Entity\User")
                            ->getUserByEmail($params["email"]);
                        if (is_array($user) && count($user) >= 1) {
                            if (!$user[0]->isActive()) {
                                $errors[] = "Le compte ".$user[0]->getEmail()." est désactivé.";
                                $this->alert($errors, "danger");
                                return $this->redirect($response, "login", 400);
                            } else {
                                if ($user[0]->passwordVerify($params["password"])) {
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
                                    // Notification
                                    if (isset($params["remember"]) && $params["remember"] == "on") {
                                        $remember_token = $this->generateToken();
                                        setcookie("remember", $user[0]->getId()."==".$remember_token.
                                        sha1($user[0]->getId()."gcp"), time() + 60 * 60 * 24 * 1);
                                        $user[0]->setRememberToken($remember_token);
                                        $this->em->persist($user[0]);
                                        $this->em->flush();
                                    }
                                    $this->alert(["Vous êtes connecté !"], "success");
                                    return $this->redirect($response, "home");
                                } else {
                                    if (is_null($user[0]->getPassword())) {
                                        $errors[] =
                                          "Ce compte utilise uniquement google comme méthode d'authentification.";
                                        $this->alert($errors, "danger");
                                        return $this->redirect($response, "login", 400);
                                    } else {
                                        $errors[] = "Le mot de passe ne correspond pas à l'adresse email.";
                                        $this->alert($errors, "danger");
                                        return $this->redirect($response, "login", 400);
                                    }
                                }
                            }
                        } else {
                            $errors[] = "Cette adresse email n'est associé à aucun compte.";
                            $this->alert($errors, "danger");
                            return $this->redirect($response, "login", 400);
                        }
                    } else {
                        $this->alert($errors, "danger");
                        return $this->redirect($response, "login", 400);
                    }
                } else {
                    $this->alert(["Formulaire invalide, veuillez réessayer."], "danger");
                    return $this->redirect($response, "login", 400);
                }
            } else {
                return $this->redirect($response, "login");
            }
        }
    }

    public function getLogout(RequestInterface $request, ResponseInterface $response)
    {
        if (isset($_SESSION["auth"]) && !empty($_SESSION["auth"])) {
            unset($_SESSION["auth"]);
            unset($_SESSION["csrf"]);
            unset($_SESSION["token"]);
            setcookie("remember", null, -1);
            $this->alert(["Vous êtes déconnecté !"], "success");
            return $this->redirect($response, "login");
        } else {
            return $this->redirect($response, "home");
        }
    }

    public function getOauthCallBack(RequestInterface $request, ResponseInterface $response)
    {
        if (!$this->google->getFactory()->has("google")) {
            throw new \Exception('Wrong $provider passed in url : google');
        }
        $provider = $this->google->getProvider("google");
        $accessToken = $provider->getAccessTokenByRequestParameters($_GET);
        $user = $provider->getIdentity($accessToken);
        $userEntity = $this->em->getRepository("App\Entity\User")->getUserByEmail($user->email);
        if ($userEntity != null && !empty($userEntity)) {
            $userEntity = $userEntity[0];
            if ($userEntity->isActive()) {
                // Remember token
                $remember_token = $this->generateToken();
                setcookie("remember", $userEntity->getId()."==".$remember_token.
                sha1($userEntity->getId()."gcp"), time() + 60 * 60 * 24 * 1);
                // Update entity
                $userEntity->setRememberToken($remember_token);
                if (is_null($userEntity->getGoogle())) {
                    $userEntity->setGoogle($user->id);
                }
                if (is_null($userEntity->getAvatar())) {
                    $userEntity->setAvatar($user->pictureURL);
                }
                if (is_null($userEntity->getFirstName())) {
                    $userEntity->setFirstName($user->firstname);
                }
                if (is_null($userEntity->getLastName())) {
                    $userEntity->setLastName($user->lastname);
                }
                $this->em->persist($userEntity);
                $this->em->flush();
                // Session
                $_SESSION["auth"] = [
                    "id_user" => $userEntity->getId(),
                    "email" => $userEntity->getEmail(),
                    "nom" => $userEntity->getLastName(),
                    "prenom" => $userEntity->getFirstName(),
                    "id_fonction" => $userEntity->getFonction()->getId(),
                    "fonction" => $userEntity->getFonction()->getName(),
                    "id_profil" => $userEntity->getProfil()->getId(),
                    "profil" => $userEntity->getProfil()->getName(),
                    "permissions" => $userEntity->getProfil()->getPermissions()
                ];
                // Notification
                $this->alert(["Vous êtes connecté !"], "success");
                return $this->redirect($response, "home");
            } else {
                $this->alert(["Le compte associé à cette adresse email est désactivé !"], "warning");
                return $this->redirect($response, "login");
            }
        } else {
            $this->alert(["Aucun compte associé à cette adresse email n'a été trouvé !"], "warning");
            return $this->redirect($response, "login");
        }
    }

    public function getOauth(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $provider = $this->google->getProvider("google");
            header("Location: " . $provider->makeAuthUrl());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        exit();
    }
}
