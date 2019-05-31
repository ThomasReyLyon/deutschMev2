<?php


namespace App\Controller;
use App\Entity\Article;
use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowCategoryController extends AbstractController
{

	/**
	 * @Route("/categorie/{name}/{page}",
	 *
	 *     defaults = {"name":"article_Fr", "page":0},
	 *     requirements = {"[a-zA-Z]"},
	 *     name = "category_index", methods={"GET"})
	 * @ParamConverter("name", options={"mapping": {"name" : "name"}})
	 */
	public function categoryIndex(Category $category, Request $request) {

		$articles = $category->getArticles();

		//Defines the limit of slice filter in TWIG display (6 articles / page)
		if($request->get('page') == 1) {
			$pageFloorSlice = $request->get('page')-1;
		}
		else{
			$pageFloorSlice = ($request->get('page')-1)*6;
		}

		// Generate the links to browse the page of 6 articles.
		$countArticles = [];
		$page = 0;
		for ($i = 0; $i < count($articles); $i += 6){
			$page++;
			$countArticles[$i] = $page;
		}

		$currentPage = $request->get('page');


		return $this->render('category/categoryhome.html.twig', ['categorie' => $category,
			'articles' => $articles,
			'pageFloorSlice' => $pageFloorSlice,
			'countArticles' => $countArticles,
			'currentPage' => $currentPage
		]);

	}
}