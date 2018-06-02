<?php

namespace App\Command;

ini_set('max_execution_time', 60);

use App\Entity\AutoStake;
use App\Entity\Product;
use App\Entity\StakeExpense;
use App\Entity\User;
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
        //var_dump($event->getDuration());


        //$output->writeln("<info>Done!</info>");
    }

    protected function processAutoStakes()
    {
        $now = new DateTime();

        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $unGroupedAutoStakes = $em->getRepository(AutoStake::class)->findAll();
        $groupedAutoStakes = $this->groupAutostakesByAuction($unGroupedAutoStakes);

        foreach($groupedAutoStakes as $autoStakes) {
            $isHasStake = false;
            /** @var Product $auction */
            $auction = $autoStakes[0]->getAuction();

            shuffle($autoStakes);

            /** @var AutoStake $autoStake */
            foreach ($autoStakes as $autoStake) {
                $stakeDetail = $autoStake->getStakeDetail();

                if ($this->needRemoveAutoStake($autoStake)) {
                    $autoStake->returnStakesToUser();
                    $em->remove($autoStake);

                    continue;
                }

                $potentialWinner = $auction->getPotentialWinner();
                $user = $stakeDetail->getUser();


                if (!($potentialWinner instanceof User) || $potentialWinner instanceof User && $potentialWinner->getId() !== $user->getId()) {
                    $auctionEndTime = $autoStake->getAuction()->getTimer()->getEndTimeInMS() / 1000;

                    if (($auctionEndTime - $now->getTimestamp()) >= 2) {
                        continue;
                    }

                    $this->addStakeByAutoStake($autoStake);
                    $isHasStake = true;
                }
            }

            if($isHasStake){
                $auction->getTimer()->restartTimer();
            }
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

        $autoStake->spendOneStake();

        $stakeExpense = new StakeExpense();
        $stakeExpense->setCount(1);
        $stakeExpense->setProduct($product);
        $stakeExpense->setStakeDetail($autoStake->getStakeDetail());
        $product->setPotentialWinner($autoStake->getStakeDetail()->getUser());
        $product->setCost($product->getCost() + 0.1);

        $em->persist($stakeExpense);
    }

    protected function groupAutostakesByAuction($unGroupedAutoStakes)
    {
        $groupedAutoStakes = [];

        /** @var AutoStake $autoStake */
        foreach($unGroupedAutoStakes as $autoStake){
            $auctionId = $autoStake->getAuction()->getId();

            if(!array_key_exists($auctionId, $groupedAutoStakes)){
                $groupedAutoStakes[$auctionId] = [];
            }

            $groupedAutoStakes[$auctionId][] = $autoStake;
        }

        return $groupedAutoStakes;
    }
}