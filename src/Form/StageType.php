<?php

namespace App\Form;

use App\Entity\Stage;
use App\Entity\Typestage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'required' => true,
            ])
            ->add('domaine', TextType::class, [
                'label' => 'Domaine',
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('entreprise', TextType::class, [
                'label' => 'Entreprise',
                'required' => false,
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
                'required' => true, // Ensure this is true to trigger validation
                'attr' => [
                    'placeholder' => 'Ex: Paris',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La localisation ne peut pas être vide.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9 .]+$/',
                        'message' => 'La localisation ne peut contenir que des lettres, chiffres, espaces et des points.',
                    ]),
                ],
            ])
            ->add('datededebut', null, [
                'widget' => 'single_text',
                'label' => 'Date de début',
            ])
            ->add('datedefin', null, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
            ])
            ->add('typestage', EntityType::class, [
                'class' => Typestage::class,
                'choice_label' => 'type', // Afficher le nom du type au lieu de l'ID
                'label' => 'Type de Stage',
                'placeholder' => 'Sélectionnez un type de stage',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
