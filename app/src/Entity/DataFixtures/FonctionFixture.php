<?php

namespace DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Fonction;

class FonctionFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $fonction = new Fonction();
        $fonction->setName("DRH");
        $manager->persist($fonction);
        $manager->flush();
        $this->addReference("fonction", $fonction);
    }

    public function getOrder()
    {
        return 2;
    }
}
