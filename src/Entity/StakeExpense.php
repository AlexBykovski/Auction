<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StakeExpenseRepository")
 * @ORM\Table(name="stake_expense")
 */
class StakeExpense
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
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Many StakeExpenses have One Product.
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="stakeExpenses")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var StakeDetail
     *
     * Many StakeExpenses have One StakeDetail.
     * @ORM\ManyToOne(targetEntity="StakeDetail", inversedBy="expenses")
     * @ORM\JoinColumn(name="stake_detail_id", referencedColumnName="id")
     */
    private $stakeDetail;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * StakeExpense constructor.
     */
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
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}