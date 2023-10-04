<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
        ->add('nom', TextType::class, [
            "attr" => [
                "class" => "form-control"
            ]
        ])

        ->add('email', EmailType::class,[
            'label' =>'Votre Email',
            "attr" => [
                "class" => "form-control"
            ]
        ])

        ->add('message', TextareaType::class, [
            'label' =>'Votre message',
           
            "attr" => [
                "class" => "form-control"
            ]

        ])
       
        ->add('Soumettre', SubmitType::class, [
            "attr" => [
                "class" => "btn btn-block btn-danger"
            ]
        ])
       
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
