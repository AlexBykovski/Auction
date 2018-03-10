<?php

namespace App\Admin;

use App\Entity\StakeOffering;
use App\Upload\FileUpload;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Form;

class StakeOfferingAdmin extends AbstractAdmin
{
    protected $uploader = null;
    protected $uploadDirectory = null;

    public function __construct(string $code, string $class, string $baseControllerName, FileUpload $uploader, $uploadDirectory)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;
        $this->uploadDirectory = $uploadDirectory;

        $this->uploader->setFolder(FileUpload::STAKE_OFFERING);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var StakeOffering $stakeOffering */
        $stakeOffering = $this->getSubject();

        $formMapper->add('cost', IntegerType::class, ['label' => 'Стоимость', 'required' => true]);
        $formMapper->add('count', IntegerType::class, ['label' => 'Количество', 'required' => true]);
        $formMapper->add('isSpecial', CheckboxType::class, ['label' => 'Специальное ли?', 'required' => false]);
        $formMapper->add(
            'imageFile',
            FileType::class,
            ['label' => 'Изображение', 'required' => !$stakeOffering->getImage(), 'mapped' => false],
            ["help" => $isEditAction ? $this->getImageHelp($stakeOffering->getImage()) : ""]);
        $formMapper->add('percent', PercentType::class, ['label' => 'Процент', 'required' => true, 'type' => 'integer']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('cost', IntegerType::class, ['label' => 'Стоимость', 'sortable' => false]);
        $listMapper->addIdentifier('count', IntegerType::class, ['label' => 'Количество', 'sortable' => false]);
        $listMapper->addIdentifier('isSpecial', 'boolean', [
            'label' => 'Специальное ли?', 'sortable' => false
        ]);
    }

    public function prePersist($stakeOffering)
    {
        $this->uploadFiles($this->getForm(), $stakeOffering);
    }

    public function preUpdate($stakeOffering)
    {
        $this->uploadFiles($this->getForm(), $stakeOffering);
    }

    protected function uploadFiles(Form $form, StakeOffering $stakeOffering){
        $image = $form->get('imageFile')->getData();

        if($image){
            $path = $this->uploader->upload($image);

            $stakeOffering->setImage($path);
        }
    }

    protected function getImageHelp($image){
        return "<img style='max-height: 100px;' src='"  . $this->uploadDirectory . $image . "' />";
    }
}