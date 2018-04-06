<?php

namespace App\Form\Type;

use App\Entity\ProductDeliveryDetail;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photoFile', FileType::class, [
                'constraints' => [
                    new File([
                        "maxSize" => "10Mi",
                        "mimeTypes" => ["image/png", "image/jpeg"],
                        "maxSizeMessage" => "Фото не должно превышать 10Мб",
                        "mimeTypesMessage" => "Вы можете загружать только .png, .jpg файлы",
                    ])
                ],
                'required' => false,
                'mapped' => false,
            ])
            ->add('username', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Ник',
                'constraints' => [
                    new NotBlank(['message' => 'Проверьте корректность ввода']),
                ],
            ])
            ->add('email', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Электронная почта',
                'constraints' => [
                    new NotBlank(['message' => 'Проверьте корректность ввода']),
                ],
            ])
            ->add('firstName', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Имя',
            ])
            ->add('lastName', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Фамилия',
            ])
            ->add('sex', ChoiceType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Пол',
                'choices'  => array(
                    'М' => true,
                    'Ж' => false,
                ),
            ])
            ->add('age', NumberType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Возраст',
            ])
            ->add('oldPassword', PasswordType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Старый пароль',
                'mapped' => false,
                'required' => false,
            ])
            ->add('newPassword', RepeatedType::class, [
                'invalid_message' => 'Пароли должны совпадать',
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Новый пароль',
                    'label_attr' => ['class' => 'fg-label']
                ],
                'second_options' => [
                    'label' => 'Подтверждение пароля',
                    'label_attr' => ['class' => 'fg-label']
                ],
                'required' => false,
                'mapped' => false,
            ])
            ->add('deliveryDetail', UserDeliveryType::class, [])
            ->add('notificationDetail', NotificationDetailType::class, [])
            ->add('submit', SubmitType::class, [
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['edit_profile', "edit_delivery"],
        ]);
    }
}