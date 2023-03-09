<?php

namespace App\Form;

use App\Entity\Fruit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FilterFruitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' =>[
                    'placeholder' =>'name',
                    'class' => 'form-control m-2'
                ],
                'required' => false,
            ])
            ->add('family', TextType::class, [
                'label' => false,
                'attr' =>[
                    'placeholder' =>'family',
                    'class' => 'form-control m-2'
                ],
                'required' => false,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fruit::class,
            'csrf_protection' => false,
            'method' => 'GET',
        ]);
    }
}
