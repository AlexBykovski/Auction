<?php

namespace App\Repository;

use App\Entity\User;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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
    public function findCurrectAuctions($filterParams, $limit = 9, $offset = 0)
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
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.endAt IS NULL");

        $query = $this->getSearchByFilterParams($query, $filterParams);

        return $query
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
    public function findCountNotFinishedAuctions($filterParams)
    {
        $query = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where("p.endAt IS NULL");

        $query = $this->getSearchByFilterParams($query, $filterParams);

        return $query
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

    public function findCurrentAuctionsByUser(User $user)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.stakeExpenses', 'se')
            ->where("p.endAt IS NULL")
            ->andWhere("se.stakeDetail = :stakeDetail")
            ->setParameter("stakeDetail", $user->getStakeDetail())
            ->orderBy("p.startAt", "ASC")
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function findSuccessAuctionsByUser(User $user)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.endAt IS NOT NULL")
            ->andWhere("p.winner = :user")
            ->setParameter("user", $user)
            ->orderBy("p.endAt", "DESC")
            ->getQuery()
            ->getResult();
    }

    protected function getSearchByFilterParams(QueryBuilder $query, $filterParams)
    {
        $orXCategories = null;

        foreach($filterParams["categories"] as $category){
            if(!$orXCategories){
                $orXCategories = $query->expr()->orX();
            }

            $orXCategories->add($query->expr()->like('p.categories', "'%" . $category . "%'"));
        }

        if($orXCategories){
            $query = $query->andWhere($orXCategories);
        }

        if(count($filterParams["times"])){
            if(in_array("active", $filterParams["times"])){
                $query = $query->andWhere("p.startAt <= :now");
            }

            if(in_array("soon", $filterParams["times"])){
                $query = $query->andWhere("p.startAt > :now");
            }

            $query = $query->setParameter("now", new DateTime());
        }

        return $query;
    }
}