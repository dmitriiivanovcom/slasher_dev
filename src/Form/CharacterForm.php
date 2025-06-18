<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CharacterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('strength')
            ->add('agility')
            ->add('intelligence')
            ->add('charisma')
            ->add('quote')
            ->add('role')
            ->add('portrait', FileType::class, [
                'label' => 'Портрет (JPG, PNG)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('backgroundImage', FileType::class, [
                'label' => 'Фоновое изображение (JPG, PNG)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('motto')
            ->add('weaknesses')
            ->add('strengths')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}
