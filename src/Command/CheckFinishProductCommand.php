<?php

namespace App\Command;

ini_set('max_execution_time', 60);

use App\Entity\AutoStake;
use App\Entity\Product;
use App\Entity\StakeExpense;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class CheckFinishProductCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:product:check-finish')
            ->setDescription('Check finish auctions');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('eventName');

        $time = new DateTime();
        $time->add(new DateInterval("PT1M"));

        while($time > (new DateTime())){
            $this->processAutoStakes();
            $this->finishAuctions();
        }

        $event = $stopwatch->stop('eventName');
        var_dump($event->getDuration());


        //$output->writeln("<info>Done!</info>");
    }

    protected function processAutoStakes()
    {
        $now = new DateTime();

        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $autoStakes = $em->getRepository(AutoStake::class)->findAll();

        /** @var AutoStake $autoStake */
        foreach($autoStakes as $autoStake){
            if($this->needRemoveAutoStake($autoStake)){
                $em->remove($autoStake);

                continue;
            }

            $auctionEndTime = $autoStake->getAuction()->getTimer()->getEndTimeInMS() / 1000;

            if(($auctionEndTime - $now->getTimestamp()) >= 2){
                continue;
            }

            $this->addStakeByAutoStake($autoStake);
        }

        $em->flush();
    }

    protected function finishAuctions()
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $products = $em->getRepository(Product::class)->findAllAlreadyFinished();

        /** @var Product $product */
        foreach($products as $product){
            $product->setEndAt(new DateTime());
            $product->setWinner($product->getPotentialWinner());
        }

        $em->flush();
    }

    protected function needRemoveAutoStake(AutoStake $autoStake)
    {
        $now = new DateTime();

        //1) count < 1
        //2) is product finished
        //3) !is_win_end && $now > end_at
        return $autoStake->getCount() < 1 ||
            $autoStake->getAuction()->getEndAt() instanceof DateTime ||
            !$autoStake->getIsWinEnd() && $now > $autoStake->getEndAt();
    }

    protected function addStakeByAutoStake(AutoStake $autoStake)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $product = $autoStake->getAuction();

        $autoStake->setCount($autoStake->getCount() - 1);

        $stakeExpense = new StakeExpense();
        $stakeExpense->setCount(1);
        $stakeExpense->setProduct($product);
        $stakeExpense->setStakeDetail($autoStake->getStakeDetail());
        $product->setPotentialWinner($autoStake->getStakeDetail()->getUser());
        $product->setCost($product->getCost() + 0.1);

        $em->persist($stakeExpense);

        $product->getTimer()->restartTimer();
    }
}