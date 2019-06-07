<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('category', EntityType::class, [
            	'class' => Category::class,
				"choice_label" => function(Category $category){
            		return ucwords(str_replace("_", " ", $category->getName()));
				},
				"expanded" => true,
				"multiple" => false

			])
            ->add('author', EntityType::class, [
            	'class' => Author::class,
				"choice_label" => function(Author $author) {
            		return strtoupper($author->getName());
				},
				"preferred_choices" => function(Author $author) {
            		return $author->getName() == "AUTRE";
				},

			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
