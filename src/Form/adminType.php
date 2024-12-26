<?php
namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class adminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class, [
                'label' => 'Identifiant',
                'attr' => [
                    'placeholder' => 'Entrez votre identifiant',
                    'class' => 'form-control input-blue',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'identifiant est obligatoire.']),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'L\'identifiant doit contenir exactement {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control input-blue',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control input-blue',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire.']),
                    new Assert\Email(['message' => 'Veuillez entrer une adresse email valide.']),
                ],
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'required' => false,
                'attr' => [
                    'class' => 'form-control input-blue',
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex : +33123456789',
                    'class' => 'form-control input-blue',
                ],
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9]{10,15}$/',
                        'message' => 'Le numéro de téléphone doit être valide (entre 10 et 15 chiffres).',
                    ]),
                ],
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'M',
                    'Femme' => 'F',
                ],
                'label' => 'Sexe',
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control input-blue',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un sexe.']),
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
            ])
         
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true, // Permet d'ignorer les champs non définis dans l'entité
        ]);
    }
}