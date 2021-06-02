<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Livreur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
        ->add('address', EntityType::class, [
            'class' => Address::class,
            'label' => 'Séléctionné votre adresse : ',
            'required' => true,
            'choices' => $user->getUserAddress(),
            /*
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('address')
                    ->orderBy('address.address', 'ASC');
            },*/
            'expanded' => true
        ])
        ->add('livreurs', EntityType::class, [
            'class' => Livreur::class,
            'label' => 'Séléctionné votre livreur :',
            'required' => true,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('livreur')
                    ->orderBy('livreur.name', 'ASC');
            },
            'expanded' => true
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Valider ma commande",
            'attr' => [
                'class' => 'btn btn-primary btn-lg btn-block'
            ]                    
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => []
        ]);
    }
}
