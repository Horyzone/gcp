<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUsers()
    {
        return $this->createQueryBuilder("u")
            ->select("u")
            ->leftjoin("u.fonction", "f")
            ->addSelect("f")
            ->leftjoin("u.profil", "pr")
            ->addSelect("pr")
            ->leftjoin("pr.permissions", "p")
            ->addSelect("p")
            ->getQuery()
            ->getResult();
    }

    public function getUserById(int $id)
    {
        $qb = $this->createQueryBuilder("u")
            ->select("u")
            ->leftjoin("u.fonction", "f")
            ->addSelect("f")
            ->leftjoin("u.profil", "pr")
            ->addSelect("pr")
            ->leftjoin("pr.permissions", "p")
            ->addSelect("p")
            ->andWhere("u.id = :id")
                ->setParameter("id", $id);
        return $qb->getQuery()->getResult();
    }

    public function getUserByEmail(string $email, bool $active = null)
    {
        $qb = $this->createQueryBuilder("u")
            ->select("u")
            ->leftjoin("u.fonction", "f")
            ->addSelect("f")
            ->leftjoin("u.profil", "pr")
            ->addSelect("pr")
            ->leftjoin("pr.permissions", "p")
            ->addSelect("p")
            ->andWhere("u.email = :email")
                ->setParameter("email", $email);
        if (!is_null($active)) {
            $qb->andWhere("u.active = :active")
                ->setParameter("active", $active);
        }
        return $qb->getQuery()->getResult();
    }
}
