<?php
//src/Form/ResetPasswordType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', PasswordType::class, [
            'label' => 'Nouveau mot de passe',
            'attr' => ['placeholder' => 'Entrez votre nouveau mot de passe'],
        ]);
    }
}