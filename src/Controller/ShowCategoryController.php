<?php


namespace App\Controller;
use App\Entity\Article;
use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowCategoryController extends AbstractController
{

	/**
	 * @Route("/categorie/{name}",
	 *
	 *     defaults ={"article_Fr"},
	 *     requirements = {"[a-zA-Z]"},
	 *     name = "category_index")
	 * @ParamConverter("name", options={"mapping": {"name" : "name"}})
	 */
	public function categoryIndex(Category $category) {

		$articles = $category->getArticles();

		return $this->render('category/categoryhome.html.twig', ['categorie' => $category,
			'articles' => $articles]);

	}
}