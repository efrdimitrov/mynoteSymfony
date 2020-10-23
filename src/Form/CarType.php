<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('brand')
            ->add('model')
            ->add('cubic')
            ->add('power')
            ->add('year')
            ->add('motor')
            ->add('kilowatt', TextType::class, [
                'empty_data' => NULL
            ])
            ->add('chassis', TextType::class, [
                'empty_data' => ''
            ])
            ->add('info', TextType::class, [
                'empty_data' => ''
            ]);
    }
}