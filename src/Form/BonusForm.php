<?php

namespace App\Form;

use App\Entity\Bonus;
use App\Helper\AdminHelper;
use Hillrange\CKEditor\Form\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BonusForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $helper = new AdminHelper('/images/');

        $builder
            ->add('imageFile', FileType::class, [
                'label_attr' => ['class' => 'control-label'],
                'label' => 'Изображение',
                'required' => false,
                'mapped' => false,
            ])
            ->add('description', CKEditorType::class, [
                'label_attr' => ['class' => 'control-label'],
                'label' => 'Описание',
                'required' => true,
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($helper) {
                $bonus = $event->getData();
                $form = $event->getForm();

                if($bonus instanceof Bonus){
                    $config = $form->get('imageFile')->getConfig();
                    $options = $config->getOptions();
                    $options["sonata_help"] = $bonus->getImage() ? $helper->getImagesHelp([$bonus->getImage()]) : "";

                    $form->add('imageFile', FileType::class, $options);
                }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bonus::class,
            'validation_groups' => [],
        ]);
    }
}