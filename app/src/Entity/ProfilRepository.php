<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class ProfilRepository extends EntityRepository
{
    public function queryGetProfils()
    {
        return $this->createQueryBuilder("p")
            ->getQuery()
            ->getResult();
    }
}
