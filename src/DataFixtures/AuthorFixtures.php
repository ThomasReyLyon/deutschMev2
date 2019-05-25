<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AuthorFixtures extends Fixture

{
    public function load(ObjectManager $manager)
    {

    	for ($i = 0; $i < 10; $i++) {
			$fakerFr = Faker\Factory::create('fr_FR');
			$author = new Author();
    		$author->setName($fakerFr->firstName.' '.$fakerFr->lastName);
			$manager->persist($author);
			$this->addReference('author_'.$i, $author);
		}

    	for($i = 10; $i < 20; $i++) {
			$fakerDe = Faker\Factory::create('de_DE');
			$author = new Author();
    		$author->setName($fakerFr->firstName.' '.$fakerFr->lastName);
    		$manager->persist($author);
			$this->addReference('author_'.$i, $author);
		}

        $manager->flush();
    }
}
