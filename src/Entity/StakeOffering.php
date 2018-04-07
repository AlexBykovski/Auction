<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="stake_offering")
 */
class StakeOffering
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $cost;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $count;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isSpecial;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 100})
     */
    private $percent = 0;

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
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param integer $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param integer $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getIsSpecial()
    {
        return $this->isSpecial;
    }

    /**
     * @param mixed $isSpecial
     */
    public function setIsSpecial($isSpecial)
    {
        $this->isSpecial = $isSpecial;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param mixed $percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    }
}