<?php


namespace App\Controller;


use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ShowArticleController extends AbstractController
{
	/**
	 * @Route("/article/{slug}",
	 *	 name="article_show",
	 * 	 methods={"GET"})
	 * @ParamConverter("slug", options = {"mapping" : {"slug" :"slug"}})
	 */
	public function showArticleFr(Article $article){
		return $this->render('articles/show.html.twig', ['article' => $article]);
	}
}