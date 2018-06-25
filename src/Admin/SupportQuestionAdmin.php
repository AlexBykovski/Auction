<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SupportQuestionAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('user.username', TextType::class, ['label' => 'Ник', 'sortable' => false, 'editable' => false]);
        $listMapper->addIdentifier('user.fullName', TextType::class, ['label' => 'ФИО', 'sortable' => false, 'editable' => false]);
        $listMapper->addIdentifier('question', TextType::class, ['label' => 'Вопрос', 'sortable' => false]);
        $listMapper->addIdentifier('image', null, ['label' => "Изображение", 'template' => 'admin/support_question_image.html.twig', 'sortable' => false]);
        $listMapper->addIdentifier('createdAt', 'datetime', ['label' => "Дата", "format" => "d-m-Y", 'sortable' => false]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        //$collection->remove('edit');
        //$collection->remove('show');

        $collection->clearExcept('list');
    }
}