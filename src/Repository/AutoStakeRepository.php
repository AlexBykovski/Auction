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
        return $this->createQueryBuilder('ast')
            ->select('ast')
            ->innerJoin('ast.auction', 'p')
            ->where("p.endAt IS NULL")
            ->andWhere("p.startAt < :now")
            ->setParameter("now", new DateTime())
            ->orderBy("ast.updatedAt", "ASC")
            ->getQuery()
            ->getResult();
    }
}