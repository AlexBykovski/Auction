<?php

namespace App\Repository;

use App\Entity\Bonus;
use Doctrine\ORM\EntityRepository;

class BonusRepository extends EntityRepository
{
    /**
     * @param array $ids
     * @return mixed
     */
    public function deleteAllExceptIds(array $ids)
    {
        return $this->createQueryBuilder('b')
            ->delete(Bonus::class, 'b')
            ->andWhere('b.id NOT IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }
}