<?php

namespace App\Admin;

use App\Entity\Product;
use App\Entity\ProductTimer;
use App\Helper\AdminHelper;
use App\Upload\FileUpload;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
use Symfony\Component\Validator\Constraints\NotNull;

class ProductAdmin extends AbstractAdmin
{
    protected $uploader = null;

    protected $baseRouteName = 'admin_app_product';
    protected $baseRoutePattern = 'product';

    private $helper;

    public function __construct(string $code, string $class, string $baseControllerName, FileUpload $uploader, $uploadDirectory)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;

        $this->uploader->setFolder(FileUpload::PRODUCT);
        $this->helper = new AdminHelper($uploadDirectory);
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
            ["help" => $isEditAction ? $this->helper->getImagesHelp([$goods->getMainPhoto()]) : ""]
        );
        $formMapper->add(
            'photosFiles',
            FileType::class,
            ['label' => 'Фотографии', "multiple" => true, 'mapped' => false, 'required' => false],
            ["help" => $isEditAction ? $this->helper->getImagesHelp($goods->getPhotos()) : ""]);
        $formMapper->add('cost', IntegerType::class, ['label' => 'Стоимость', 'required' => true]);
        $formMapper->add('buyCost', IntegerType::class, ['label' => 'Стоимость для покупки', 'required' => true]);
        $formMapper->add('characteristics', CKEditorType::class, [
            'label' => 'Характеристики',
            'required' => true,
            'constraints' => [
                new NotNull(['message' => 'Введите характеристику']),
            ]]);
        $formMapper->add('startAt', DateTimeType::class, [
            'label' => 'Начало',
            'widget' => 'single_text',
            'format' => 'yyyy.MM.dd HH:mm:ss',
            'required' => true
        ], ["help" => "<span>Формат гггг.мм.дд чч:мм:сс</span>"]);
        $formMapper->add('conditions', CKEditorType::class, [
            'label' => 'Условия ',
            'required' => true,
            'constraints' => [
                new NotNull(['message' => 'Введите условия']),
            ]]);
        $formMapper->add('categories', ChoiceType::class, [
            'label' => 'Категории',
            "choices" => Product::$allCategories,
            'expanded' => true,
            'multiple' => true,
            'required' => true,
        ]);
    }

    public function prePersist($product)
    {
        $this->uploadFiles($this->getForm(), $product);

        $timer = new ProductTimer();
        $timer->setUpdatedAt($product->getStartAt());
        $timer->setProduct($product);

        $product->setTimer($timer);
    }

    public function preUpdate($product)
    {
        $this->uploadFiles($this->getForm(), $product);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', TextType::class, ['label' => 'Название', 'sortable' => false]);
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
}