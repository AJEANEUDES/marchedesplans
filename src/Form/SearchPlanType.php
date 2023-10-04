<?php

namespace App\Form;

use App\Entity\Plans;
use App\Data\SearchData;
use App\Entity\Forme;
use App\Entity\Type;
use App\Entity\Superficie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchPlanType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder,array $options)
    {
        $builder
        // ->add('q', TextType::class, [
        //     'label' => false,
        //     'required' => false,
        //     'attr' => [
        //         'placeholder' => 'Rechercher un plan'
        //     ]
        // ])   
      

      
    // ->add('superficie', TextType::class, [
    //     'label' => false,
    //     'required' => false,
    //     'attr' => [
    //         'placeholder' => 'Superficie'
    //     ]
    // ])

    ->add('nbre_piece', NumberType::class, [
        'label' => false,
        'required' => false,
        'attr' => [
            'placeholder' => 'Nombre pieces'
        ]
    ])     


    ->add('min', NumberType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Prix minimum'
            ]
        ])

    ->add('max', NumberType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Prix maximum'
            ]
        ])
    
    
        ->add('types', EntityType::class,[
            'label'=>false,
            'required'=>false,
            'class'=>Type::class,
            'expanded'=> false,
            'multiple'=>false,
            'attr' => [
                'placeholder' => 'Types de Plans'
            ]
    ])


    ->add('superficies',EntityType::class,[
        'label'=>false,
        'required'=>false,
        'class'=>Superficie::class,
        'expanded'=>false,
        'multiple'=>false,
        'attr' => [
            'placeholder' => 'Nombre de Lots'
        ]
    ])

    
    ->add('formes',EntityType::class,[
        'label'=>false,
        'required'=>false,
        'class'=>Forme::class,
        'expanded'=>false,
        'multiple'=>false,
        'attr' => [
            'placeholder' => 'Formes de Plans'
        ]
    ])

            ;
   }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method'=>'GET',
            'csrf_protection'=> false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
