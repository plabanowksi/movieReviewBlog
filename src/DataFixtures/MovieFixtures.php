<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      
        $movie = new Movie();
        $movie->setTitle('The Dark Knight');
        $movie->setReleaseYear(2008);
        $movie->setDescription('This is desc of the dark knight');
        $movie->setImagePath('https://static.wikia.nocookie.net/arkhamcity/images/d/d1/Batman.png/revision/latest/scale-to-width-down/1200?cb=20160115140157&path-prefix=pl');
        $movie->addActor($this->getReference('actor_1'));
        $movie->addActor($this->getReference('actor_2'));
        $manager->persist($movie);

        $manager->flush();
    }
}
