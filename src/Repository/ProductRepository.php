<?php

namespace App\Repository;

use DateInterval;
use DateTime;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    /**
     * @param integer $limit
     *
     * @return mixed
     */
    public function findLastEnded($limit = 9)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.endAt IS NOT NULL")
            ->orderBy("p.endAt", "DESC")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param integer $limit
     * @param integer $offset
     *
     * @return mixed
     */
    public function findCurrectAuctions($limit = 9, $offset = 0)
    {
//        return $this->createQueryBuilder('p')
//            ->select('p')
//            ->where("p.winner IS NULL")
//            ->andWhere("p.startAt <= :now")
//            ->setParameter("now", new DateTime())
//            ->orderBy("p.startAt", "ASC")
//            ->setMaxResults($limit)
//            ->getQuery()
//            ->getResult();
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.endAt IS NULL")
            ->orderBy("p.startAt", "ASC")
            ->setFirstResult( $offset )
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param integer $limit
     *
     * @return mixed
     */
    public function findCountNotFinishedAuctions()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where("p.endAt IS NULL")
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAuctionsByIds($ids)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.id IN(:ids)")
            ->setParameter("ids", $ids)
            ->orderBy("p.startAt", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findAllAlreadyFinished()
    {
        $nowMinus10Sec = new DateTime();
        $nowMinus10Sec->sub(new DateInterval("PT10S"));

        return $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.timer', 't')
            ->where("t.updatedAt < :checkTimer")
            ->andWhere("p.endAt IS NULL")
            ->setParameter("checkTimer", $nowMinus10Sec)
            ->getQuery()
            ->getResult();
    }
}