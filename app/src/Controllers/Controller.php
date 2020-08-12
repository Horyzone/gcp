<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

class Controller
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->get($name);
    }

    public function alert($message, $type = "success")
    {
        if (!isset($_SESSION['alert2'])) {
            $_SESSION['alert2'] = [];
        }
        $_SESSION['alert2'][$type] = $message;
    }

    public function tokenCheck($token)
    {
        if (!isset($_SESSION['token']) || empty($_SESSION['token'])) {
            return false;
        } elseif ($_SESSION['token'] === $token) {
            return true;
        } else {
            return false;
        }
    }

    public function generateToken($length = 250)
    {
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }

    public function days()
    {
        return [
            "Lundi",
            "Mardi",
            "Mercredi",
            "Jeudi",
            "Vendredi",
            "Samedi",
            "Dimanche",
        ];
    }

    public function months($key)
    {
        $tab = [
            "01" => "Janvier",
            "02" => "Février",
            "03" => "Mars",
            "04" => "Avril",
            "05" => "Mai",
            "06" => "Juin",
            "07" => "Juillet",
            "08" => "Août",
            "09" => "Septembre",
            "10" => "Octobre",
            "11" => "Novembre",
            "12" => "Décembre",
        ];
        if (isset($tab[$key])) {
            return $tab[$key];
        } else {
            null;
        }
    }

    public function getWeeks(\DateTime $date)
    {
        $start = new \DateTime($date->format("Y")."-".$date->format("m")."-01");
        $end = (clone $start)->modify("+1 month -1 day");
        $startWeek = intval($start->format("W"));
        $endWeek = intval($end->format("W"));
        if ($endWeek === 1) {
            $endWeek = intval((clone $end)->modify("-7 days")->format("W")) + 1;
        }
        $weeks = $endWeek - $startWeek + 1;
        if ($weeks < 0) {
            $weeks = intval($end->format("W"));
        }
        return $weeks;
    }

    public function getStartingDay(\DateTime $date)
    {
        return new \DateTime($date->format("Y")."-".$date->format("m")."-01");
    }

    public function render(ResponseInterface $response, $file, $params = [])
    {
        return $this->container->get("view")->render($response, $file, $params);
    }

    public function redirect(ResponseInterface $response, $name, $status = 302, $params = [])
    {
        if (empty($params)) {
            return $response->withHeader('Location', $this->container->get("router")->urlFor($name))
                ->withStatus($status);
        } else {
            return $response->withHeader('Location', $this->container->get("router")->urlFor($name, $params))
                ->withStatus($status);
        }
    }
}
