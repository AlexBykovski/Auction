<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $mainPhoto;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $photos;

    /**
     * @ORM\Column(type="integer", options={"default" : 100}, nullable=false)
     */
    private $cost = 100;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $characteristics;

    /**
     * @ORM\Column(type="text")
     */
    private $conditions;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $categories;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $startAt;

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
    public function getMainPhoto()
    {
        return $this->mainPhoto;
    }

    /**
     * @param mixed $mainPhoto
     */
    public function setMainPhoto($mainPhoto)
    {
        $this->mainPhoto = $mainPhoto;
    }

    /**
     * @return array
     */
    public function getPhotos()
    {
        if(!$this->photos){
            return [];
        }

        return unserialize($this->photos);
    }

    /**
     * @param array $photos
     */
    public function setPhotos(array $photos)
    {
        $this->photos = serialize($photos);
    }

    public function addPhoto(string $photo){
        $photos = $this->getPhotos();
        $photos[] = $photo;

        $this->setPhotos($photos);
    }

    /**
     * @return integer
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCharacteristics()
    {
        return $this->characteristics;
    }

    /**
     * @param mixed $characteristics
     */
    public function setCharacteristics($characteristics)
    {
        $this->characteristics = $characteristics;
    }

    /**
     * @return mixed
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param mixed $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        if(!$this->categories){
            return [];
        }

        return unserialize($this->categories);
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = serialize($categories);
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param DateTime $startAt
     */
    public function setStartAt(DateTime $startAt)
    {
        $this->startAt = $startAt;
    }
}