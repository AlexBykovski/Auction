<?php

namespace App\Admin;

use App\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class WonProductAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'admin_app_won_product';
    protected $baseRoutePattern = 'won-product';

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', 'text', ['label' => 'Аукцион', 'sortable' => false]);
        $listMapper->addIdentifier('winner.id', 'integer', ['label' => 'ID пользователя', 'sortable' => false]);
        $listMapper->addIdentifier('isProcessed', 'boolean', ['label' => 'Обработан ли?', 'sortable' => false]);
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->select('p')
            ->from(Product::class, 'p')
            ->where('p.winner IS NOT NULL');

        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept('list');
    }
}