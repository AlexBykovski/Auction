<?php

namespace App\Admin;

use App\Entity\MainPage;
use App\Entity\SoonProduct;
use App\Upload\FileUpload;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MainPageAdmin extends AbstractAdmin
{
    protected $uploader = null;
    protected $uploadDirectory = null;

    public function __construct(string $code, string $class, string $baseControllerName, FileUpload $uploader, $uploadDirectory)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;
        $this->uploadDirectory = $uploadDirectory;

        $this->uploader->setFolder(FileUpload::MAIN_PAGE);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var MainPage $main */
        $main = $this->getSubject();
        $sliderImages = $main->getSliderImages();
        $soonProductImage = $main->getSoonProduct() instanceof SoonProduct ? $main->getSoonProduct()->getImage() : null;

        $formMapper
            ->tab("Главная")
                ->with("Слайдер")
                    ->add(
                        'sliderImagesFiles',
                        FileType::class,
                        ['label' => 'Слайдер', "multiple" => true, 'mapped' => false, 'required' => false],
                        ["help" => count($sliderImages) ? $this->getMultipleImagesHelp($sliderImages) : ""])
                ->end()
                ->with("Скоро на аукционе")
                    ->add(
                        'soonProductImageFile',
                        FileType::class,
                        ['label' => 'Изображение', 'required' => false, 'mapped' => false],
                        ["help" => $soonProductImage ? $this->getImageHelp($soonProductImage) : ""]
                    )
                    ->add('soonProduct.name', TextType::class, ['label' => 'Название', 'required' => false])
                    ->add('soonProduct.description', TextAreaType::class, ['label' => 'Описание', 'required' => false])
                ->end()
            ->end();
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

    public function prePersist($main)
    {
        $this->uploadFiles($this->getForm(), $main);
    }

    public function preUpdate($main)
    {
        $this->uploadFiles($this->getForm(), $main);
    }

    protected function uploadFiles(Form $form, MainPage $main){
        $soonProductFile = $form->get('soonProductImageFile')->getData();
        $sliderFiles = $form->get('sliderImagesFiles')->getData();
        $sliderFiles = is_array($sliderFiles) ? $sliderFiles : ($sliderFiles instanceof UploadedFile ? [$sliderFiles] : []);

        if($soonProductFile){
            $path = $this->uploader->upload($soonProductFile);

            $main->getSoonProduct()->setImage($path);
        }

        if(count($sliderFiles)){
            $photos = [];

            /** @var UploadedFile $file */
            foreach($sliderFiles as $file){
                $photos[] = $this->uploader->upload($file);
            }

            $main->setSliderImages($photos);
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