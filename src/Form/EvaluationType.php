<?php

namespace App\Form;

use App\Entity\Evaluation;
use App\Entity\Inscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Form\Transformer\StringToIntTransformer;


class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('participation', ChoiceType::class, [
            'label' => 'Participation',
            'choices' => [
                1 => '1 étoile',
                2 => '2 étoiles',
                3 => '3 étoiles',
                4 => '4 étoiles',
                5 => '5 étoiles',
            ],
            'expanded' => true, // Pour afficher les choix sous forme de boutons radio
            'multiple' => false, // Une seule note par critère
            'attr' => ['class' => 'rating']
        ])
        // Rigueur
        ->add('rigueur', ChoiceType::class, [
            'label' => 'Rigueur',
            'choices' => [
                1 => '1 étoile',
                2 => '2 étoiles',
                3 => '3 étoiles',
                4 => '4 étoiles',
                5 => '5 étoiles',
            ],
            'expanded' => true, // Pour afficher les choix sous forme de boutons radio
            'multiple' => false, // Une seule note par critère
            'attr' => ['class' => 'rating']
        ])
        // Progression
        ->add('progression', ChoiceType::class, [
            'label' => 'Progression',
            'choices' => [
                1 => '1 étoile',
                2 => '2 étoiles',
                3 => '3 étoiles',
                4 => '4 étoiles',
                5 => '5 étoiles',
            ],
            'expanded' => true, // Pour afficher les choix sous forme de boutons radio
            'multiple' => false, // Une seule note par critère
            'attr' => ['class' => 'rating']
        ]);
                // Appliquez le DataTransformer aux champs pour convertir les valeurs
        $builder->get('participation')
            ->addModelTransformer(new StringToIntTransformer());
    
        $builder->get('rigueur')
            ->addModelTransformer(new StringToIntTransformer());
    
        $builder->get('progression')
            ->addModelTransformer(new StringToIntTransformer());
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
