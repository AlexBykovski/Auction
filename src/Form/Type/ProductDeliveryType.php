<?php

namespace App\Form\Type;

use App\Entity\ProductDeliveryDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class ProductDeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Получатель ФИО',
            ])
            ->add('postIndex', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Почтовый индекс',
            ])
            ->add('country', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Страна',
            ])
            ->add('city', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Город',
            ])
            ->add('address', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Адрес',
            ])
            ->add('phone', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Телефон для связи',
            ])
            ->add('note', TextareaType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Примечание',
            ])
            ->add('payment', ChoiceType::class, [
                'choices'  => [
                    "payment-visa-mscard" => "card",
                    "payment-qiwi" => "qiwi",
                    "payment-ipay" => "ipay",
                    "payment-ydmoney" => "yandex",
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('submit', SubmitType::class, [
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductDeliveryDetail::class,
            'validation_groups' => ['create_order'],
        ]);
    }
}