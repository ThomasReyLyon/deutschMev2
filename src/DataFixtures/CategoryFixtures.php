<?php


namespace App\DataFixtures;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

	private const CATEGORIES = ['article_fr', 'article_de', 'article_other'];


	public function load(ObjectManager $manager)
	{
		foreach (self::CATEGORIES as $key => $categoryName) {
			$category = new Category();

			$category->setName($categoryName);
			$manager->persist($category);
			$this->addReference('category_'.$key, $category);
			$manager->flush();
		}

	}
}