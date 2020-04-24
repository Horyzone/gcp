<?php

namespace DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\User;

class UserFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail("admin@entreprise.fr");
        $user->setPassword("admin");
        $user->setFirstName("Admin");
        $user->setLastName("Admin");
        $user->setFonction(
            $this->getReference("fonction")
        );
        $user->setProfil(
            $this->getReference("admin")
        );
        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
