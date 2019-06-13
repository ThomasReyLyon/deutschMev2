<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\DataFixtures\ArticleFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
class CommentFixtures extends Fixture implements DependentFixtureInterface
{

	public function getDependencies()
	{
		return [UserFixtures::class, ArticleFixtures::class];
	}


    public function load(ObjectManager $manager)
    {
		$faker = new Faker\Factory();
		$faker = $faker::create('fr_FR');

		for($i = 0; $i < 200; $i++) {
		$randomArticleNumber = rand(0,109);


    	$comment = new Comment();
		$comment->setContent($faker->paragraphs(3, true));
		$comment->setDateComment(new \DateTime());
		$comment->setArticle($this->getReference('article_'.$randomArticleNumber));
		$comment->setCommentator($this->getReference('user_'.rand(20,39)));
        $manager->persist($comment);
		}

        $manager->flush();
    }
}
