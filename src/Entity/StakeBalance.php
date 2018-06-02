<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="stake_balance")
 */
class StakeBalance
{
    const REGISTRATION_STAKES = "REGISTRATION_STAKES";
    const REFERRAL_STAKES = "REFERRAL_STAKES";
    const DISCOUNT_STAKES = "DISCOUNT_STAKES";
    const SIMPLE_STAKES = "SIMPLE_STAKES";

    const COUNT_REGISTRATION_STAKES = 25;

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
     * @var StakeDetail
     *
     * One StakeBalance has One StakeDetail.
     * @ORM\OneToOne(targetEntity="StakeDetail", inversedBy="stakeBalance")
     */
    private $stakeDetail;

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

    public function spendManyStakeByType(string $type, int $count)
    {
        switch($type){
            case self::REGISTRATION_STAKES:
                $spendCount = $this->spendManyRegistrationStakes($count);

                break;
            case self::REFERRAL_STAKES:
                $spendCount = $this->spendManyReferralStakes($count);

                break;
            case self::DISCOUNT_STAKES:
                $spendCount = $this->spendManyDiscountStakes($count);

                break;
            case self::SIMPLE_STAKES:
                $spendCount = $this->spendManySimpleStakes($count);

                break;
            default:
                return 0;
        }

        $this->stakeDetail->setCount($this->stakeDetail->getCount() - $spendCount);

        return $spendCount;
    }

    public function spendManyRegistrationStakes(int $count)
    {
        if($count > $this->registrationStakes){
            $returnCount = $this->registrationStakes;

            $this->registrationStakes = 0;
        }
        else{
            $returnCount = $count;

            $this->registrationStakes -= $count;
        }

        return $returnCount;
    }

    public function spendManyReferralStakes(int $count)
    {
        if($count > $this->referralStakes){
            $returnCount = $this->referralStakes;

            $this->referralStakes = 0;
        }
        else{
            $returnCount = $count;

            $this->referralStakes -= $count;
        }

        return $returnCount;
    }

    public function spendManyDiscountStakes(int $count)
    {
        if($count > $this->discountStakes){
            $returnCount = $this->discountStakes;

            $this->discountStakes = 0;
        }
        else{
            $returnCount = $count;

            $this->discountStakes -= $count;
        }

        return $returnCount;
    }

    public function spendManySimpleStakes(int $count)
    {
        if($count > $this->simpleStakes){
            $returnCount = $this->simpleStakes;

            $this->simpleStakes = 0;
        }
        else{
            $returnCount = $count;

            $this->simpleStakes -= $count;
        }

        return $returnCount;
    }

    public function addStakes(string $type, int $count)
    {
        switch($type){
            case self::REGISTRATION_STAKES:
                $this->registrationStakes += $count;

                return true;
            case self::REFERRAL_STAKES:
                $this->referralStakes += $count;

                return true;
            case self::DISCOUNT_STAKES:
                $this->discountStakes += $count;

                return true;
            case self::SIMPLE_STAKES:
                $this->simpleStakes += $count;

                return true;
            default:
                return false;
        }
    }
}