<?php

namespace App\Form;

use App\Entity\Monster;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonsterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('title')
            ->add('rank')
            ->add('dangerIndex')
            ->add('lethality')
            ->add('speed')
            ->add('stealth')
            ->add('description')
            ->add('strengths')
            ->add('weaknesses')
            ->add('backstory')
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Monster::class,
        ]);
    }
}
