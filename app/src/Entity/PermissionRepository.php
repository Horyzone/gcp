<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class PermissionsRepository extends EntityRepository
{
    public function getPermissionss()
    {
        return $this->createQueryBuilder("p")
            ->getQuery()
            ->getResult();
    }
}
