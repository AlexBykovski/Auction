<?php

namespace App\Helper;

use App\Entity\StakeOffering;
use Doctrine\ORM\EntityManagerInterface;

class StakeHelper
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * StakeHelper constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getStakesArrayForBuyAction($isSpecial = false)
    {
        $queryResult = $this->em->getRepository(StakeOffering::class)->findBy(["isSpecial" => $isSpecial]);
        $stakes = [];

        /** @var StakeOffering $item */
        foreach($queryResult as $item){
            $stakes[$item->getId()] = $item;
        }

        return $stakes;
    }

    public function getCountForBuyAction(array $simpleStakes, array $specialStakes)
    {
        $count = 0;

        /** @var StakeOffering $stake */
        foreach($simpleStakes as $stake){
            $count += $stake->getCount();
        }

        /** @var StakeOffering $stake */
        foreach($specialStakes as $stake){
            $count += $stake->getCount();
        }

        return $count;
    }

    public function getCostForBuyAction(array $simpleStakes, array $specialStakes)
    {
        $cost = 0;

        /** @var StakeOffering $stake */
        foreach($simpleStakes as $stake){
            $cost += $stake->getCost();
        }

        /** @var StakeOffering $stake */
        foreach($specialStakes as $stake){
            $cost += $stake->getCost();
        }

        return $cost;
    }
}