<?php


namespace App\Form;


use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
        ->add('name')
        ->add('date', DateType::class, [
            'widget' => 'single_text',
            // this is actually the default format for single_text
            'format' => 'dd.MM.yyyy',
        ])
        ->add('category')
        ->add('days_remaining', NumberType::class,[
            'empty_data' => '0'
        ])
        ->add('hidden',  CheckboxType::class,[
            'attr' => array('checked' => 'checked', 'value' => '1')
        ]);
   }
}