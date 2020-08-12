<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use App\Entity\Periode;

class HomeController extends Controller
{
    public function getHome(RequestInterface $request, ResponseInterface $response)
    {
        if ($request->getAttribute('date')) {
            try {
                $date = new \DateTime($request->getAttribute('date')."-01");
                $firstDay = clone $date;
                $lastDay = (clone $date)->modify("+1 month -1 day");
            } catch (Exception $e) {
                return $this->redirect($response, "admin");
            }
        } else {
            $date = new \DateTime();
            $firstDay = new \DateTime($date->format("Y")."-".$date->format("m")."-01");
            $lastDay = (new \DateTime($date->format("Y")."-".$date->format("m")."-01"))->modify("+1 month -1 day");
        }
        // Get periodes
        $periodes = $this->em->getRepository("App\Entity\Periode")
            ->getPeriodesOrderByDates($firstDay->format("Y-m-d"), $lastDay->format("Y-m-d"));
        $daysPeriodes = [];
        $firstPeriode = false;
        for ($i=1; $i <= intval($lastDay->format("d")); $i++) {
            if ($firstPeriode == !isset($daysPeriodes[$i-1])) {
                $firstPeriode = false;
            }
            foreach ($periodes as $k => $v) {
                if ($v->getDateStart() <= (new \DateTime($date->format("Y")."-".$date->format("m")."-".$i))
                && $v->getDateEnd() >= (new \DateTime($date->format("Y")."-".$date->format("m")."-".$i))) {
                    if ($firstPeriode == false) {
                        $firstPeriode = true;
                        $daysPeriodes[$i] = [
                            "id" => $v->getId(),
                            "title" => "Disponibilité",
                            "text" => "Disponible du ".$v->getDateStart()->format("d/m/Y")." au "
                              .$v->getDateEnd()->format("d/m/Y")." pour d'éventuelles formations personnalisés"
                        ];
                    } else {
                        $daysPeriodes[$i] = [
                            "id" => $v->getId(),
                            "text" =>"&nbsp;"
                        ];
                    }
                }
            }
        }
        // Set calendar
        $today = new \DateTime();
        if (!is_null($this->months($date->format("m")))) {
            $dateTitle = $this->months($date->format("m"))." ".$date->format("Y");
        } else {
            $dateTitle = $date->format("F Y");
        }
        $weeks = $this->getWeeks($date);
        if ($this->getStartingDay($date)->format("N") === "1") {
            $day = $this->getStartingDay($date);
        } else {
            $day = $this->getStartingDay($date)->modify("last monday");
        }
        $days = $this->days();
        $title = "Tableau de bord";
        $params = compact("title", "date", "dateTitle", "weeks", "day", "days", "today", "daysPeriodes");
        return $this->render($response, 'pages/home.twig', $params);
    }

    public function postHome(RequestInterface $request, ResponseInterface $response)
    {
        return $this->redirect($response, 'home');
    }
}
