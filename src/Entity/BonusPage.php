<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bonus_page")
 */
class BonusPage
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
    private $titleDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     */
    private $titleBonuses;

    /**
     * One BonusPage has Many Bonuses.
     * @ORM\OneToMany(targetEntity="Bonus", mappedBy="bonusPage", cascade={"persist"})
     */
    private $bonuses;

    /**
     * BonusPage constructor.
     */
    public function __construct()
    {
        $this->bonuses = new ArrayCollection();
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
    public function getTitleDescription()
    {
        return $this->titleDescription;
    }

    /**
     * @param mixed $titleDescription
     */
    public function setTitleDescription($titleDescription)
    {
        $this->titleDescription = $titleDescription;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getTitleBonuses()
    {
        return $this->titleBonuses;
    }

    /**
     * @param mixed $titleBonuses
     */
    public function setTitleBonuses($titleBonuses)
    {
        $this->titleBonuses = $titleBonuses;
    }

    /**
     * @return mixed
     */
    public function getBonuses()
    {
        return $this->bonuses;
    }

    /**
     * @param mixed $bonuses
     */
    public function setBonuses($bonuses)
    {
        $this->bonuses = $bonuses;
    }

    public function addBonus(Bonus $bonus){
        $this->bonuses->add($bonus);
    }
}