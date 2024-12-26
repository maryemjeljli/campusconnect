<?php
namespace App\Form;

use App\Entity\Formation;
use App\Entity\TypeFormation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FormulaireFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('lieu')
            ->add('date_debut', null, [
                'widget' => 'single_text',
                'input' => 'datetime',
            ])
            ->add('date_fin', null, [
                'widget' => 'single_text',
                'input' => 'datetime',
            ])
            ->add('cout')
            ->add('formateur')
            // Ajouter un champ pour sélectionner le libellé de TypeFormation
            ->add('typeformation', EntityType::class, [
                'class' => TypeFormation::class,
                'choice_label' => 'libelle', // Le champ à afficher dans la liste déroulante
                'placeholder' => 'Choisir un type de formation', // Optionnel, place un texte de type "Veuillez choisir"
                'required' => true, // Rendre ce champ obligatoire
            ])

            ->add('image', FileType::class, [
                'label' => 'Image de la formation',
                'mapped' => false, // Pas directement lié à l'entité
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG, PNG, GIF).',
                    ]),
                ],
            ])

            ->add('places', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
                'attr' => ['min' => 0],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
