<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="main_page")
 */
class MainPage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $sliderImages;

    /**
     * One MainPage has One SoonProduct.
     * @ORM\OneToOne(targetEntity="SoonProduct")
     * @ORM\JoinColumn(name="soon_product_id", referencedColumnName="id")
     */
    private $soonProduct;

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
     * @return array
     */
    public function getSliderImages()
    {
        if(!$this->sliderImages){
            return [];
        }

        return unserialize($this->sliderImages);
    }

    /**
     * @param array $sliderImages
     */
    public function setSliderImages(array $sliderImages)
    {
        $this->sliderImages = serialize($sliderImages);
    }

    /**
     * @return mixed
     */
    public function getSoonProduct()
    {
        return $this->soonProduct;
    }

    /**
     * @param mixed $soonProduct
     */
    public function setSoonProduct($soonProduct)
    {
        $this->soonProduct = $soonProduct;
    }

}