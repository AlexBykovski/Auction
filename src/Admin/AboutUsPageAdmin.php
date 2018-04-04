<?php

namespace App\Admin;

use App\Entity\AboutUsPage;
use App\Upload\FileUpload;
use Hillrange\CKEditor\Form\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;

class AboutUsPageAdmin extends AbstractAdmin
{
    protected $uploader = null;
    protected $uploadDirectory = null;

    public function __construct(string $code, string $class, string $baseControllerName, FileUpload $uploader, $uploadDirectory)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;
        $this->uploadDirectory = $uploadDirectory;

        $this->uploader->setFolder(FileUpload::ABOUT_US_PAGE);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var AboutUsPage $aboutUs */
        $aboutUs = $this->getSubject();
        $image = $aboutUs->getImage();
        $achievement = $aboutUs->getAchievementImage();

        $formMapper->add(
            'imageFile',
            FileType::class,
            ['label' => 'Изображение', 'required' => false, 'mapped' => false],
            ["help" => $image ? $this->getImageHelp($image) : ""]
        );
        $formMapper->add('information', CKEditorType::class, ['label' => 'Информация', 'required' => false]);
        $formMapper->add('assortment', IntegerType::class, ['label' => 'Ассортимент', 'required' => false]);
        $formMapper->add('countries', IntegerType::class, ['label' => 'География', 'required' => false]);
        $formMapper->add(
            'achievementImageFile',
            FileType::class,
            ['label' => 'Достижения', 'required' => false, 'mapped' => false],
            ["help" => $achievement ? $this->getImageHelp($achievement) : ""]
        );
        $formMapper->add('experience', IntegerType::class, ['label' => 'Стаж работы', 'required' => false]);
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

    public function prePersist($aboutUs)
    {
        $this->uploadFiles($this->getForm(), $aboutUs);
    }

    public function preUpdate($aboutUs)
    {
        $this->uploadFiles($this->getForm(), $aboutUs);
    }

    protected function uploadFiles(Form $form, AboutUsPage $aboutUs){
        $imageFile = $form->get('imageFile')->getData();
        $achievementImageFile = $form->get('achievementImageFile')->getData();

        if($imageFile){
            $path = $this->uploader->upload($imageFile);

            $aboutUs->setImage($path);
        }

        if($achievementImageFile){
            $path = $this->uploader->upload($achievementImageFile);

            $aboutUs->setAchievementImage($path);
        }
    }

    protected function getImageHelp($image){
        return "<img style='max-height: 100px;' src='"  . $this->uploadDirectory . $image . "' />";
    }
}