<?php


namespace App\Controller;


use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowArticleController extends AbstractController
{
	/**
	 * @Route("/articlefr/{id}",
	 *	 requirements={"id" = "[0-9]+"},
	 *	 defaults={"150"},
	 *	 name="article_show")
	 */
	public function showArticleFr(Article $article){
		return $this->render('articles/show.html.twig', ['article' => $article]);
	}
}