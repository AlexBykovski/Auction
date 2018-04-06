<?php

namespace App\Admin;

use App\Entity\Bonus;
use App\Entity\BonusPage;
use App\Upload\FileUpload;
use App\Form\BonusForm;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BonusPageAdmin extends AbstractAdmin
{
    protected $uploader = null;

    public function __construct(string $code, string $class, string $baseControllerName, FileUpload $uploader)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;

        $this->uploader->setFolder(FileUpload::BONUS);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('titleDescription', TextType::class, ['label' => 'Заголовок', 'required' => false]);
        $formMapper->add('description', TextAreaType::class, ['label' => 'Описание', 'required' => false]);
        $formMapper->add('titleBonuses', TextType::class, ['label' => 'Заголовок бонусов', 'required' => false]);
        $formMapper->add('bonuses', CollectionType::class, [
            'entry_type' => BonusForm::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false
        ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', 'integer', ['label' => 'ID', 'sortable' => false])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ]
            ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
    }

    public function preUpdate($bonusPage)
    {
        $this->uploadFiles($this->getForm(), $bonusPage);
    }

    protected function uploadFiles(Form $form, BonusPage $bonusPage){
        $bonuses = $form->get('bonuses');
        $newBonusIds = [];

        /** @var Form $bonus */
        foreach($bonuses as $bonusForm){
            /** @var Bonus $bonus */
            $bonus = $bonusForm->getData();

            $file = $bonusForm->get("imageFile")->getData();

            if($file instanceof UploadedFile) {
                $path = $this->uploader->upload($file);
                $bonus->setImage($path);
            }

            $bonus->setBonusPage($bonusPage);

            if($bonus->getId()){
                $newBonusIds[] = $bonus->getId();
            }
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');

        $em->getRepository(Bonus::class)->deleteAllExceptIds($newBonusIds);
    }
}