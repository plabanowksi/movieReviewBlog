<?php

namespace App\Form;

use App\Entity\Comments;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author',null,[
                "row_attr" => [
                    "class" => "d-none"
                ],
                'required' => false
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control comment-form',
                    'placeholder' => 'Leave a comment here',
                    'style' => 'height: 100px',
                    'cols' => 100,
                    'rows' => 20,
                ],
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
                "row_attr" => [
                    "class" => "d-none"
                ],
                'required' => false
            ])
            ->add('movie', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => 'id',
                "row_attr" => [
                    "class" => "d-none"
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
