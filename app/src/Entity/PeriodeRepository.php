<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PeriodeRepository extends EntityRepository
{
    public function getPeriodes()
    {
        return $this->createQueryBuilder("p")
            ->getQuery()
            ->getResult();
    }

    public function getPeriodeById(int $id)
    {
        return $this->createQueryBuilder("p")
            ->select("p")
            ->andWhere("p.id = :id")
                ->setParameter("id", $id)
            ->getQuery()
            ->getResult();
    }

    public function getPeriodesOrderDate(string $start = null)
    {
        $qb = $this->createQueryBuilder("p")
            ->select("p");
        if (!is_null($start)) {
            $qb->where('p.dateStart >= :start')
                ->setParameter('start', $start);
        }
        return $qb->orderBy("p.dateStart", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function getPeriodesOrderByDates(string $start, string $end)
    {
        return $this->createQueryBuilder("p")
            ->select("p")
            ->where('p.dateStart >= :start')
                ->setParameter('start', $start)
            ->orWhere('p.dateEnd <= :end')
                ->setParameter('end', $end)
            ->orderBy("p.dateStart", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function getPeriodesPage($start = 0, $limit = 20, $filtres = [])
    {
        $qb = $this->createQueryBuilder("p")
            ->orderBy("p.dateStart", "DESC");

        $qb->setFirstResult($start)
            ->setMaxResults($limit);

        return count(new Paginator($qb));
    }

    public function getPeriodesWithPosition($start = 0, $limit = 20, $filtres = [])
    {
        $qb = $this->createQueryBuilder("p")
            ->orderBy("p.dateStart", "DESC");

        return $qb->setFirstResult($start)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
