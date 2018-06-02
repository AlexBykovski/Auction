<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="auto_stake_balance")
 */
class AutoStakeBalance
{
    /**
     * @var integer
     *
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
    private $registrationStakes = 0; // got after registration

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $referralStakes = 0; // got from referral system

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $discountStakes = 0; // got by buying stakes with discount

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $simpleStakes = 0; // got by buying stakes

    /**
     * @var AutoStake
     *
     * One AutoStakeBalance has One AutoStake.
     * @ORM\OneToOne(targetEntity="AutoStake", inversedBy="balance")
     */
    private $autoStake;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getRegistrationStakes(): int
    {
        return $this->registrationStakes;
    }

    /**
     * @param int $registrationStakes
     */
    public function setRegistrationStakes(int $registrationStakes): void
    {
        $this->registrationStakes = $registrationStakes;
    }

    /**
     * @return int
     */
    public function getReferralStakes(): int
    {
        return $this->referralStakes;
    }

    /**
     * @param int $referralStakes
     */
    public function setReferralStakes(int $referralStakes): void
    {
        $this->referralStakes = $referralStakes;
    }

    /**
     * @return int
     */
    public function getDiscountStakes(): int
    {
        return $this->discountStakes;
    }

    /**
     * @param int $discountStakes
     */
    public function setDiscountStakes(int $discountStakes): void
    {
        $this->discountStakes = $discountStakes;
    }

    /**
     * @return int
     */
    public function getSimpleStakes(): int
    {
        return $this->simpleStakes;
    }

    /**
     * @param int $simpleStakes
     */
    public function setSimpleStakes(int $simpleStakes): void
    {
        $this->simpleStakes = $simpleStakes;
    }

    /**
     * @return AutoStake
     */
    public function getAutoStake(): AutoStake
    {
        return $this->autoStake;
    }

    /**
     * @param AutoStake $autoStake
     */
    public function setAutoStake(AutoStake $autoStake): void
    {
        $this->autoStake = $autoStake;
    }

    public function spendOneStake()
    {
        if($this->registrationStakes > 0){
            --$this->registrationStakes;

            return true;
        }

        if($this->referralStakes > 0){
            --$this->referralStakes;

            return true;
        }

        if($this->discountStakes > 0){
            --$this->discountStakes;

            return true;
        }

        --$this->simpleStakes;

        return true;
    }

    public function addStakes(string $type, int $count)
    {
        switch($type){
            case StakeBalance::REGISTRATION_STAKES:
                $this->registrationStakes += $count;

                return true;
            case StakeBalance::REFERRAL_STAKES:
                $this->referralStakes += $count;

                return true;
            case StakeBalance::DISCOUNT_STAKES:
                $this->discountStakes += $count;

                return true;
            case StakeBalance::SIMPLE_STAKES:
                $this->simpleStakes += $count;

                return true;
            default:
                return false;
        }
    }
}