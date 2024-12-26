<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class, [
                'label' => 'Identifiant',
                'attr' => [
                    'placeholder' => 'Entrez votre identifiant',
                ],
                'required' => true,
                'constraints' => [
                    new Length(['min' => 8, 'max' => 8]), // Exactly 8 characters
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom',
                ],
                'required' => true,
                'constraints' => [
                    new Length(['min' => 2, 'max' => 50]), // Name length constraints
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Entrez votre email',
                ],
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Entrez un mot de passe sécurisé',
                    'pattern' => '^[a-zA-Z\d]+$', // Frontend validation
                    'title' => 'Le mot de passe doit contenir uniquement des lettres et des chiffres.',
                ],
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z\d]+$/',
                        'message' => 'Le mot de passe doit contenir uniquement des lettres et des chiffres.',
                    ]),
                    new Length(['min' => 8, 'max' => 20]), // Password length constraints
                ],
            ]);}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}