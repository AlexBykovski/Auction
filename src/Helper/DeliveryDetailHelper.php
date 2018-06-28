<?php

namespace App\Helper;

use App\Entity\Product;
use App\Entity\StakeBalance;
use App\Entity\StakeExpense;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DeliveryDetailHelper
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * DeliveryDetailHelper constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getUserCostForAuction(User $user, Product $auction)
    {
        if(($auction->getWinner() instanceof User) && $auction->getWinner()->getId() === $user->getId()){
            return $auction->getCost();
        }

        $stakeExpenses = $this->em->getRepository(StakeExpense::class)->findAllPayableStakes($auction, $user);
        $discount = count($stakeExpenses) * StakeBalance::STAKE_COST;

        $formattedCost = number_format($auction->getBuyCost() - $discount, 2);

        return $formattedCost > 0 ? $formattedCost : 0;
    }
}