<?php

namespace App\Form;

use App\Entity\User;
use app\App\Form\type;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class, [
                'label' =>'Votre Prenom','constraints'=>new Length(['min'=>2,'max'=>30]),'attr'=>['placeholder'=>'saissisez votre prenom ici']])
            ->add('lastname',TextType::class, [
                'label' =>'Votre Nom','constraints'=>new Length(['min'=>2,'max'=>30]),'attr'=>['placeholder'=>'saissisez votre nom ici']])
            ->add('email',EmailType::class,['label' =>'Votre Adresse E-mail','constraints'=>new Length(['min'=>2,'max'=>30]),'attr'=>['placeholder'=>'Saissisez votre Adresse E-mail']])

            ->add('password',RepeatedType::class, 
            ['type'=> PasswordType::class,
            'invalid_message'=>'votre mot de passe ne correspond pas à la confirmation',
            'required'=>true,
            'first_options'=>['label'=>'Créer un Mot de passe',],
          
            'second_options'=>['label'=>'Confirmer Votre Mot de passe',]
            ])


            ->add('Submit',SubmitType::class,['label'=>"S'inscrire"])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
