<?php

namespace App\Form;

use App\Entity\Postuler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class postulerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('phone', NumberType::class, [
                'label' => 'Numéro de téléphone',
            ])
            ->add('formation', TextType::class, [
                'label' => 'Formation'
            ])
            ->add('experiences_academiques', TextareaType::class, [
                'label' => 'Expériences académiques'
            ])
            ->add('competences', TextareaType::class, [
                'label' => 'Compétences'
            ])
            ->add('lettre_motivation', TextareaType::class, [
                'label' => 'Lettre de motivation'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => postuler::class,
        ]);
    }
}
