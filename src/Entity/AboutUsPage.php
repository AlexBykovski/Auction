<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="about_us_page")
 */
class AboutUsPage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $information;

    /**
     * @ORM\Column(type="integer")
     */
    private $assortment;

    /**
     * @ORM\Column(type="integer")
     */
    private $countries;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $achievementImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $experience;

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
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @param mixed $information
     */
    public function setInformation($information)
    {
        $this->information = $information;
    }

    /**
     * @return mixed
     */
    public function getAssortment()
    {
        return $this->assortment;
    }

    /**
     * @param mixed $assortment
     */
    public function setAssortment($assortment)
    {
        $this->assortment = $assortment;
    }

    /**
     * @return mixed
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param mixed $countries
     */
    public function setCountries($countries)
    {
        $this->countries = $countries;
    }

    /**
     * @return mixed
     */
    public function getAchievementImage()
    {
        return $this->achievementImage;
    }

    /**
     * @param mixed $achievementImage
     */
    public function setAchievementImage($achievementImage)
    {
        $this->achievementImage = $achievementImage;
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }
}