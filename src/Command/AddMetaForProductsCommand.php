<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\ProductMetaData;
use App\Entity\User;
use App\Generator\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddMetaForProductsCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:product:add-meta')
            ->setDescription('Add metadata for existing products');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine.orm.default_entity_manager');

        $products = $em->getRepository(Product::class)->findAll();

        foreach ($products as $product){
            $metaData = new ProductMetaData();
            $em->persist($metaData);

            $product->setMetaData($metaData);
        }

        $em->flush();
    }
}