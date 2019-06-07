<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

	private $encoder;

	private $slugifier;

	public function __construct(UserPasswordEncoderInterface $encoder, Slugify $slugifier)
	{
		$this->encoder = $encoder;
		$this->slugifier = $slugifier;
	}


	public function load(ObjectManager $manager)
    {
		$faker = Faker\Factory::create('fr_FR');


    	$user = new User();
    	$user->setEmail('admin@deutschme.fr');
    	$user->setPassword($this->encoder->encodePassword($user,'admin'));
    	$user->setRoles(['ROLE_ADMIN']);
    	$user->setName('Deutsch Me');

    	$manager->persist($user);

    	for ($i = 0; $i < 10; $i++) {

			$user = new User();
			$firstName = $faker->firstName;
			$lastName = $faker->lastName;

			$name = $firstName.$lastName;
			$cleanName = $this->slugifier->removeAccentsForEmail($name);
			$user->setEmail($cleanName.'@user.fr');
			$user->setPassword($this->encoder->encodePassword($user,'user'));
			$user->setRoles(['ROLE_AUTHOR']);
			$user->setName($firstName.' '.$lastName);
			$manager->persist($user);
			$this->addReference('author_'.$i, $user);
		}

    	for($i = 10; $i < 20; $i++) {
			$fakerDe = Faker\Factory::create('de_DE');
			$firstName = $fakerDe->firstName;
			$lastName = $fakerDe->lastName;
			$user = new User();
			$user->setEmail($firstName.$lastName.'@user.fr');
			$user->setPassword($this->encoder->encodePassword($user,'user'));
			$user->setName($firstName.' '.$lastName);
			$user->setRoles(['ROLE_AUTHOR']);
			$manager->persist($user);
			$this->addReference('author_'.$i, $user);
		}


        $manager->flush();
    }
}
