<?php


namespace App\Controller;
use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends AbstractController
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function index(ArticleRepository $articleRepository) {

		$articles = $articleRepository->allArticlesOrderedByDate();

		$notLogged = 1;
		if(!empty($this->getUser())){
			$notLogged = 0;
		}

		return $this->render('home.html.twig', ['articles' => $articles, 'notLogged' => $notLogged]);
	}

}