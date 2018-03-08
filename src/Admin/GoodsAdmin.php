<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GoodsAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', TextType::class, ['label' => 'Название', 'required' => true]);
        $formMapper->add('mainPhoto', FileType::class, ['label' => 'Главное фото', 'required' => true]);
        $formMapper->add('photos', FileType::class, ['label' => 'Фотографии', "multiple" => true]);
        $formMapper->add('cost', IntegerType::class, ['label' => 'Стоимость', 'required' => true]);
        $formMapper->add('characteristics', TextType::class, ['label' => 'Характеристики', 'required' => true]);
        $formMapper->add('startAt', DateTimeType::class, [
            'label' => 'Начало',
            'widget' => 'single_text',
            'format' => 'yyyy.MM.dd HH:mm:ss',
            'required' => true
        ], ["help" => "<span>Формат гггг.мм.дд чч:мм:сс</span>"]);
        $formMapper->add('conditions', TextType::class, ['label' => 'Условия ', 'required' => true]);
        $formMapper->add('categories', ChoiceType::class, [
            'label' => 'Категории',
            "choices" => $this->getCategoryChoices(),
            'expanded' => true,
            'multiple' => true
        ]);
    }
//
//    public function prePersist($medicalCenter)
//    {
//        $this->addRoleAdmin($medicalCenter->getUser());
//    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', TextType::class, ['label' => 'Название']);
    }

    protected function getCategoryChoices (){

        return [
            'Новый' => 'new',
            'Популярный' => 'popular',
        ];
    }
}