<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length as ConstraintsLength;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Intitulé de votre adresse',
                'constraints' => new ConstraintsLength([
                    'min' => 2,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => ' '
                ],
                'required' => 'true',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => new ConstraintsLength([
                    'min' => 2,
                    'max' => 30
                ]),
                'attr' => [
                    'placeholder' => ' '
                ],
                'required' => 'true',
            ])
            ->add('firstname',TextType::class, [
                'label' => 'Nom',
                'constraints' => new ConstraintsLength([
                    'min' => 2,
                    'max' => 30
                ]),'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ],
                'required' => 'true',
            ])
            ->add('address',TextType::class, [
                'label' => 'Adresse',
                'constraints' => new ConstraintsLength([
                    'min' => 2
                ]),'attr' => [
                    'placeholder' => 'Adresse postale, boîte postale, nom de la société, a/s'
                ],
                'required' => 'true',
            ])
            ->add('postal',TextType::class, [
                'label' => 'Code postal',
                'constraints' => new ConstraintsLength([
                    'min' => 2,
                    'max' => 30
                ]),'attr' => [
                    'placeholder' => ''
                ],
                'required' => 'true',
            ])
            ->add('City',TextType::class, [
                'label' => 'Ville',
                'constraints' => new ConstraintsLength([
                    'min' => 2,
                    'max' => 250
                ]),'attr' => [
                    'placeholder' => ''
                ],
                'required' => 'true',
            ])
            ->add('country',TextType::class, [
                'label' => 'État/province/région',
                'constraints' => new ConstraintsLength([
                    'min' => 2,
                    'max' => 30
                ]),'attr' => [
                    'placeholder' => ''
                ],
                'required' => 'true',
            ])
            ->add('phone',TextType::class, [
                'label' => 'Numéro de téléphone',
                'constraints' => new ConstraintsLength([
                    'min' => 2,
                    'max' => 30
                ]),'attr' => [
                    'placeholder' => ''
                ],
                'required' => 'true',
            ])
            ->add('company',TextType::class, [
                'label' => 'Entreprise',
                'constraints' => new ConstraintsLength([
                    'min' => 2
                ]),'attr' => [
                    'placeholder' => 'Nom de votre entreprise'
                ],
                'required' => 'true',
            ])
            ->add('instruction',TextareaType::class, [
                'label' => 'Avons-nous besoin de directions supplémentaires pour trouver cette adresse?',
                'attr' => [
                    'placeholder' => "Fournir des détails tels que la description du bâtiment, un point de repère à proximité ou d'autres instructions de navigation"
                ],
                'required' => 'true',
            ])
            ->add('codePorte',TextType::class, [
                'label' => 'Faut-il un code de sécurité ou un numéro de téléphone pour accéder à ce bâtiment ?',
                'constraints' => new ConstraintsLength([
                    'min' => 2,
                    'max' => 30
                ]),'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ],
                'required' => 'true',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter une adresse',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg'
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
