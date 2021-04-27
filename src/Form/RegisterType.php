<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\Length;
use Symfony\Component\Validator\Constraints\Length as ConstraintsLength;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => new ConstraintsLength(2,30),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ],
                'required' => 'true',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => new ConstraintsLength(2,30),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ],
                'required' => 'true',
            ])
            ->add('email', EmailType::class, [
                'invalid_message' => 'Le format de mail saisi est incorrect',
                'label' => 'Votre email',
                'constraints' => new ConstraintsLength(2,150),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre email'
                ],
                'required' => 'true',
                
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => new ConstraintsLength(8),
                'invalid_message' => 'Le mot de passe et la confirmation ne correspond pas',
                'options' => ['attr' => ['class' => 'password-field']],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre mot de passe'
                ],
                'required' => 'true',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'btn btn-primary btn-lg btn-block'
                ]                    
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
