<?php

namespace App\Repository;

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
            ->where("p.winner IS NOT NULL")
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
            ->where("p.winner IS NULL")
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
            ->where("p.winner IS NULL")
            ->getQuery()
            ->getSingleScalarResult();
    }
}