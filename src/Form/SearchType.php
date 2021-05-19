<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType as TypeSearchType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Classe\Search;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SearchType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Recherche',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre recherche...'
                ]
            ] )
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => 'Categories',
                'required' => false,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('category')
                        ->orderBy('category.name', 'ASC');
                },
                'expanded' => true

            ])
            ->add('min', NumberType::class, [
                'label' => 'Prix',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix min'
                ]
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix max'
                ]
            ])
            /**
             * TO DO : Color class.
             */
            ->add('color', ChoiceType::class, [
                'label' => 'Couleur',
                'required' => false,
                'choices'  => [
                    'Rouge' => 'rouge',
                    'Marron' => 'marron',
                    'Blanc' => 'blanc',
                    'Noir' => 'noir',
                    'Bleu' => 'bleu'
                ],
            ])
            ->add('stars', ChoiceType::class, [
                'label' => 'Note',
                'required' => false,
                'choices'  => [
                    '⭐' => 1,
                    '⭐⭐' => 2 ,
                    '⭐⭐⭐' => 3,
                    '⭐⭐⭐⭐' => 4,
                    '⭐⭐⭐⭐⭐' => 5
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Filtrer",
                'attr' => [
                    'class' => 'btn btn-primary btn-lg btn-block'
                ]                    
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}


?>