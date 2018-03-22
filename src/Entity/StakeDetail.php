<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $count = 0;

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
     * StakeDetail constructor.
     */
    public function __construct()
    {
        $this->purchases = new ArrayCollection();
        $this->expenses = new ArrayCollection();
        $this->autoStakes = new ArrayCollection();
    }

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
     * @return ArrayCollection
     */
    public function getPurchases()
    {
        return $this->purchases;
    }

    /**
     * @param ArrayCollection $purchases
     */
    public function setPurchases($purchases)
    {
        $this->purchases = $purchases;
    }

    /**
     * @return ArrayCollection
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * @param ArrayCollection $expenses
     */
    public function setExpenses($expenses)
    {
        $this->expenses = $expenses;
    }

    /**
     * @return ArrayCollection
     */
    public function getAutoStakes()
    {
        return $this->autoStakes;
    }

    /**
     * @param ArrayCollection $autoStakes
     */
    public function setAutoStakes($autoStakes)
    {
        $this->autoStakes = $autoStakes;
    }
}