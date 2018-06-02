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
     * @var integer
     *
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
     * One StakeDetail has One User.
     * @ORM\OneToOne(targetEntity="User", mappedBy="stakeDetail")
     */
    private $user;

    /**
     * @var StakeBalance
     *
     * One StakeDetail has One StakeBalance.
     * @ORM\OneToOne(targetEntity="StakeBalance", mappedBy="stakeDetail", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="stake_balance", referencedColumnName="id")
     */
    private $stakeBalance;

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
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
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

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return StakeBalance
     */
    public function getStakeBalance(): StakeBalance
    {
        return $this->stakeBalance;
    }

    /**
     * @param StakeBalance $stakeBalance
     */
    public function setStakeBalance(StakeBalance $stakeBalance): void
    {
        $this->stakeBalance = $stakeBalance;
    }

    public function spendOneStake()
    {
        --$this->count;

        $this->stakeBalance->spendOneStake();
    }

    public function addStakes(string $type, int $count)
    {
        $this->count += $count;

        $this->stakeBalance->addStakes($type, $count);
    }
}