<?php


namespace App\Security;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class ArticleVoter extends Voter
{
	const DELETE = 'delete';
	const EDIT = 'edit';

	private $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	protected function supports($attribute, $subject)
	{
		if(!in_array($attribute, [self::DELETE, self::EDIT])) {
			return false;
		}

		elseif(!$subject instanceof Article) {
			return false;
		}

		else{
			return true;
		}
	}

	protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
	{

		if($this->security->isGranted('ROLE_ADMIN')){
			return true;
		}

		$user = $token->getUser();



		if(!$user instanceof User){

			return false;
		}
		/**@Var Post $post */
		$article = $subject;

		switch ($attribute){
			case self::EDIT :
				return $this->canEdit($article, $user);
			case self::DELETE :
				return $this->canDelete($article, $user);
		}

		throw new \LogicException('heu, ouais mais non en fait.');
	}


	private function canEdit(Article $article, $user) {

		// si la fonction delete est passée, on sait donc que l'auteur est bien l'auteur du machin. Donc, bon BANCO!
		if($this->canDelete($article, $user)){

			return true;
		}
		else{
			return false;
		}

	}

	// Pour delete, on vérifie que l'auteur est bien l'auteur de l'article en question.
	private function canDelete(Article $article, $user) {

		return $user === $article->getAuthor();
	}

}