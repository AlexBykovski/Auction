<?php

namespace App\Form\Type;

use App\Entity\AutoStake;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoStakeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isWinEnd', ChoiceType::class, [
                'choices' => [
                    'до победы' => true,
                    'до' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => $options["data"]->getId() ? $options["data"]->getIsWinEnd() : true,
            ])
            ->add('endAt', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'HH:mm dd.MM.yyyy'
            ])
            ->add('count', NumberType::class, [
                'label' => 'количество ставок'
            ])
            ->add('submit', SubmitType::class, []);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AutoStake::class,
            'validation_groups' => ['create_autostake'],
        ]);
    }
}