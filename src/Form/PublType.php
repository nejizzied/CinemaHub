<?php

namespace App\Form;

use App\Entity\Admin;
use App\Entity\Cinema;
use App\Entity\Publicite;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\PropertyAccess\PropertyAccess;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PublType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        
        
        $builder
            
       
        ->add('date', DateType::class , [
            'widget' => 'single_text',
            // prevents rendering it as type="date", to avoid HTML5 date pickers
            'html5' => false,
            // adds a class that can be selected in JavaScript
            'attr' => ['class' => 'js-datepicker date_in' , 'autocomplete' => 'off'],
        ])

        ->add('datefin' , DateType::class , [
            'widget' => 'single_text',
            // prevents rendering it as type="date", to avoid HTML5 date pickers
            'html5' => false,
            // adds a class that can be selected in JavaScript
            'attr' => ['class' => 'js-datepicker datefin_in' , 'autocomplete' => 'off'],
        ])


            ->add('id_admin',EntityType::class,[
                'class' =>Admin::class,
                'choice_label'=> 'nom',
                'multiple'=>false,
                'expanded'=>true, // thotha false twali liste deroulante mch btn radio
            ])
           


         
             ->add('prix'
            )
           
            ->add('Demander Publicité',SubmitType::class)



        ;

       
           

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publicite::class,
        ]);
    }
}
