<?php

namespace App\Form;

use App\Entity\Quack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('picture', FileType::class, [
                'label' => 'Picture',
                'required' => false,
                'mapped' => false,
            ])
            ->add('tags', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'Tags',
                'entry_options' => [
                    'label' => false,
                ],
                'attr' => [
                    'class' => 'tags',
                    'data-prototype' => $builder->create('tags', TextType::class, [
                        'label' => false,
                    ])->getForm()->createView(),
                    'data-index' => 0,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quack::class,
        ]);
    }
}
