<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Movie;
use App\Entity\Comments;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      
        $movie = new Movie();
        $movie->setTitle('Spider-Man: Across the Spider-Verse');
        $movie->setReleaseYear(2023);
        $movie->setDescription('Spider-Man: Across the Spider-Verse is a 2023 American epic animated superhero film featuring the Marvel Comics character Miles Morales / Spider-Man, produced by Columbia Pictures and Sony Pictures Animation in association with Marvel Entertainment, and distributed by Sony Pictures Releasing. It is the sequel to Spider-Man: Into the Spider-Verse (2018), set in a shared multiverse of alternate universes called the Spider-Verse. The film is directed by Joaquim Dos Santos, Kemp Powers and Justin K. Thompson and written by Phil Lord, Christopher Miller, who both also produce, and David Callaham. Shameik Moore voices Miles, starring alongside Hailee Steinfeld, Brian Tyree Henry, Lauren Vélez, Jake Johnson, Jason Schwartzman, Issa Rae, Karan Soni, Shea Whigham, Greta Lee, Daniel Kaluuya, Mahershala Ali and Oscar Isaac. In the film, Miles goes on an adventure with Gwen Stacy / Spider-Woman across the Multiverse, where he meets a team of Spider-People led by Miguel OHara / Spider-Man 2099 known as the Spider-Society, but comes into conflict with them over handling a new threat in the form of the Spot.');
        $movie->setImagePath('https://upload.wikimedia.org/wikipedia/en/b/b4/Spider-Man-_Across_the_Spider-Verse_poster.jpg');
        // $movie->addActor($this->getReference('actor_1'));
        // $movie->addActor($this->getReference('actor_2'));
        $createdAt = new \DateTime();

        $comment1 = new Comments();
        $comment1->setAuthor('Paweł Ł.');
        $comment1->setContent('Awesome movie');
        $comment1->setCreatedAt($createdAt);
        $comment1->setMovie($movie);

        $comment2 = new Comments();
        $comment2->setAuthor('Karol K.');
        $comment2->setContent('I love this movie');
        $comment2->setCreatedAt($createdAt);
        $comment2->setMovie($movie);
        $manager->persist($movie);
        $manager->persist($comment1);
        $manager->persist($comment2);

        $manager->flush();
    }
}
