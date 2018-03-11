<?php

namespace App\Admin;

use App\Entity\Product;
use App\Entity\User;
use App\Upload\FileUpload;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserAdmin extends AbstractAdmin
{
    protected function configureShowFields(ShowMapper $showMapper)
    {
        // Here we set the fields of the ShowMapper variable, $showMapper (but this can be called anything)
        $showMapper

            /*
             * The default option is to just display the value as text (for boolean this will be 1 or 0)
             */
            ->add('username')
//            ->add('Phone')
//            ->add('Email')
//
//            /*
//             * The boolean option is actually very cool
//             * - True  shows a check mark and says 'yes'
//             * - False shows an 'X' and says 'no'
//             */
//            ->add('DateCafe','boolean')
//            ->add('DatePub','boolean')
//            ->add('DateClub','boolean')
        ;

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('lastName', TextType::class, ['label' => 'Фамилия', 'sortable' => false]);
        $listMapper->addIdentifier('firstName', TextType::class, ['label' => 'Имя', 'sortable' => false]);
        $listMapper->addIdentifier('sum', null, array('template' => 'admin/user_sum.html.twig'));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('edit');
        $collection->remove('delete');
        $collection->remove('create');
        //$collection->clearExcept(array('list', 'show'));
    }

    public function createQuery($context = 'list')
    {
        /** @var ProxyQueryInterface $query */
        $query = parent::createQuery($context);
        $query->select('u')
            ->from(User::class, 'u')
            ->where('u.roles NOT LIKE :superAdminRole')
            ->setParameter('superAdminRole', '%' . User::ROLE_SUPER_ADMIN . '%');
//        $query->andWhere(
//            $query->expr()->like($query->getRootAliases()[0] . '.roles', ':roleUser')
//        );
//        $query->setParameter('roleUser', User::ROLE_DEFAULT);

        return $query;
    }
}