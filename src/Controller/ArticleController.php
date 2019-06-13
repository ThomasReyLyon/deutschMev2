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
use Symfony\Component\Validator\Validator\ValidatorInterface;



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
			'errors' => ''
		]);
	}


	/** Cette fonction marche à la fois pour le new (article = null et donc new article) et pour le edit (article trouvé grace à param converter
	 *
	 * @Route("/new", name="article_new", methods={"GET","POST"})
	 * @Route("/{id}/edit", name="article_edit", methods={"GET", "POST"})
	 *
	 * @param Article|null $article
	 * @param Request $request
	 * @param Slugify $slugify
	 * @param Mail $mail
	 * @param ObjectManager $manager
	 * @param ValidatorInterface $validator
	 * @param ArticleRepository $articleRepository
	 * @return Response
	 * @throws \Exception
	 */
	public function formArticle(Article $article = null, Request $request, Slugify $slugify, Mail $mail, ObjectManager
	$manager, ValidatorInterface $validator, ArticleRepository $articleRepository): Response
	{

		// Article vide? Alors on crée nouvel article.
		if(!$article) {
			$article = new Article();
		}





		//Formulaire lié à l'article.
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

			//Error message in case Of.
			$errors = $validator->validate($article);

			if(count($errors) > 0) {
				$errorString = (string) $errors;

				// si il y a des erreurs, on est renvoyé sur la page formulaire, avec les erreurs
				return $this->render('article/new_edit.html.twig', [
					'article' => $article,
					'form' => $form->createView(),
					'edit_mode' => $article->getId() !== null,
					'errors' => $errorString
				]);
			}


			$manager->persist($article);
			$manager->flush();


			$mail->newArticleConfirmation($article);

			if($this->isGranted('ROLE_ADMIN')){
			//fetch all articles for the rendering of the index
			$articles = $articleRepository->findAll();
			//if all goes well, this message will be displayed in the index upon submitting
			$errorString = 'Parfait! l\'article a été ajouté';

			return $this->render('article/index.html.twig', ['errors' => $errorString, 'articles' => $articles]);
			} else {
				//fetch all articles for the rendering of the index
				$articles = $articleRepository->findAll();
				//if all goes well, this message will be displayed in the index upon submitting
				$errorString = 'Parfait! l\'article a été ajouté';

				return $this->render('article/index.html.twig', ['errors' => $errorString, 'articles' => $articleRepository->findByAuthor($this->getUser())]);
			}
		}

		//if nothing's posted, then it displays the usual form.
		$errorString = '';

		// Works with Voter classes in security. If voter passes for edit, then go. If not, access will be denied.
		// (Only access if the user is the one who created the article)
		// Not so useful here, but implemented as test. ANd double security with what one can find under
		// UserArticleController (when an author logs in, he has only access to his own article).
		$this->denyAccessUnlessGranted('edit', $article);



		return $this->render('article/new_edit.html.twig', [
			'article' => $article,
			'form' => $form->createView(),
			'edit_mode' => $article->getId() !== null,
			'errors' => $errorString
		]);
	}

	/**
	 * @Route("/delete/{id}", name="article_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
	{
		if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($article);
			$entityManager->flush();
		}

		$this->denyAccessUnlessGranted('delete', $article);

		$errorString = ' l\'article a bien été supprimé';

		if($this->isGranted('ROLE_ADMIN')){

			return $this->redirectToRoute('article_index');
		} else {

			return $this->render('article/index.html.twig', ['errors' => $errorString, 'articles' => $articleRepository->findByAuthor($this->getUser())]);
		}

	}
}
