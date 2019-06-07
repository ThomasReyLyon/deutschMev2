<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserArticleController
 * @package App\Controller
 * @Route("/profile/")
 */
class UserArticleController extends AbstractController
{
    /**
     * @Route("article", name="user_article")
     */
    public function index(ArticleRepository $articleRepository)
    {



		return $this->render('article/index.html.twig', [
			'articles' => $articleRepository->findByAuthor($this->getUser()),
		]);
    }
}
