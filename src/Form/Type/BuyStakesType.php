<?php

namespace App\Form\Type;


use App\Entity\StakeOffering;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;

class BuyStakesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('simpleStakes', EntityType::class, [
                'class' => StakeOffering::class,
                'choices' => $options["data"]["simpleStakes"],
                'choice_label' => 'id',
                'choice_value' => 'id',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'data' => null
            ])
            ->add('specialStakes', EntityType::class, [
                'class' => StakeOffering::class,
                'choices' => $options["data"]["specialStakes"],
                'choice_label' => 'id',
                'choice_value' => 'id',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'data' => null
            ])
            ->add('payment', ChoiceType::class, [
                'required' => true,
                'choices'  => [
                    "payment-visa-mscard" => "card",
                    "payment-qiwi" => "qiwi",
                    "payment-ipay" => "ipay",
                    "payment-ydmoney" => "yandex",
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotNull(['message' => "Вы должны выбрать способ оплаты для продолжения"]),
                ],
            ])
            ->add('submit', SubmitType::class, [
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'validation_groups' => [],
        ]);
    }
}