<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class, [
                'label' => 'Identifiant',
                'attr' => ['placeholder' => 'Entrez votre identifiant'],
                'constraints' => [
                    new NotBlank(['message' => 'L\'identifiant ne peut pas être vide.']),
                    new Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'L\'identifiant doit comporter exactement 8 caractères.',
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'attr' => ['placeholder' => 'Entrez votre nom'],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne peut pas être vide.']),
                    new Length(['min' => 2, 'max' => 50, 'maxMessage' => 'Le nom ne peut pas dépasser 50 caractères.']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Entrez votre email'],
                'constraints' => [
                    new NotBlank(['message' => 'L\'email ne peut pas être vide.']),
                    new Email(['message' => 'L\'email n\'est pas valide.']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Entrez un mot de passe sécurisé',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe ne peut pas être vide.']),
                    new Length([
                        'min' => 8,
                        'max' => 20,
                        'minMessage' => 'Le mot de passe doit contenir au moins 8 caractères.',
                        'maxMessage' => 'Le mot de passe ne peut pas dépasser 20 caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\d]+$/',
                        'message' => 'Le mot de passe doit contenir uniquement des lettres et des chiffres.',
                    ]),
                ],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}