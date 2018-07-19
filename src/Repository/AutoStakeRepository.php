<?php

namespace App\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

class AutoStakeRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findStarted()
    {
        return $this->createQueryBuilder('as')
            ->select('as')
            ->innerJoin('as.auction', 'p')
            ->where("p.endAt IS NULL")
            ->andWhere("p.startAt < :now")
            ->setParameter("now", new DateTime())
            ->orderBy("as.updatedAt", "ASC")
            ->getQuery()
            ->getResult();
    }
}