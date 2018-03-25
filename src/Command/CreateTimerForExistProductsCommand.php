<?php

namespace App\Command;


use App\Entity\Product;
use App\Entity\ProductTimer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTimerForExistProductsCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:product:timer:create')
            ->setDescription('Create timer for products');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine.orm.default_entity_manager');

        $products = $em->getRepository(Product::class)->findBy(["timer" => null]);

        /** @var Product $product */
        foreach($products as $product){
            $timer = new ProductTimer();
            $timer->setUpdatedAt($product->getStartAt());
            $timer->setProduct($product);

            $em->persist($timer);

            $product->setTimer($timer);
        }

        $em->flush();
        $output->writeln("<info>Done!</info>");
    }
}