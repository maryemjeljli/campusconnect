<?php
namespace App\Form;

use App\Entity\Evenement;
use App\Entity\TypeEvenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', TextType::class, [
            'label' => 'Titre',
            'required' => true,
        ])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('dateDebut', DateTimeType::class, ['label' => 'Date de début', 'widget' => 'single_text'])
            ->add('dateFin', DateTimeType::class, ['label' => 'Date de fin', 'widget' => 'single_text'])
            ->add('type', EntityType::class, [
                'class' => TypeEvenement::class,
                'choice_label' => 'nom',
                'label' => 'Type d\'événement'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG)',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
