<?php

namespace App\Form;

// use App\Entity\Formulaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class FormulairesType extends AbstractType
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
            // ->add('status', ChoiceType::class, [
            //     'choices' => [
            //         'Lus' => 'Lus',
            //         'Nouveau' => 'Nouveau',
            //         'Repondu' => 'Repondu'],
            // ])

            // ->add('ip')
          
            ->add('envoyer', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-primary"
                ]
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formulaires::class,
        ]);
    }
}
