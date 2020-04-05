<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
        ->add('name')
        ->add('date', DateType::class, [
            // this is actually the default format for single_text
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd'
        ])
        ->add('category')
        ->add('days_remaining', NumberType::class,[
            'empty_data' => '0'
        ])
        ->add('status',  NumberType::class,[
            'empty_data' => '0'
        ])
        ->add('checked',  NumberType::class,[
            'empty_data' => NULL
        ]);
   }
}