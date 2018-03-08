<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;

class StakeOfferingAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('cost', IntegerType::class, ['label' => 'Стоимость', 'required' => true]);
        $formMapper->add('count', IntegerType::class, ['label' => 'Количество', 'required' => true]);
        $formMapper->add('isSpecial', CheckboxType::class, ['label' => 'Специальное ли?', 'required' => false]);
        $formMapper->add('image', FileType::class, ['label' => 'Изображение', 'required' => true]);
        $formMapper->add('percent', PercentType::class, ['label' => 'Процент', 'required' => true]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('cost', IntegerType::class, ['label' => 'Стоимость']);
        $listMapper->addIdentifier('count', IntegerType::class, ['label' => 'Количество']);
        $listMapper->addIdentifier('isSpecial', 'boolean', [
            'label' => 'Специальное ли?',
        ]);
    }
}