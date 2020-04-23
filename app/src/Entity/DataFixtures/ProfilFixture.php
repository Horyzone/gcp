<?php

namespace DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Profil;

class ProfilFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $profil = new Profil();
        $profil->setName("Administrateur");
        $manager->persist($profil);

        $profil2 = new Profil();
        $profil2->setName("Salarié");
        $manager->persist($profil2);

        $manager->flush();
        $this->addReference("admin", $profil);
    }

    public function getOrder()
    {
        return 1;
    }
}
