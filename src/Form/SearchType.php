<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    //fonction qui nous permet de créer un formulaire 
    {
        
        $builder
            ->add('string',TextType::class,['label'=>false,
            'required'=>false,'attr'=>['placeholder'=>'Rechercher','class'=>'form-control-sm']])
            //represente la recherche texte de mes users

            ->add ('categories',EntityType::class,
            ['label'=>false,
            'required'=>false,
            'class'=>Category::class,
            'multiple'=>true,
            'expanded'=>true])

            //j'ai lie ma classe a l'intité categorie grace à la propriete EntityType
            //avec des option comme class= category,multiple pour lui dire de selectionner 
            // plusieurs valeurs en meme temps, exanded pour afficher en checkbox

            ->add('submit',SubmitType::class,['label'=>'Filtrer','attr'=>['class'=>'btn-block btn-info']]);
    }    

    public function configureOptions(OptionsResolver $resolver)
    
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            //j'ai associe ma classe  à mon nouveau formulaire
            'method'=>'GET',
            'crsf_protection '=>false,
        ]);
    }
    

        public function getBlockPrefix()
        {
            return '';
        } 

}