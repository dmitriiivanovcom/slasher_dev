<?php

namespace App\Form;

use App\Entity\Weapon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeaponForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('title')
            ->add('description')
            ->add('type')
            ->add('image')
            ->add('ammoStatus')
            ->add('damage')
            ->add('range')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Weapon::class,
        ]);
    }
}
