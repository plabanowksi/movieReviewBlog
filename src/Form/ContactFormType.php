<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', TextType::class, [
            'label' => false,
            'attr' => [
                'autocomplete' => 'email',                     
                'class' => 'block w-full h-20 text-3xl form-control',
                'placeholder' => 'Enter your email here...'
            ],
        ])
            ->add('content',TextareaType::class,[
                'label' => false,
                'attr' => [
                    'class' => 'block w-full text-3xl form-control',
                    'placeholder' => 'Enter your text here...',
                    'style' => 'height: 300px',
                    'cols' => 100,
                    'rows' => 20,
                ]
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
                "row_attr" => [
                    "class" => "d-none"
                ],
                'required' => false,
                'data' => new \DateTime(),
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
                "row_attr" => [
                    "class" => "d-none"
                ],
                'required' => false,
                'data' => new \DateTime(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
