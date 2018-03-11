<?php

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    protected function configureShowFields(ShowMapper $showMapper)
    {
        // Here we set the fields of the ShowMapper variable, $showMapper (but this can be called anything)
        $showMapper
            ->tab('Ставки') // the tab call is optional
                ->with('Списание ставок', [
                    'class'       => 'col-md-8',
                    'box_class'   => 'box box-solid',
                ])
                    ->add('buyStakes', 'array', array('template' => 'admin/user_buy_stakes.html.twig'))
                ->end()
                ->with('Начисление ставок', [
                    'class'       => 'col-md-8',
                    'box_class'   => 'box box-solid',
                ])
                    ->add('spendStakes', 'array', array('template' => 'admin/user_spend_stakes.html.twig'))
                ->end()
            ->end();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('lastName', TextType::class, ['label' => 'Фамилия', 'sortable' => false]);
        $listMapper->addIdentifier('firstName', TextType::class, ['label' => 'Имя', 'sortable' => false]);
        $listMapper->addIdentifier('sum', null, array('template' => 'admin/user_sum.html.twig'));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('delete');
        $collection->remove('create');
        $collection->remove('edit');
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->select('u')
            ->from(User::class, 'u')
            ->where('u.roles NOT LIKE :superAdminRole')
            ->setParameter('superAdminRole', '%' . User::ROLE_SUPER_ADMIN . '%');

        return $query;
    }
}