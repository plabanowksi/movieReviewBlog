<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $actor1 = new Actor();
        // $actor1->setName('Christian Bale');
        // $manager->persist($actor1); 
        // $actor2 = new Actor();
        // $actor2->setName('Dilan Dyn');
        // $manager->persist($actor2);
        // $manager->flush();

        // $this->addReference('actor_1', $actor1);
        // $this->addReference('actor_2', $actor2);
        }
}
