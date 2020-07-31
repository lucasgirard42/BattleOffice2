<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('adress')
            ->add('additionalAdress')
            ->add('city')
            ->add('postal')
            ->add('country', ChoiceType::class, [
                'choices'  => [
                    'France' => 'France',
                    'Belgique' => 'Belgique',
                    'Luxembourg' => 'Luxembourg',
                ],
            ])
            //->add('payment')
            //->add('statusMessage')
            //->add('apiId')
            ->add('client', ClientType::class)
            
            
            ->add('product', EntityType::class,[
                'class'=> Product::class,
                'expanded' => true,
                'choice_label'=>'name',
                'choice_value'=> 'id',
                'multiple'=> false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
