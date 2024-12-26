<?php
namespace App\Form;

use App\Entity\Club;
use App\Entity\Typeclub;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FormulaireClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom du Club',
                'attr' => ['class' => 'form-control'], // Ajout de classes CSS pour le style
                'required' => false, // Désactiver HTML5 required
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du club est obligatoire.']),
                ],
            ])
            ->add('description', null, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 3],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'La description du club est obligatoire.']),
                ],
            ])
            ->add('date_de_creation', null, [
                'label' => 'Date de Création',
                'widget' => 'single_text', // Permet d'utiliser un champ de type date unique
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'La date de création est obligatoire.']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez une date',
                ],
            ])
            ->add('type', EntityType::class, [
                'class' => Typeclub::class,
                'choice_label' => 'libelle',
                'label' => 'Type de Club',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Le type de club est obligatoire.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}

?>