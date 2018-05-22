<?php

namespace App\Entity;

use DateInterval;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_timer")
 */
class ProductTimer
{
    const TIME = 10;// in seconds

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * One ProductTimer has One Product.
     * @ORM\OneToOne(targetEntity="Product", inversedBy="timer")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="cascade")
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(
     *     value = 0
     * )
     */
    private $time = self::TIME; //in seconds

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * ProductTimer constructor.
     */
    public function __construct()
    {
        $this->updatedAt = new DateTime();
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
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function restartTimer()
    {
        $this->updatedAt = new DateTime();
        $this->time = self::TIME;
    }

    public function getEndTimeInMS()
    {
        $endAt = clone $this->updatedAt;
        $endAt->add(new DateInterval("PT" . $this->time . "S"));

        return $endAt->getTimestamp() * 1000;
    }
}