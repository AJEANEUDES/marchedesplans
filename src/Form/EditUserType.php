<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'constraints' =>[
                    new NotBlank(['message'=>'merci de saisir une adresse valide'
              ])
                ],
                'required'=> true,
                'attr' => [ 'class' => 'form-control']
                
            ])
            ->add('roles', ChoiceType::class,
            [
               'choices' => [
                   'Client'=> 'ROLE_USER',
                   'Architecte'=> 'ROLE_ARCHITECTE',
                   'Administrateur' => 'ROLE_ADMIN'
               ],
               'expanded' => true,
               'multiple' => true,
               'label'    => 'Rôles'
            ])
            ->add('plainPassword', PasswordType::class, ['mapped' => true])

            ->add('pseudo')
            ->add('tel')
            ->add('banque')
            ->add('adresse')
            ->add('pays')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
