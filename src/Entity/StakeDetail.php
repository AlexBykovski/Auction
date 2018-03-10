<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="stake_detail")
 */
class StakeDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $count;

    /**
     * One StakeDetail has Many StakePurchases.
     * @ORM\OneToMany(targetEntity="StakePurchase", mappedBy="stakeDetail")
     */
    private $purchases;

    /**
     * One StakeDetail has Many StakeExpenses.
     * @ORM\OneToMany(targetEntity="StakeExpense", mappedBy="stakeDetail")
     */
    private $expenses;

    /**
     * One StakeDetail has Many AutoStakes.
     * @ORM\OneToMany(targetEntity="AutoStake", mappedBy="stakeDetail")
     */
    private $autoStakes;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getPurchases()
    {
        return $this->purchases;
    }

    /**
     * @param mixed $purchases
     */
    public function setPurchases($purchases)
    {
        $this->purchases = $purchases;
    }

    /**
     * @return mixed
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * @param mixed $expenses
     */
    public function setExpenses($expenses)
    {
        $this->expenses = $expenses;
    }

    /**
     * @return mixed
     */
    public function getAutoStakes()
    {
        return $this->autoStakes;
    }

    /**
     * @param mixed $autoStakes
     */
    public function setAutoStakes($autoStakes)
    {
        $this->autoStakes = $autoStakes;
    }
}