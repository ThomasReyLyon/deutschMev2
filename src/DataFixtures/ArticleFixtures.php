<?php

namespace App\DataFixtures;

use App\Controller\HomeController;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;



class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
	public function getDependencies()
	{
		return [AuthorFixtures::class, CategoryFixtures::class];
	}



    public function load(ObjectManager $manager)
    {

    	$fakerFr = Faker\Factory::create('fr_FR');
		$fakerEn = Faker\Factory::create('en_GB');
    	$fakerDe = Faker\Factory::create('de_De');


    	$randomNumberOfWordsTitle = rand(1,6);
    	$randomNumberOfParagraph = rand(3,8);

    	for($i = 0; $i < 50; $i++) {
			$article = new Article();

    		$article->setTitle($fakerFr->sentence($randomNumberOfWordsTitle));
    		$article->setContent($fakerFr->paragraphs($randomNumberOfParagraph, true));
    		$article->setDateArticle($fakerFr->dateTimeBetween('-2 years', 'now'));
			$article->setCategory($this->getReference('category_0'));
			$article->setAuthor($this->getReference('author_'.rand(0,9)));

    		$manager->persist($article);

		}

		for($i = 0; $i < 50; $i++) {
			$article = new Article();

			$article->setTitle($fakerDe->sentence($randomNumberOfWordsTitle));
			$article->setContent($fakerDe->paragraphs($randomNumberOfParagraph, true));
			$article->setDateArticle($fakerDe->dateTimeBetween('-2 years', 'now'));
			$article->setCategory($this->getReference('category_1'));
			$article->setAuthor($this->getReference('author_'.rand(10,19)));
			$manager->persist($article);

		}

		for($i = 0; $i < 10; $i++) {
			$article = new Article();

			$article->setTitle($fakerEn->sentence($randomNumberOfWordsTitle));
			$article->setContent($fakerEn->paragraphs($randomNumberOfParagraph, true));
			$article->setDateArticle($fakerEn->dateTimeBetween('-2 years', 'now'));
			$article->setCategory($this->getReference('category_2'));
			$article->setAuthor($this->getReference('author_'.rand(1,19)));
			$manager->persist($article);
		}



        $manager->flush();
    }

}
