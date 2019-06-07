<?php


namespace App\Services;


use App\Entity\Article;
use Twig\Environment;

class Mail
{

	private $mailer;

	private $twig;

	public function __construct(\Swift_Mailer $mailer, Environment $twig)
	{
		$this->mailer = $mailer;
		$this->twig = $twig;
	}

	public function newArticleConfirmation(Article $article) {

		$message = new \Swift_Message($article);
		$title = $article->getTitle();
		$message->setSubject("nouvel article ajouté : $title")
			->setBody($this->twig->render('emails/newarticle.html.twig', [
				'article' => $article
			]), 'text/html');

		return $this->mailer->send($message);
	}
}