<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bonus")
 */
class Bonus
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
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * Many Bonuses have One BonusPage.
     * @ORM\ManyToOne(targetEntity="BonusPage", inversedBy="bonuses")
     * @ORM\JoinColumn(name="bonus_page_id", referencedColumnName="id")
     */
    private $bonusPage;

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
    public function getBonusPage()
    {
        return $this->bonusPage;
    }

    /**
     * @param mixed $bonusPage
     */
    public function setBonusPage($bonusPage)
    {
        $this->bonusPage = $bonusPage;
    }
}