<?php

namespace App\Admin;

use App\Entity\Goods;
use App\Upload\FileUpload;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GoodsAdmin extends AbstractAdmin
{
    protected $uploader = null;
    protected $uploadDirectory = null;

    public function __construct(string $code, string $class, string $baseControllerName, FileUpload $uploader, $uploadDirectory)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;
        $this->uploadDirectory = $uploadDirectory;

        $this->uploader->setFolder(FileUpload::GOODS);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var Goods $goods */
        $goods = $this->getSubject();

        $formMapper->add('name', TextType::class, ['label' => 'Название', 'required' => true]);
        $formMapper->add(
            'mainPhotoFile',
            FileType::class,
            ['label' => 'Главное фото', 'required' => !$goods->getMainPhoto(), 'mapped' => false],
            ["help" => $isEditAction ? $this->getImageHelp($goods->getMainPhoto()) : ""]
        );
        $formMapper->add(
            'photosFiles',
            FileType::class,
            ['label' => 'Фотографии', "multiple" => true, 'mapped' => false, 'required' => false],
            ["help" => $isEditAction ? $this->getMultipleImagesHelp($goods->getPhotos()) : ""]);
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

    public function prePersist($goods)
    {
        $this->uploadFiles($this->getForm(), $goods);
    }

    public function preUpdate($goods)
    {
        $this->uploadFiles($this->getForm(), $goods);
    }

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

    protected function uploadFiles(Form $form, Goods $goods){
        $mainFile = $form->get('mainPhotoFile')->getData();
        $otherFiles = $form->get('photosFiles')->getData();
        $otherFiles = is_array($otherFiles) ? $otherFiles : ($otherFiles instanceof UploadedFile ? [$otherFiles] : []);

        if($mainFile){
            $path = $this->uploader->upload($mainFile);

            $goods->setMainPhoto($path);
        }

        if(count($otherFiles)){
            $photos = [];

            /** @var UploadedFile $file */
            foreach($otherFiles as $file){
                $photos[] = $this->uploader->upload($file);
            }

            $goods->setPhotos($photos);
        }
    }

    protected function getImageHelp($image){
        return "<img style='max-height: 100px;' src='"  . $this->uploadDirectory . $image . "' />";
    }

    protected function getMultipleImagesHelp($images){
        $help = "";

        foreach($images as $image){
            $help .= $this->getImageHelp($image) . "<br />";
        }

        return $help;
    }
}