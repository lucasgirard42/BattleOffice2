<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ClientType extends AbstractType
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
            ->add('phone')
            ->add('email', EmailType::class)
            ->add('confirmationEmail', EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
