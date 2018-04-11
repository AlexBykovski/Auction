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
     * @ORM\Column(type="boolean")
     */
    private $isWinEnd;

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
     * Many AutoStakes have One StakeDetail.
     * @ORM\ManyToOne(targetEntity="StakeDetail", inversedBy="autoStakes")
     * @ORM\JoinColumn(name="stake_detail_id", referencedColumnName="id")
     */
    private $stakeDetail;

    /**
     * Many AutoStakes have One Product.
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="autoStakes")
     * @ORM\JoinColumn(name="auction_id", referencedColumnName="id")
     */
    private $auction;

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
    public function getStakeDetail()
    {
        return $this->stakeDetail;
    }

    /**
     * @param StakeDetail $stakeDetail
     */
    public function setStakeDetail($stakeDetail)
    {
        $this->stakeDetail = $stakeDetail;
    }

    /**
     * @return Product
     */
    public function getAuction()
    {
        return $this->auction;
    }

    /**
     * @param Product $auction
     */
    public function setAuction($auction)
    {
        $this->auction = $auction;
    }
}