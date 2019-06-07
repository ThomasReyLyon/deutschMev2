<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Services\Mail;
use App\Services\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/article")
 */
class ArticleController extends AbstractController
{
	/**
	 * @Route("/", name="article_index", methods={"GET"})
	 */
	public function index(ArticleRepository $articleRepository): Response
	{
		return $this->render('article/index.html.twig', [
			'articles' => $articleRepository->findAll(),
		]);
	}

	/** Cette fonction marche à la fois pour le new (article = null et donc new article) et pour le edit (article trouvé grace à param converter
	 * @Route("/new", name="article_new", methods={"GET","POST"})
	 * @Route("/{id}/edit", name="article_edit", methods={"GET", "POST"})s
	 */
	public function formArticle(Article $article = null,Request $request, Slugify $slugify, Mail $mail, ObjectManager $manager): Response
	{
		if(!$article) {
			$article = new Article();
		}

		$form = $this->createForm(ArticleType::class, $article);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$article->setSlug($slugify->generate($article->getTitle()));

			//Gestion de la date de création. Si article déjà existant, on ne change pas la date
			if(!$article->getId()){
				$article->setDateArticle(new \DateTime());
			}
			//Verifie qu'on est bien loggé, et attribue le nom de la personne loggé dans l'article nouvellement créé
			$article->setAuthor($this->getUser());


			$manager->persist($article);
			$manager->flush();

			$mail->newArticleConfirmation($article);
			return $this->redirectToRoute('article_index');
		}


		return $this->render('article/new.html.twig', [
			'article' => $article,
			'form' => $form->createView(),
			'edit_mode' => $article->getId() !== null
		]);
	}

	/**
	 * @Route("/{id}", name="article_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Article $article): Response
	{
		if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($article);
			$entityManager->flush();
		}

		return $this->redirectToRoute('article_index');
	}
}
