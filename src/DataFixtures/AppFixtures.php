<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Rating;
use App\Entity\Comments;
use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $em;
    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            'action hero',
            'alternate history',
            'ambiguous ending',
            'americana',
            'anime',
            'anti hero',
            'avant-garde',
            'b movie',
            'bank heist',
            'based on book',
            'based on play',
            'based on comic',
            'based on comic book',
            'based on novel',
            'based on novella',
            'based on short story',
            'battle',
            'betrayal',
            'biker',
            'black comedy',
            'blockbuster',
            'bollywood',
            'breaking the fourth wall',
            'business',
            'caper',
            'car accident',
            'car chase',
            'car crash',
            'character name in title',
            'character\'s point of view camera shot',
            'chick flick',
            'coming of age',
            'competition',
            'conspiracy',
            'corruption',
            'criminal mastermind',
            'cult',
            'cult film',
            'cyberpunk',
            'dark hero',
            'deus ex machina',
            'dialogue driven',
            'dialogue driven storyline',
            'directed by star',
            'director cameo',
            'double cross',
            'dream sequence',
            'dystopia',
            'ensemble cast',
            'epic',
            'espionage',
            'experimental',
            'experimental film',
            'fairy tale',
            'famous line',
            'famous opening theme',
            'famous score',
            'fantasy sequence',
            'farce',
            'father daughter relationship',
            'father son relationship',
            'femme fatale',
            'fictional biography',
            'flashback',
            'french new wave',
            'futuristic',
            'good versus evil',
            'heist',
            'hero',
            'high school',
            'husband wife relationship',
            'idealism',
            'independent film',
            'investigation',
            'kidnapping',
            'knight',
            'kung fu',
            'macguffin',
            'medieval times',
            'mockumentary',
            'monster',
            'mother daughter relationship',
            'mother son relationship',
            'multiple actors playing same role',
            'multiple endings',
            'multiple perspectives',
            'multiple storyline',
            'multiple time frames',
            'murder',
            'musical number',
            'neo noir',
            'neorealism',
            'ninja',
            'no background score',
            'no music',
            'no opening credits',
            'no title at beginning',
            'nonlinear timeline',
            'on the run',
            'one against many',
            'one man army',
            'opening action scene',
            'organized crime',
            'parenthood',
            'parody',
            'plot twist',
            'police corruption',
            'police detective',
            'post-apocalypse',
            'postmodern',
            'psychopath',
            'race against time',
            'redemption',
            'remake',
            'rescue',
            'road movie',
            'robbery',
            'robot',
            'rotoscoping',
            'satire',
            'self sacrifice',
            'serial killer',
            'shakespeare',
            'shootout',
            'show within a show',
            'slasher',
            'southern gothic',
            'spaghetti western',
            'spirituality',
            'spoof',
            'steampunk',
            'subjective camera',
            'superhero',
            'supernatural',
            'surprise ending',
            'swashbuckler',
            'sword and sandal',
            'tech-noir',
            'time travel',
            'title spoken by character',
            'told in flashback',
            'vampire',
            'virtual reality',
            'voice over narration',
            'whistleblower',
            'wilhelm scream',
            'wuxia',
            'zombie',
        ];

        foreach ($categories as $categorie) {
            $genre = new Categories();
            $genre->setName($categorie);
            $manager->persist($genre);
        }

        $tagNamesTab = ['action hero', 'hero']; //here u can isert tags that u want to have in your created movie
        $categoriesMovie1tab = [];
        foreach ($tagNamesTab as $tagName) {
            $categorieForMovie = $this->em->getRepository(Categories::class)->findOneBy(['name' => $tagName]);
            if (!$categorieForMovie) {
                $categorieForMovie = new Categories();
                $categorieForMovie->setName($tagName);
                $manager->persist($categorieForMovie);
            }
            $categoriesMovie1tab[] = $categorieForMovie;
        }
        $movie = new Movie();
        $movie->setTitle('Spider-Man: Across the Spider-Verse');
        $movie->setReleaseYear(2023);
        $movie->setDescription('Spider-Man: Across the Spider-Verse is a 2023 American epic animated superhero film featuring the Marvel Comics character Miles Morales / Spider-Man, produced by Columbia Pictures and Sony Pictures Animation in association with Marvel Entertainment, and distributed by Sony Pictures Releasing. It is the sequel to Spider-Man: Into the Spider-Verse (2018), set in a shared multiverse of alternate universes called the Spider-Verse. The film is directed by Joaquim Dos Santos, Kemp Powers and Justin K. Thompson and written by Phil Lord, Christopher Miller, who both also produce, and David Callaham. Shameik Moore voices Miles, starring alongside Hailee Steinfeld, Brian Tyree Henry, Lauren Vélez, Jake Johnson, Jason Schwartzman, Issa Rae, Karan Soni, Shea Whigham, Greta Lee, Daniel Kaluuya, Mahershala Ali and Oscar Isaac. In the film, Miles goes on an adventure with Gwen Stacy / Spider-Woman across the Multiverse, where he meets a team of Spider-People led by Miguel OHara / Spider-Man 2099 known as the Spider-Society, but comes into conflict with them over handling a new threat in the form of the Spot.');
        $movie->setImagePath('https://upload.wikimedia.org/wikipedia/en/b/b4/Spider-Man-_Across_the_Spider-Verse_poster.jpg');
        foreach ($categoriesMovie1tab as $categorieForMovie1) {
            $movie->addCategory($categorieForMovie1);
        }

        $movie2 = new Movie();
        $movie2->setTitle('The incredible Hulk');
        $movie2->setReleaseYear(2008);
        $movie2->setDescription('Scientist Bruce Banner is living in the shadows, scouring the planet for an antidote. But the warmongers who dream of abusing his powers wont leave him alone. Our brilliant doctor is pursued by the abomination, a nightmarish beast of pure adrenaline and aggression whose powers match the Hulks own, and a fight of comic book proportions ensues, threatening New York City with total destruction.');
        $movie2->setImagePath('https://m.media-amazon.com/images/S/pv-target-images/669b0142c944b1a961fb698592082971d09c5bc3a1d67128ab5084c6d45edda6.jpg');

        $movie3 = new Movie();
        $movie3->setTitle('The Dark Knight');
        $movie3->setReleaseYear(2008);
        $movie3->setDescription('The Dark Knight - the seventh feature film about the adventures of Batman, the second (after Batman: The Beginning) directed by Christopher Nolan. It had its world premiere on July 18, 2008 (Poland: August 8). It set a U.S. opening record with $158.4 million. The film was nominated in eight categories for the 2008 Academy Awards and received two statuettes: for supporting actor (Heath Ledger) and for best sound editing.');
        $movie3->setImagePath('/uploads/66282bd4ed0a6.jpg');

        $tagNamesTab2 = ['action hero','epic','famous line'];
        $categoriesMovie1tab2 = [];
        foreach ($tagNamesTab2 as $tagName) {
            $categorieForMovie = $this->em->getRepository(Categories::class)->findOneBy(['name' => $tagName]);
            if (!$categorieForMovie) {
                $categorieForMovie = new Categories();
                $categorieForMovie->setName($tagName);
                $manager->persist($categorieForMovie);
            }
            $categoriesMovie1tab2[] = $categorieForMovie;
        }
        foreach ($categoriesMovie1tab2 as $categorieForMovie2) {
            $movie3->addCategory($categorieForMovie2);
        }
        
        $createdAt = new \DateTime();

        $user = new User();
        $user->setEmail('admin@admin.pl');
        $user->setPassword('$2y$13$Zxyhw7uwSPCCGAPIMFfejO51/7/gX9m6wbCZVFaeHgsI3TJ8vfWcu'); //Admin@123!
        $user->setRoles($user->getRoles());
        $user->setRoles(['ROLE_ADMIN','ROLE_USER']);
        $user->setName('admin');
        $user->setSurename('admin');
        $user2 = new User();
        $user2->setEmail('user@user.pl');
        $user2->setPassword('$2y$13$Zxyhw7uwSPCCGAPIMFfejO51/7/gX9m6wbCZVFaeHgsI3TJ8vfWcu'); //Admin@123!

        $user2->setRoles($user2->getRoles());
        $user2->setName('user');
        $user2->setSurename('user');

        $comment1 = new Comments();
        $comment1->setAuthor('Paweł Ł.');
        $comment1->setContent('Awesome movie');
        $comment1->setCreatedAt($createdAt);
        $comment1->setMovie($movie);
        $comment1->setUser($user);
        $comment2 = new Comments();
        $comment2->setAuthor('Karol K.');
        $comment2->setContent('I love this movie');
        $comment2->setCreatedAt($createdAt);
        $comment2->setMovie($movie);
        $comment2->setUser($user2);

        $rating = new Rating();
        $rating->setScore('5.0');
        $rating->setMovie($movie);
        $rating->setUser($user);
        $rating2 = new Rating();
        $rating2->setScore('2.0');
        $rating2->setMovie($movie);
        $rating2->setUser($user2);

    $manager->persist($movie);
    $manager->persist($movie2);
    $manager->persist($movie3);
    $manager->persist($user);
    $manager->persist($user2);
    $manager->persist($comment1);
    $manager->persist($comment2);
    $manager->persist($rating);
    $manager->persist($rating2);

    $manager->flush();
    }
}
