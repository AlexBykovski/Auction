<?php

namespace App\Form\Type;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Введите адрес электронной почты']),
                    new Email(['message' => 'Неверный формат']),
                ],
            ])
            ->add('username', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Введите имя пользователя']),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'invalid_message' => 'Пароли должны совпадать',
                'type' => PasswordType::class,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Введите пароль']),
                ],
            ])
            ->add('conditions_agree', CheckboxType::class, [
                'required' => true,
                'data' => true,
                'constraints' => [
                    new IsTrue(['message' => 'Вы должны согласиться с условиями']),
                ],
                'mapped' => false
            ])
            ->add('processing_agree', CheckboxType::class, [
                'required' => true,
                'data' => true,
                'constraints' => [
                    new IsTrue(['message' => 'Вы должны согласиться на обработку данных']),
                ],
                'mapped' => false
            ])
            ->add('get_news', CheckboxType::class, [
                'required' => true,
                'data' => true,
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => [],
        ]);
    }
}