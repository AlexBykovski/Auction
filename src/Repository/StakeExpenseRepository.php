<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\StakeBalance;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class StakeExpenseRepository extends EntityRepository
{
    public function findAllPayableStakes(Product $auction, User $user)
    {
        return $this->createQueryBuilder('se')
            ->select('se')
            ->where("se.product = :auction")
            ->andWhere("se.stakeDetail = :stakeDetail")
            ->andWhere('se.type IN (:payableTypes)')
            ->setParameter('auction', $auction)
            ->setParameter('stakeDetail', $user->getStakeDetail())
            ->setParameter('payableTypes', [StakeBalance::DISCOUNT_STAKES, StakeBalance::SIMPLE_STAKES])
            ->getQuery()
            ->getResult();
    }
}