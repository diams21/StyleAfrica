<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstname',TypeTextType::class,['disabled'=>true,'label'=>'Mon prenom'])
            ->add('lastname',TypeTextType::class,['disabled'=>true,'label'=>'Mon nom'])
            ->add('email',EmailType::class,['disabled'=>true,'label'=>'Mon adresse mail'])
            ->add('old_password',PasswordType::class,['label'=>'Mon mot de passe actuel','mapped'=>false,'attr'=>['placeholder'=>'Votre mot de passe actuel']])

            ->add('new_password',RepeatedType::class,
            ['type'=> PasswordType::class,'mapped'=>false,
            'invalid_message'=>'votre mot de passe ne correspond pas à la confirmation',
            'required'=>true,
            'first_options'=>['label'=>'Créer un nouveau Mot de passe',],
          
            'second_options'=>['label'=>'Confirmer Votre nouveau Mot de passe',]
            ])


            ->add('Submit',SubmitType::class,['label'=>"Mettre à jour"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
