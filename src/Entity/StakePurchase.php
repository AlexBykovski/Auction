<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StakePurchaseRepository")
 * @ORM\Table(name="stake_purchase")
 */
class StakePurchase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\GreaterThan(
     *     value = 0
     * )
     */
    private $cost;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\GreaterThan(
     *     value = 0
     * )
     */
    private $count;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Many StakePurchases have One StakeDetail.
     * @ORM\ManyToOne(targetEntity="StakeDetail", inversedBy="purchases")
     * @ORM\JoinColumn(name="stake_detail_id", referencedColumnName="id")
     */
    private $stakeDetail;

    public function __construct()
    {
        $this->createdAt = new DateTime();
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
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getStakeDetail()
    {
        return $this->stakeDetail;
    }

    /**
     * @param mixed $stakeDetail
     */
    public function setStakeDetail($stakeDetail)
    {
        $this->stakeDetail = $stakeDetail;
    }
}