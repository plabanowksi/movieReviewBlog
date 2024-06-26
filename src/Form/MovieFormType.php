<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Categories;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'attr'=> array(
                    'class' => 'bg-transparent block border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Enter title...'
                ),
                'label' => false,
                'required' => false
            ])
            ->add('release_year', IntegerType::class,[
                'attr'=> array(
                    'class' => 'bg-transparent block mt-10 border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Enter release year...'
                ),
                'label' => false,
                'required' => false
            ])
            ->add('description', TextareaType::class,[
                'attr'=> array(
                    'class' => 'bg-transparent block mt-10 border-b-2 w-full h-60 text-6xl outline-none',
                    'placeholder' => 'Enter description...'
                ),
                'label' => false,
                'required' => false
            ])
            ->add('image_path', FileType::class,[
                'required' => false,
                'mapped' => false,
                'attr'=> array(
                    'class' => 'py-10',
                    'placeholder' => ''
                ),
                'label' => false
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Categories:',
                'class' => Categories::class,
                'attr'=> array(
                    'class' => 'form-select select2',
                    'id' => 'multiple-select-field',
                    'data-placeholder' => "Choose anything"
                ),
                'label_attr' => [
                    'class' => 'display-6 mb-2', 
                ],
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            ]);


//             ->add('actors', EntityType::class, [
//                 'class' => Actor::class,
// 'choice_label' => 'id',
// 'multiple' => true,
//             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
