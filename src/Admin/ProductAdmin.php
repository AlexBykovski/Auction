<?php

namespace App\Admin;

use App\Entity\Product;
use App\Upload\FileUpload;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductAdmin extends AbstractAdmin
{
    protected $uploader = null;
    protected $uploadDirectory = null;

    protected $baseRouteName = 'admin_app_product';
    protected $baseRoutePattern = 'product';

    public function __construct(string $code, string $class, string $baseControllerName, FileUpload $uploader, $uploadDirectory)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;
        $this->uploadDirectory = $uploadDirectory;

        $this->uploader->setFolder(FileUpload::PRODUCT);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isEditAction = $this->isCurrentRoute('edit');
        /** @var Product $goods */
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
        $formMapper->add('characteristics', TextAreaType::class, ['label' => 'Характеристики', 'required' => true]);
        $formMapper->add('startAt', DateTimeType::class, [
            'label' => 'Начало',
            'widget' => 'single_text',
            'format' => 'yyyy.MM.dd HH:mm:ss',
            'required' => true
        ], ["help" => "<span>Формат гггг.мм.дд чч:мм:сс</span>"]);
        $formMapper->add('conditions', TextAreaType::class, ['label' => 'Условия ', 'required' => true]);
        $formMapper->add('categories', ChoiceType::class, [
            'label' => 'Категории',
            "choices" => $this->getCategoryChoices(),
            'expanded' => true,
            'multiple' => true
        ]);
    }

    public function prePersist($product)
    {
        $this->uploadFiles($this->getForm(), $product);
    }

    public function preUpdate($product)
    {
        $this->uploadFiles($this->getForm(), $product);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', TextType::class, ['label' => 'Название', 'sortable' => false]);
    }

    protected function getCategoryChoices (){

        return [
            'Новый' => 'new',
            'Популярный' => 'popular',
        ];
    }

    protected function uploadFiles(Form $form, Product $product){
        $mainFile = $form->get('mainPhotoFile')->getData();
        $otherFiles = $form->get('photosFiles')->getData();
        $otherFiles = is_array($otherFiles) ? $otherFiles : ($otherFiles instanceof UploadedFile ? [$otherFiles] : []);

        if($mainFile){
            $path = $this->uploader->upload($mainFile);

            $product->setMainPhoto($path);
        }

        if(count($otherFiles)){
            $photos = [];

            /** @var UploadedFile $file */
            foreach($otherFiles as $file){
                $photos[] = $this->uploader->upload($file);
            }

            $product->setPhotos($photos);
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