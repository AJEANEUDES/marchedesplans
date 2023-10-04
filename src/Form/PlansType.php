<?php

namespace App\Form;

use App\Entity\Plans;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class PlansType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('nbre_piece')
            ->add('nbre_etage')
            ->add('superficie')
             //ajout du champ images 
             ->add('images', FileType::class,[
                'label'=>false,
/*                 'maxSize' => '5M'
 */              'multiple'=>true,
                'mapped' => false,
                'required' => false
            ])
            ->add('type')
            ->add('forme')
            ->add('garage')
            
        
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plans::class,
        ]);
    }
}
