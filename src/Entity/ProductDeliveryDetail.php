<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_delivery_detail")
 */
class ProductDeliveryDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $postIndex;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $country;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $city;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $address;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

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
    public function getPostIndex()
    {
        return $this->postIndex;
    }

    /**
     * @param mixed $postIndex
     */
    public function setPostIndex($postIndex)
    {
        $this->postIndex = $postIndex;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    public function setUserDeliveryDetail(UserDeliveryDetail $deliveryDetail)
    {
        $this->address = $deliveryDetail->getAddress();
        $this->city = $deliveryDetail->getCity();
        $this->country = $deliveryDetail->getCountry();
        $this->name = $deliveryDetail->getName();
        $this->phone = $deliveryDetail->getPhone();
        $this->postIndex = $deliveryDetail->getPostIndex();
        $this->note = $deliveryDetail->getNote();
    }
}