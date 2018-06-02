<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="auto_stake")
 */
class AutoStake
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $isWinEnd = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endAt;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\GreaterThan(
     *     value = 0,
     *     groups={"create_autostake"}
     * )
     */
    private $count;

    /**
     * @var StakeDetail
     *
     * Many AutoStakes have One StakeDetail.
     * @ORM\ManyToOne(targetEntity="StakeDetail", inversedBy="autoStakes")
     * @ORM\JoinColumn(name="stake_detail_id", referencedColumnName="id")
     */
    private $stakeDetail;

    /**
     * @var Product
     *
     * Many AutoStakes have One Product.
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="autoStakes")
     * @ORM\JoinColumn(name="auction_id", referencedColumnName="id")
     */
    private $auction;

    /**
     * @var AutoStakeBalance
     *
     * One AutoStake has One AutoStakeBalance.
     * @ORM\OneToOne(targetEntity="AutoStakeBalance", mappedBy="autoStake", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="balance", referencedColumnName="id", onDelete="cascade")
     */
    private $balance;

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
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getIsWinEnd()
    {
        return $this->isWinEnd;
    }

    /**
     * @param mixed $isWinEnd
     */
    public function setIsWinEnd($isWinEnd)
    {
        $this->isWinEnd = $isWinEnd;
    }

    /**
     * @return mixed
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param mixed $endAt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
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
     * @return StakeDetail
     */
    public function getStakeDetail(): StakeDetail
    {
        return $this->stakeDetail;
    }

    /**
     * @param StakeDetail $stakeDetail
     */
    public function setStakeDetail(StakeDetail $stakeDetail): void
    {
        $this->stakeDetail = $stakeDetail;
    }

    /**
     * @return Product
     */
    public function getAuction(): Product
    {
        return $this->auction;
    }

    /**
     * @param Product $auction
     */
    public function setAuction(Product $auction): void
    {
        $this->auction = $auction;
    }

    /**
     * @return AutoStakeBalance
     */
    public function getBalance(): AutoStakeBalance
    {
        return $this->balance;
    }

    /**
     * @param AutoStakeBalance $balance
     */
    public function setBalance(AutoStakeBalance $balance): void
    {
        $this->balance = $balance;
    }

    public function spendOneStake()
    {
        --$this->count;

        $this->balance->spendOneStake();
    }

    public function addStakesFromUser(int $count)
    {
        $leftAdded = $count;

        if($this->stakeDetail->getCount() < $count){
            return false;
        }

        $this->count += $count;

        $registrationStakes = $this->addStakesFromUserByType(StakeBalance::REGISTRATION_STAKES, $leftAdded);
        $leftAdded -= $registrationStakes;

        if($leftAdded === 0){
            return true;
        }

        $referralStakes = $this->addStakesFromUserByType(StakeBalance::REFERRAL_STAKES, $leftAdded);
        $leftAdded -= $referralStakes;

        if($leftAdded === 0){
            return true;
        }

        $discountStakes = $this->addStakesFromUserByType(StakeBalance::DISCOUNT_STAKES, $leftAdded);
        $leftAdded -= $discountStakes;

        if($leftAdded === 0){
            return true;
        }

        $this->addStakesFromUserByType(StakeBalance::SIMPLE_STAKES, $leftAdded);

        return true;
    }

    public function returnStakesToUser()
    {
        $autoStakeBalance = $this->balance;

        $this->stakeDetail->addStakes(StakeBalance::REGISTRATION_STAKES, $autoStakeBalance->getRegistrationStakes());
        $this->stakeDetail->addStakes(StakeBalance::REFERRAL_STAKES, $autoStakeBalance->getReferralStakes());
        $this->stakeDetail->addStakes(StakeBalance::DISCOUNT_STAKES, $autoStakeBalance->getDiscountStakes());
        $this->stakeDetail->addStakes(StakeBalance::SIMPLE_STAKES, $autoStakeBalance->getSimpleStakes());

        $this->count = 0;

        $autoStakeBalance->setRegistrationStakes(0);
        $autoStakeBalance->setReferralStakes(0);
        $autoStakeBalance->setDiscountStakes(0);
        $autoStakeBalance->setSimpleStakes(0);
    }

    protected function addStakesFromUserByType(string $type, int $count)
    {
        $userBalance = $this->stakeDetail->getStakeBalance();

        $stakes = $userBalance->spendManyStakeByType($type, $count);
        $this->balance->addStakes($type, $stakes);

        return $stakes;
    }
}