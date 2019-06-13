<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ShowArticleController extends AbstractController
{
	/**
	 * @Route("/article/{slug}",
	 *	 name="article_show",
	 * 	 methods={"GET", "POST"})
	 * @ParamConverter("slug", options = {"mapping" : {"slug" :"slug"}})
	 */
	public function showArticleFr(Article $article, CommentRepository $commentRepository, ObjectManager $manager, Request $request){

		$comments = $commentRepository->findByArticle($article->getId());

		$countComments = count($comments);

		$comment = new Comment();
		$form = $this->createForm(CommentType::class, $comment);

		$form->handleRequest($request);
		dump($request);
		if($form->isSubmitted() && $form->isValid()) {
			$comment->setCommentator($this->getUser());
			$comment->setArticle($article);
			$comment->setDateComment(new \DateTime());

			$manager->persist($comment);
			$manager->flush();

			return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
		}


		return $this->render('articles/show.html.twig', ['article' => $article,
			'comments' => $comments,
			'countComments' => $countComments,
			'form' => $form->createView()
			]);
	}
}