<?php

namespace App\DataFixtures;

use App\Controller\HomeController;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use App\Services\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;



class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
	public function getDependencies()
	{
		return [UserFixtures::class, CategoryFixtures::class];
	}



    public function load(ObjectManager $manager)
    {

    	$fakerFr = Faker\Factory::create('fr_FR');
		$fakerEn = Faker\Factory::create('en_GB');
    	$fakerDe = Faker\Factory::create('de_De');


    	$randomNumberOfWordsTitle = rand(1,6);
    	$randomNumberOfParagraph = rand(3,8);
		$slugify = new Slugify();
    	for($i = 0; $i < 50; $i++) {
			$article = new Article();
			$fakeTitle = $fakerFr->sentence($randomNumberOfWordsTitle);
    		$article->setTitle($fakeTitle);
    		$article->setContent($fakerFr->paragraphs($randomNumberOfParagraph, true));
    		$article->setDateArticle($fakerFr->dateTimeBetween('-2 years', 'now'));
			$article->setCategory($this->getReference('category_0'));
			$article->setAuthor($this->getReference('author_'.rand(0,9)));
			$article->setSlug($slugify->generate($fakeTitle));
    		$manager->persist($article);
    		$this->addReference('article_'.$i, $article);

		}

		for($i = 50; $i < 100; $i++) {
			$article = new Article();
			$fakeTitle = $fakerFr->sentence($randomNumberOfWordsTitle);
			$article->setTitle($fakeTitle);
			$article->setContent($fakerDe->paragraphs($randomNumberOfParagraph, true));
			$article->setDateArticle($fakerDe->dateTimeBetween('-2 years', 'now'));
			$article->setCategory($this->getReference('category_1'));
			$article->setAuthor($this->getReference('author_'.rand(10,19)));
			$article->setSlug($slugify->generate($fakeTitle));
			$manager->persist($article);
			$this->addReference('article_'.$i, $article);
		}

		for($i = 100; $i < 110; $i++) {
			$article = new Article();
			$fakeTitle = $fakerFr->sentence($randomNumberOfWordsTitle);
			$article->setTitle($fakeTitle);
			$article->setContent($fakerEn->paragraphs($randomNumberOfParagraph, true));
			$article->setDateArticle($fakerEn->dateTimeBetween('-2 years', 'now'));
			$article->setCategory($this->getReference('category_2'));
			$article->setAuthor($this->getReference('author_'.rand(1,19)));
			$article->setSlug($slugify->generate($fakeTitle));
			$manager->persist($article);
			$this->addReference('article_'.$i, $article);
		}



        $manager->flush();
    }

}
