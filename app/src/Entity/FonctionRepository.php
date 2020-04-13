<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class FonctionRepository extends EntityRepository
{
    public function queryGetFonctions()
    {
        return $this->createQueryBuilder("f")
            ->getQuery()
            ->getResult();
    }
}
